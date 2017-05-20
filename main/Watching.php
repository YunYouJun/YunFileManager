<?php
@$path=$_REQUEST['path'];
@$filename=$_REQUEST['filename'];
@$fileTitle=isset($_REQUEST['fileTitle'])?$_REQUEST['fileTitle']:basename($_REQUEST['filename']);
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>视频：<?php echo $filename; ?></title>
		<?php include("../common/headlink.php");?>
		<link rel="stylesheet" type="text/css" href="../css/player/musicplayer.css">
	</head>
	<body class="container">
		<?php include('../common/header.php');?>
		<div class="embed-responsive embed-responsive-16by9">
		  <video class="embed-responsive-item" src="../file/MyVideo/all.mov" controls="controls">
		  	您的浏览器可能不支持视频在线播放，请使用最新chrome浏览器。
		  </video>
		</div>
		<div class="progress">
			<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;">
			    <input type="range" name="" id="volume" min="0" max="100" value="60">
			</div>
		</div>
		<?php include('../common/footer.php');?>
	</body>

</html>