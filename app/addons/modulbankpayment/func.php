<?php
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

