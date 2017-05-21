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
		<link rel="stylesheet" type="text/css" href="../css/player/player.css">
		<script type="text/javascript" src="../js/player/player.js"></script>
	</head>
	<body class="container">
		<div class="page-header text-center">
			<span class="fa fa-video-camera medium-icon"></span>
			<span class="medium-icon">&nbsp;—&nbsp;</span>
			<span style="font-size: 222%;"><?php echo $fileTitle;?></span>
		</div>

		<div class="embed-responsive embed-responsive-16by9">
		  <video class="embed-responsive-item" src="<?php echo $filename;?>" id="myvideo" loop>
		  	您的浏览器可能不支持视频在线播放，请使用最新chrome浏览器。
		  </video>
		</div>
			<div id="PlayerControl">
			<div class="progress form-control" id="audioProgressBar" style="margin-bottom:5px;background:rgba(255,255,255,0.7);position: relative;" title="进度条">
				<input type="range" name="" id="progress" min="0" max="100" value="60" style="position: absolute;z-index: 1;">
				<div class="progress-bar progress-bar-striped active" id="dispalyProgress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="position: relative; width: 0%;">
				</div>
			</div>
			<!-- 控制器 -->
		 	<div class="input-group">
		       <span class="input-group-btn">
		         <button class="btn btn-default" type="button">
		         	<span id="playStatus" class="glyphicon glyphicon-play" title="播放"></span>
		         </button>
		       </span>
		       <span class="input-group-addon">
		       	<span id="curTime"></span>
		       	<span>/</span>
		       	<span id="totalTime"></span>	 
		       </span>
		       <span class="input-group-btn">
		       	<button class="btn btn-default" type="button" style="border-radius: 0px;border-left: 0px;border-right:0px;">
		       			<span id="soundStatus" class="glyphicon glyphicon-volume-up" title="静音"></span>
		       	</button>
		       </span>
		       <div class="form-control" id="volumeBar" style="position: relative;background-color: #fff; padding: 5px;" title="60">
		       	<input type="range" name="" id="volume" min="0" max="100" value="60" style="position: absolute;z-index: 1;">
		       	<div class="progress-bar progress-bar-striped active" id="dispalyVolume" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="position: relative; width: 60%;">
		       	</div>
		       </div>
		       <span class="input-group-btn">
			      	<a class="btn btn-default" href="../main/doFileAction.php?act=downFile&path=<?php echo $path;?>&filename=<?php echo $filename;?>">
			       		<span class="glyphicon glyphicon-download-alt" title="下载"></span>
			       	</a>
		       </span>
		    </div><!-- /input-group -->
			</div>
		<?php include('../common/footer.php');?>
		<script type="text/javascript">
			var video = $('#myvideo')[0];
			// audio.oncanplaythrough = function(){
			video.volume = 0.6; 
			// };

			var change = function($input){  
			// console.log(media.volume);  
			};
			$('#volume').RangeSlider({ min: 0,   max: 100,  step: 1,  callback: change } , $('#dispalyVolume') , $('#volumeBar'),video,'volume');
			$('#progress').RangeSlider({ min: 0,   max: 100,  step: 1,  callback: change} , $('#dispalyProgress') , $('#audioProgressBar'),video,'currentTime');
			$('#playStatus').parent().click(function(){
				PlayToPause(video);
			});
			$('#soundStatus').parent().click(function(){
				SoundOnAndOff(video);
			});
			setInterval(function(){
				displayTime(video);
			},1000);
			video.onclick=function(){
				PlayToPause(video);
			}
		</script>
	</body>

</html>