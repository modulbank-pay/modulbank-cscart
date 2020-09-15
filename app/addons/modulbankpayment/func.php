<?php
use Tygh\Payments\Processors\ModulbankLib;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_modulbankpayment_install()
{
    fn_modulbankpayment_uninstall();

    $_data = array(
        'processor' => 'Модульбанк: Интернет-Эквайринг',
        'processor_script' => 'modulbankpayment.php',
        'processor_template' => 'views/orders/components/payments/cc_outside.tpl',
        'admin_template' => 'modulbankpayment.tpl',
        'callback' => 'Y',
        'type' => 'P',
        'addon' => 'modulbankpayment'
    );

    db_query("INSERT INTO ?:payment_processors ?e", $_data);
}

function fn_modulbankpayment_uninstall()
{
    db_query("DELETE FROM ?:payment_processors WHERE processor_script = ?s", "modulbankpayment.php");
}

function fn_modulbankpayment_get_transaction_status($params, $transaction_id)
{
    $key = $params['mode'] == 'test' ? $params['test_secret_key'] : $params['secret_key'];

    $result = ModulbankHelper::getTransactionStatus(
        $params['merchant'],
        $transaction_id,
        $key
    );
    return json_decode($result);
}

function fn_modulbankpayment_log($data, $category, $params)
{
    if ($params['logging']) {
        $path = fn_get_files_dir_path();
        fn_mkdir($path);
        $filename   = $path . 'modulbank.log';
        ModulbankHelper::log($filename, $data, $category, $params['log_size_limit']);
    }
}

/**
 * Hook handler: after order status changed.
 *
 * @param string $status_to     Order status to
 * @param string $status_from   Order status from
 * @param array  $order_info    Order data
 */
function fn_modulbankpayment_change_order_status($status_to, $status_from, $order_info)
{
    if ($order_info['is_parent_order'] === 'Y') {
        return;
    }

    $payment_id = isset($order_info['payment_id']) ? $order_info['payment_id'] : 0;
    $payment_data = fn_get_payment_method_data($payment_id);
    if ($payment_data['processor'] == 'Модульбанк: Интернет-Эквайринг') {
        ModulbankLib::init();
        if ($payment_data['processor_params']['preauth'] && $payment_data['processor_params']['status_capture'] == $status_to) {
            $total = ModulbankLib::convertSum($order_info['total']);

            $receipt = new ModulbankReceipt($payment_data['processor_params']['sno'], $payment_data['processor_params']['payment_method'], $total);

            $order_info = ModulbankLib::prepareOrder($order_info);

            foreach ($order_info['products'] as $cart_id => $product) {
                if($payment_data['processor_params']['product_vat'] === "0") {
                    $vat = ModulbankLib::getTaxTypeByTaxIds(empty($product['receipt_tax_ids']) ? array() : $product['receipt_tax_ids']);
                } else {
                    $vat = $payment_data['processor_params']['product_vat'];
                }
                $price = ModulbankLib::convertSum($product['price']);
                $receipt->addItem($product['product'], $price, $vat, $payment_data['processor_params']['payment_object'] ,$product['amount'] );
            }
            if (!empty($order_info['shipping_cost'])) {
                if($payment_data['processor_params']['delivery_vat'] === "0") {
                    $vat = ModulbankLib::getTaxTypeByTaxIds(empty($product['receipt_shipping_tax_ids']) ? array() : $product['receipt_shipping_tax_ids']);
                } else {
                    $vat = $payment_data['processor_params']['delivery_vat'];
                }
                $price = ModulbankLib::convertSum($order_info['shipping_cost']);
                $receipt->addItem('Доставка', $price, $vat, $payment_data['processor_params']['delivery_payment_object']);
            }


            $key = $payment_data['processor_params']['mode'] == 'test'?
                    $payment_data['processor_params']['test_secret_key']:
                    $payment_data['processor_params']['secret_key'];

            $payment_info = fn_modulbankpayment_get_payment_info($order_info['order_id']);
            $post_data = [
                'merchant'        => $payment_data['processor_params']['merchant'],
                'amount'          => $total,
                'transaction'     => $payment_info['transaction_id'],
                'receipt_contact' => $order_info['email'],
                'receipt_items'   => $receipt->getJson(),
                'unix_timestamp'  => time(),
                'salt'            => ModulbankHelper::getSalt(),
            ];
            $post_data['signature'] = ModulbankHelper::calcSignature($key, $post_data);

            fn_modulbankpayment_log($post_data, 'confirm', $payment_data['processor_params']);
            $result = ModulbankHelper::capture($post_data, $key);
            fn_modulbankpayment_log($result, 'confirmResponse', $payment_data['processor_params']);

        }

        if ($payment_data['processor_params']['status_refund'] == $status_to) {
            $total = ModulbankLib::convertSum($order_info['total']);
            $payment_info = fn_modulbankpayment_get_payment_info($order_info['order_id']);
            $key = $payment_data['processor_params']['mode'] == 'test'?
                    $payment_data['processor_params']['test_secret_key']:
                    $payment_data['processor_params']['secret_key'];
            $result = ModulbankHelper::refund(
                    $payment_data['processor_params']['merchant'],
                    $total,
                    $payment_info['transaction_id'],
                    $key
                );
            fn_modulbankpayment_log([$payment_data['processor_params']['merchant'],
                    $total,
                    $payment_info['transaction_id']], 'refund', $payment_data['processor_params']);
            fn_modulbankpayment_log($result, 'refundResponse', $payment_data['processor_params']);
        }
    }
}

function fn_modulbankpayment_get_payment_info($order_id)
{
    $payment_info = db_get_field("SELECT data FROM ?:order_data WHERE order_id = ?i AND type = 'P'", $order_id);
    if (!empty($payment_info)) {
        $payment_info = unserialize(fn_decrypt_text($payment_info));
    }

    return $payment_info;
}

