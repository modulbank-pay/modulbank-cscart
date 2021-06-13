<?php

use Tygh\Payments\Processors\ModulbankLib;

ModulbankLib::init();

if (defined('PAYMENT_NOTIFICATION')) {
    $order_id = 0;
    if(!empty($_REQUEST['order_id'])) {
        $order_id = intval($_REQUEST['order_id']);
    }
    $order_info = fn_get_order_info($order_id);
    if (empty($processor_data)) {
        $processor_data = fn_get_processor_data($order_info['payment_id']);
    }

    if ($mode === 'return') {
        fn_modulbankpayment_log($_POST, 'notification', $processor_data['processor_params']);
        $key = $processor_data['processor_params']['mode'] == 'test'?
            $processor_data['processor_params']['test_secret_key']:
            $processor_data['processor_params']['secret_key'];

        $signature = ModulbankHelper::calcSignature($key, $_POST);

        if(strcmp($signature, $_POST['signature']) === 0){
            if(($_POST['state'] === 'COMPLETE' && $order_info['status'] != $processor_data['processor_params']['status_capture']) || $_POST['state'] === 'AUTHORIZED' ) {
                $pp_response = array();

                $pp_response['order_status'] = $processor_data['processor_params']['status_success'];
                $pp_response['reason_text'] = __('transaction_approved');
                $pp_response['transaction_id'] = $_POST['transaction_id'];
                fn_finish_payment($order_id, $pp_response);
                fn_update_order_payment_info($order_id, $pp_response);
                fn_change_order_status($order_id, $pp_response['order_status'], '',false);

            }
        }
    }  elseif ($mode === 'fail' )  {
        $pp_response = array();
        $pp_response['order_status'] = 'D';

        fn_finish_payment($order_id, $pp_response);
        fn_order_placement_routines('route', $order_id);
    } elseif ($mode === 'cancel' )  {
        fn_redirect('checkout.' . (Registry::get('settings.General.checkout_style') != 'multi_page' ? 'checkout' : 'summary'));
    } elseif ($mode === 'success')  {
        $pp_response = array();
        $force_notification = array();

        if ($order_info['status'] == 'P') {
            $force_notification = false;
        }

        if ($order_info['status'] == STATUS_INCOMPLETED_ORDER) {
            $pp_response['order_status'] = 'O';
            fn_finish_payment($order_id, $pp_response);
        }
        $transaction_result = fn_modulbankpayment_get_transaction_status($processor_data['processor_params'], $_GET['transaction_id']);
        $payment_status_text = __('modulbankpayment_transaction_status_wait');
        if (isset($transaction_result->status) && $transaction_result->status == "ok") {

            switch ($transaction_result->transaction->state) {
                case 'PROCESSING':$payment_status_text = __('modulbankpayment_transaction_status_processing');
                    break;
                case 'WAITING_FOR_3DS':$payment_status_text = __('modulbankpayment_transaction_status_3ds');
                    break;
                case 'FAILED':$payment_status_text = __('modulbankpayment_transaction_status_failed');
                    break;
                case 'COMPLETE':$payment_status_text = __('modulbankpayment_transaction_status_complete');
                    break;
                case 'AUTHORIZED':$payment_status_text = __('modulbankpayment_transaction_status_complete');
                    break;
                default:$payment_status_text = __('modulbankpayment_transaction_status_wait');
            }
        }
        //fn_set_notification('N', __('order_placed'), __('text_order_placed_successfully'));
        fn_set_notification('I', __('modulbankpayment_transaction_status'), $payment_status_text);
        fn_order_placement_routines('route', $order_id, $force_notification);
    }
    exit;

} else {

    $callback_url = fn_url("payment_notification.return?payment=modulbankpayment", AREA, 'current');

    $sysinfo = [
            'language' => 'PHP ' . phpversion(),
            'plugin'   => fn_get_addon_version('modulbankpayment'),
            'cms'      => "CS-Cart ".PRODUCT_VERSION,
        ];

    $total = ModulbankLib::convertSum($order_info['total']);

    $receipt = new ModulbankReceipt($processor_data['processor_params']['sno'], $processor_data['processor_params']['payment_method'], $total);

    $order_info = ModulbankLib::prepareOrder($order_info);

    foreach ($order_info['products'] as $cart_id => $product) {
        if($processor_data['processor_params']['product_vat'] === "0") {
            $vat = ModulbankLib::getTaxTypeByTaxIds(empty($product['receipt_tax_ids']) ? array() : $product['receipt_tax_ids']);
        } else {
            $vat = $processor_data['processor_params']['product_vat'];
        }
        $price = ModulbankLib::convertSum($product['price']);
        $receipt->addItem($product['product'], $price, $vat, $processor_data['processor_params']['payment_object'] ,$product['amount'] );
    }
    if (!empty($order_info['shipping_cost'])) {
        if($processor_data['processor_params']['delivery_vat'] === "0") {
            $vat = ModulbankLib::getTaxTypeByTaxIds(empty($product['receipt_shipping_tax_ids']) ? array() : $product['receipt_shipping_tax_ids']);
        } else {
            $vat = $processor_data['processor_params']['delivery_vat'];
        }
        $price = ModulbankLib::convertSum($order_info['shipping_cost']);
        $receipt->addItem('Доставка', $price, $vat, $processor_data['processor_params']['delivery_payment_object']);
    }

    $post_data = array(
        'merchant'        => $processor_data['processor_params']['merchant'],
        'amount'          => $total,
        'order_id'        => $order_id,
        'testing'         => $processor_data['processor_params']['mode'] == 'test' ? 1 : 0,
        'preauth'         => $processor_data['processor_params']['preauth'],
        'description'     => 'Оплата заказа №' . $order_id,
        'success_url'     => $processor_data['processor_params']['success_url']."&order_id=$order_id",
        'fail_url'        => $processor_data['processor_params']['fail_url']."&order_id=$order_id",
        'cancel_url'      => $processor_data['processor_params']['cancel_url']."&order_id=$order_id",
        'callback_url'    => $callback_url,
        'client_name'     => $order_info['b_firstname'] . ' ' . $order_info['b_lastname'],
        'client_email'    => $order_info['email'],
        'receipt_contact' => $order_info['email'],
        'receipt_items'   => $receipt->getJson(),
        'unix_timestamp'  => time(),
        'sysinfo'         => json_encode($sysinfo),
        'salt'            => ModulbankHelper::getSalt(),
    );

    if ($processor_data['processor_params']['show_custom_pm']) {
        $methods = ['card', 'sbp', 'applepay', 'googlepay'];
        $methods = array_filter($methods, function ($method) use ($processor_data) {
            return $processor_data['processor_params'][$method];
        });
        $post_data['show_payment_methods'] = json_encode(array_values($methods));
    }

    $key = $processor_data['processor_params']['mode'] == 'test'?
            $processor_data['processor_params']['test_secret_key']:
            $processor_data['processor_params']['secret_key'];

    $post_data['signature'] = ModulbankHelper::calcSignature($key, $post_data);

    fn_modulbankpayment_log($order_info, 'orderInfo', $processor_data['processor_params']);
    fn_modulbankpayment_log($post_data, 'paymentForm', $processor_data['processor_params']);

    $payment_url = 'https://pay.modulbank.ru/pay';

    fn_create_payment_form($payment_url, $post_data, 'Модульбанк', false);
}

exit;
