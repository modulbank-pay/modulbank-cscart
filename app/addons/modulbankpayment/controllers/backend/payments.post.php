<?php

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    return;
}
/*
if ($mode == 'update' || $mode == 'manage') {

    $processors = Tygh::$app['view']->getTemplateVars('payment_processors');

    if (!empty($processors)) {

        foreach ($processors as &$processor) {
            if ($processor['processor'] == 'Модульбанк: Интернет-Эквайринг') {
                $processor['russian'] = 'Y';
                $processor['type'] = 'R';
                $processor['position'] = 'a_30';
            }
        }
        $processors = fn_sort_array_by_key($processors, 'position');

        Tygh::$app['view']->assign('payment_processors', $processors);
    }

}
*/
if ($mode == 'processor') {
    $defaults = array(
        'mode' => 'test',
        'success_url' => fn_url("payment_notification.success?payment=modulbankpayment", 'C', 'current'),
        'fail_url' => fn_url("payment_notification.fail?payment=modulbankpayment", 'C', 'current'),
        'cancel_url' => fn_url("payment_notification.cancel?payment=modulbankpayment", 'C', 'current'),
        'modulbankpayment_sno' => 'usn_income_outcome',
        'payment_object' => 'commodity',
        'delivery_payment_object' => 'service',
        'log_size_limit' => 10
    );
    $processor_name = Tygh::$app['view']->getTemplateVars('processor_name');

    if ($processor_name == 'Модульбанк: Интернет-Эквайринг') {
        $processor_params = Tygh::$app['view']->getTemplateVars('processor_params');
        foreach($defaults as $key => $param){
            if (empty($processor_params[$key])) {
                $processor_params[$key] = $defaults[$key];
            }
        }
        Tygh::$app['view']->assign('processor_params', $processor_params);
    }
}


