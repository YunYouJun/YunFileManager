<?php 
require_once '../include.php';
$act=$_REQUEST['act'];
@$path=$_REQUEST['path'];
@$filename=$_REQUEST['filename'];
@$fileTitle=isset($_REQUEST['fileTitle'])?$_REQUEST['fileTitle']:basename($_REQUEST['filename']);

//下载文件操作  囧，因为header前面不能有其他输出，就提到最前面了！
if($act==="downFile"){
	downFile($filename);
	exit();
}

$html=<<<EOF
	<!DOCTYPE html>
	<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>文件：{$fileTitle}</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
		<link rel="stylesheet" href="../plugins/highlight/styles/default.css">
		<script src="../plugins/highlight/highlight.pack.js"></script>  
    	<script>hljs.initHighlightingOnLoad();</script>  
EOF;
echo $html;
require_once '../common/headlink.php';
echo "</head>";
echo '<div class="page-header"><h4 class="text-center">'.$fileTitle.'</h4></div>';
echo "<body><div class='container-fluid'>";
switch ($act) {
	case 'showContent':
		showContent($filename);
		break;
	case 'editContent':
		editContent($path,$filename);
		break;
	case 'doEdit':
		doEdit($path,$filename);
		break;
	default:
		break;
}
echo "</div></body>";
