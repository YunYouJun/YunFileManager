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
		<title>音乐：<?php echo $filename; ?></title>
		<?php include("../common/headlink.php");?>
		<link rel="stylesheet" type="text/css" href="../css/player/player.css">
		<script type="text/javascript" src="../js/player/player.js"></script>
	</head>
	<body>
	<div class="container">
		<div class="page-header text-center">
			<span class="fa fa-music medium-icon"></span>
			<span class="medium-icon">&nbsp;—&nbsp;</span>
			<span style="font-size: 222%;"><?php echo $fileTitle;?></span>
		</div>

		<audio id="mymusic" src="<?php echo $filename; ?>" loop="loop">
			您的浏览器可能不支持音乐播放，请使用最新chrome浏览器。
		</audio>
	<div id="musixBox">
		<div class="btn-group btn-group-justified" role="group" id="EffectType" style="margin-bottom:5px;">
		  <div class="btn-group" role="group">
		    <button type="button" class="btn btn-default EffectType active" data-type="column">
		    	<span class="fa fa-bar-chart-o"></span>
		    	<span>柱状</span>
		    </button>
		  </div>
		  <div class="btn-group" role="group">
		    <button type="button" class="btn btn-default EffectType" data-type="dot">
		    	<span class="fa fa-dot-circle-o"></span>
		    	<span>点状</span>
		    </button>
		  </div>
		</div>
		<canvas id="musicEffect">
			您的浏览器可能不支持显示该效果，请更换最新的Chrome浏览器。
		</canvas>
	</div>
	<!-- 进度条 -->
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
	</div>
	</body>
<script type="text/javascript">
var box = $('#musixBox')[0];
var height,width;
var canvas = $('#musicEffect')[0];
var ctx = canvas.getContext("2d");
var size = 64;
var Dots = [];
var line;//线性渐变样式
resizeCanvas();

var audio = $('#mymusic')[0];
// audio.oncanplaythrough = function(){
audio.volume = 0.6; 
var actx = new window.AudioContext();
var analyser = actx.createAnalyser();

analyser.fftSize = size * 2;
var audioSrc = actx.createMediaElementSource(audio);
audioSrc.connect(analyser);
analyser.connect(actx.destination);
visualizer();
// };

var change = function($input){  
// console.log(media.volume);  
};
$('#volume').RangeSlider({ min: 0,   max: 100,  step: 1,  callback: change } , $('#dispalyVolume') , $('#volumeBar'),audio,'volume');
$('#progress').RangeSlider({ min: 0,   max: 100,  step: 1,  callback: change} , $('#dispalyProgress') , $('#audioProgressBar'),audio,'currentTime');
$('#playStatus').parent().click(function(){
	PlayToPause(audio);
});
$('#soundStatus').parent().click(function(){
	SoundOnAndOff(audio);
});
$(window).resize(resizeCanvas);

draw.type = "column";
var types = $('.EffectType');
types.click(function(){
	types.removeClass('active');
	$(this).addClass('active');
	draw.type = $(this).attr('data-type');
});

canvas.onclick=function(){
	if(draw.type=='dot'){
		for(var i=0;i<size;i++){
			Dots.dotMode == 'random'? Dots[i].dx = 0:Dots[i].dx=Dots[i].dx2;
		}
		Dots.dotMode = Dots.dotMode=="static"?"random":"static";

	}
};

</script>
</html>