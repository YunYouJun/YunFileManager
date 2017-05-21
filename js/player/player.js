$.fn.RangeSlider = function(cfg,progressbar,external,media,changeAttr){
    this.sliderCfg = {
        min: cfg && !isNaN(parseFloat(cfg.min)) ? Number(cfg.min) : null, 
        max: cfg && !isNaN(parseFloat(cfg.max)) ? Number(cfg.max) : null,
        step: cfg && Number(cfg.step) ? cfg.step : 1,
        callback: cfg && cfg.callback ? cfg.callback : null
    };

    var $input = $(this);
    var min = this.sliderCfg.min;
    var max = this.sliderCfg.max;
    var step = this.sliderCfg.step;
    var callback = this.sliderCfg.callback;

    $input.attr('min', min)
        .attr('max', max)
        .attr('step', step);

    $input.bind("input", function(e){
        $input.attr('value', this.value);
        // $input.css( 'background', 'linear-gradient(to right, #059CFA, white ' + this.value + '%, white)' );
        if(changeAttr=='currentTime'){
        	curTime =parseInt(media.duration * this.value/100);
        	eval('media.'+changeAttr+'= this.value/100');
        	media.currentTime = curTime;
        }else{
        	eval('media.'+changeAttr+'= this.value/100');
        	external.attr('title',this.value);
        }
        progressbar.attr('value', this.value);
        progressbar.css('width',this.value+'%');
        if ($.isFunction(callback)) {
            callback(this);
        }
    });
};

// $('input').css( 'background', 'linear-gradient(to right, #059CFA, white  60%, white)' );

function PlayToPause(media){
	playStatus = $('#playStatus');
	if(playStatus.hasClass('glyphicon-play')){
		playStatus.addClass('glyphicon-pause');
		playStatus.removeClass('glyphicon-play');
		playStatus.attr('title','暂停');
		media.play();
	}else{
		playStatus.addClass('glyphicon glyphicon-play');
		playStatus.removeClass('glyphicon-pause');
		playStatus.attr('title','播放');
		media.pause();
	}
}

function SoundOnAndOff(media){
	soundStatus = $('#soundStatus');
	if(soundStatus.hasClass('glyphicon-volume-up')){
		soundStatus.addClass('glyphicon-volume-off');
		soundStatus.removeClass('glyphicon-volume-up');
		soundStatus.attr('title','开启');
		media.muted=true;
	}else{
		soundStatus.addClass('glyphicon-volume-up');
		soundStatus.removeClass('glyphicon-volume-off');
		soundStatus.attr('title','静音');
		media.muted=false;
	}
}

function random(m,n){//m到n之间随机数
	return Math.round(Math.random()*(n-m)+m);
}
//生成点
function getDots(){
	Dots = [];
	for(i = 0;i<size;i++){
		var x = random(0,width);
		var y = random(0,height);
		var color = "rgba("+random(0,255)+","+random(0,255)+","+random(0,255)+","+random(5,250)+")";
		// var color = "rgba(0,0,0,100)";
		var dx = random(1,4);
		Dots.push({
			x:x,
			y:y,
			dx:dx,
			dx2:dx,
			color:color,
			cap : 0,
			dotMode:'random'
		});
	}
}

function resizeCanvas(){
	width = box.clientWidth;
	height = $(window).height()-$('footer').height()-$('#PlayerControl').height()-$('.page-header').height()-200;
	canvas.width = width;
	canvas.height = height;
	line = ctx.createLinearGradient(0,0,0,height);
	// line.addColorStop(0,"red");
	// line.addColorStop(0.5,"yellow");
	// line.addColorStop(1,"green");
	line.addColorStop(0,"rgba(102,204,255,0.9)");
	line.addColorStop(1,"rgba(0,0,0,0.9)");
	getDots();
}

//画柱状或者点状
function draw(arr){
	ctx.clearRect(0,0,width,height);
	var w = width/size;
	var cw = w*0.6;
	var capH = cw>10?10:cw;
	var gap = 20;
	ctx.fillStyle = line;
	for(var i=0; i<size;i++){
		var o = Dots[i];
		if(draw.type == "column"){
			var h = arr[i] /256 * height;
			ctx.fillRect( w *i,height-h , cw , h);
			ctx.save();
			ctx.fillStyle = "rgba(0,0,0,0.8)";
			ctx.fillRect( w *i,height-(o.cap+capH)  , cw , capH);
			ctx.restore();
			o.cap--;
			if(o.cap<0){
				o.cap = 0;
			}
			if(h>0 && o.cap<h+gap){
				o.cap = h+gap>height-capH?height-capH:h+gap;
			}
		}else if(draw.type == "dot"){
			ctx.beginPath();
			var r = 7 + arr[i] /256 * (height>width?width:height)/10;
			ctx.arc(o.x,o.y,r,0,Math.PI*2,true);
			var gra = ctx.createRadialGradient(o.x,o.y,0,o.x,o.y,r);
			gra.addColorStop(0,o.color);
			gra.addColorStop(1,"rgba(255,255,255,0)");
			// gra.addColorStop(1,"rgba(0,0,0,0)");
			ctx.fillStyle = gra;
			ctx.fill();
			o.x = o.x-r>width?-r:o.x+o.dx;
			// ctx.strokeStyle = "#000";
			// ctx.stroke();
		}
	}
}

//显示播放时间
function displayTime(media){
	$('#curTime').html(SecondToClock(media.currentTime));
	$('#totalTime').html(SecondToClock(media.duration));
	$('#dispalyProgress').css('width',media.currentTime/media.duration*100+'%');
	if(media.ended){
		// PlayToPause(audio);
	}
}

//时间转化为分钟
function SecondToClock(seconds){
	seconds = parseInt(seconds);
	var hour = 0;
	var minute = 0;
	var second = 0;
	var final;
	if(seconds>=3600){
		hour = parseInt(seconds/3600);
		seconds = seconds%3600;
	}
	if(seconds>=60){
		minute = parseInt(seconds/60);
		seconds = seconds%60;
	}
	second = seconds;
	if(hour<10){
		final = '0';
	}
	final = final+hour + ':';
	if(minute<10){
		final = final + '0';
	}
	final = final + minute + ':';
	if(second<10){
		final = final + '0';
	}
	final = final + second;
	return final;
}

//显示音乐频谱图
function visualizer(){
	var arr = new Uint8Array(analyser.frequencyBinCount);
	requestAnimationFrame = window.requestAnimationFrame;
	function v(){
		analyser.getByteFrequencyData(arr);
		draw(arr);
		displayTime(audio);
		
		requestAnimationFrame(v);
	}
	requestAnimationFrame(v);
}
