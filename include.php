<?php
//设置时区
date_default_timezone_set("PRC");

define("ROOT",dirname(__FILE__));
set_include_path(".".PATH_SEPARATOR.ROOT."/lib".PATH_SEPARATOR.ROOT."/core".PATH_SEPARATOR.ROOT."/configs".PATH_SEPARATOR.get_include_path());

require_once 'dir.func.php';
require_once 'file.func.php';
require_once 'common.func.php';