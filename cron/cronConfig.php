<?php
/**
 * Created by PhpStorm.
 * User: asad
 * Date: 4/17/2015
 * Time: 4:17 PM
 */

//require_once(__DIR__.'/../global.php');
include_once(__DIR__."/../_models/setting/global.setting.php");// connection setting db
require_once(__DIR__."/../_models/functions/functions.as.php");// execute query functions
require_once(__DIR__."/../".ADMIN_FOLDER."/functions/check_license.php"); // License

global $db,$dbF,$db_function;
