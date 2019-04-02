<?php
if (!defined('BOOTSTRAP')) { die('Access denied');  }
use Tygh\Payments\Processors\ModulbankLib;

ModulbankLib::init();

if ($mode === 'downloadlog') {
    ModulbankHelper::sendPackedLogs(fn_get_files_dir_path());
    exit();
}