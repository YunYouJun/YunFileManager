<?php 
require_once '../include.php';
$path="../file";
$path=isset($_REQUEST['path'])?$_REQUEST['path']:$path;
$act=isset($_REQUEST['act'])?$_REQUEST['act']:"";

$keyword=isset($_REQUEST['keyword'])?$_REQUEST['keyword']:"";

$filename=isset($_REQUEST['filename'])?$_REQUEST['filename']:"";
$dirname=isset($_REQUEST['dirname'])?$_REQUEST['dirname']:"";
@$info=readDirectory($path);

$mes=isset($_REQUEST['mes'])?$_REQUEST['mes']:"";
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>云游的文件管理</title>
<?php include("../common/headlink.php");?>
<script type="text/javascript">
	function show(dis){
		$(".form-inline").hide();
		document.getElementById(dis).style.display="block";
	}
	function delFile(filename,path){
		if(window.confirm("您确定要删除文件吗?删除之后无法恢复哦!")){
				location.href="index.php?act=delFile&filename="+filename+"&path="+path;
		}
	}
	function delFolder(dirname,path){
		if(window.confirm("您确定要删除嘛?删除之后无法恢复哟!!!")){
			location.href="index.php?act=delFolder&dirname="+dirname+"&path="+path;
		}
	}
	function goBack($back){
		location.href="index.php?path="+$back;
	}
</script>
</head>
<?php
if(!$info){
	$mes="NoneFileOrFolder";
}
showAlertMes($mes);
//print_r($info);
$redirect="index.php?path={$path}";
?>


<body class="container-fluid">
<?php include('../common/header.php');?>
<?php include('../common/navbar.php'); ?>

<form action="index.php" method="post" class="text-center" enctype="multipart/form-data">

	<div id="createFolder"  class="form-inline"  style="display:none;">
			<div class="form-group">
			<label for="dirname">请输入文件夹名称</label>
				<input type="text" id="dirname" class="form-control" name="dirname" />
				<input type="hidden" name="path"  value="<?php echo $path;?>"/>
				<input type="hidden"  name="act"  value="createFolder"/>
			</div>
			<button class="btn btn-default" type="submit">
				<span class="fa fa-folder-open"></span> 创建文件夹
			</button>
	</div>
	<div id="createFile" class="form-inline"  style="display:none;">
		<div class="form-group">
		 	<label for="filename">请输入文件名称</label>
			<input type="text" class="form-control" id="filename" name="filename" />
			<input type="hidden" name="path" value="<?php echo $path;?>"/>
			<input type="hidden"  name="act" value="createFile" />	
		</div>
		<button class="btn btn-default" type="submit">
			<span class="fa fa-file"></span> 创建文件
		</button>
	</div>
	<div id="uploadFile" class="form-inline"  style="display:none;">
		<div class="form-group">
			<label for="myFile">请选择要上传的文件（最大 20 MB）：</label>
			<input type="file" class="btn btn-default" class="file" name="myFile" id="myFile"  onchange="UploadFileName();" style="display: none;" />
			<input type="hidden"  name="act" value="uploadFile"/>
		</div>
		<button class="btn btn-primary" id="chooseFile" type="button">
			<span class="fa  fa-file"></span> 选择文件
		</button>
		<button class="btn btn-default" type="submit">
			<span class="glyphicon glyphicon-cloud-upload"></span> 上传
		</button>
		<script type="text/javascript">
		$('#chooseFile').click(function(){
			$('#myFile').click();
		});
		function UploadFileName(){
			var pathfilename = $('#myFile').val();
			var filename = pathfilename.split("\\");
			$('#chooseFile').text(filename[filename.length-1]);
		}
		</script>
	</div>
<div class="table-responsive">
<table class="table table-hover">
<thead>
	<tr>
		<th>编号</th>
		<th>名称</th>
		<th>类型</th>
		<th>大小</th>
		<th>可读</th>
		<th>可写</th>
		<th>可执行</th>
		<th>创建时间</th>
		<th>修改时间</th>
		<th>访问时间</th>
		<th>操作</th>
	</tr>
</thead>
<?php 
if($info['file']){
	$i=1;
	foreach($info['file'] as $val){
	$p=$path."/".$val;
	$FileNameVal=mb_convert_encoding($val, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
	$p2=$path."/".$FileNameVal;
	$OnlyFileName = explode('.', $FileNameVal);
?>
	<tr align="center"
<?php
if($keyword){
	$key = '/'.$keyword.'/i';
 	if (preg_match($key, $p)) {
     echo 'class="searchFile"';
	}
}
 ?>
	>
		<td><?php echo $i;?></td>
		<td><?php echo $FileNameVal;?></td>
		<td>
		<?php 
		$src=filetype($p)=="file"?"glyphicon glyphicon-file":"glyphicon glyphicon-folder-close";
		$filetype=filetype($p)=="file"?"文件":"文件夹";
		if(isImage($val)){
			$src = "glyphicon glyphicon-picture";
			$filetype = "图片";
		}elseif(isTxt($val)){
			$src = "fa fa-file-text";
			$filetype = "文本";
		}elseif(isCode($val)){
			$src = "fa fa-file-code-o";
			$filetype = "代码文件";
		}elseif(isMusic($val)){
			$src = "fa fa-music";
			$filetype = "音乐";
		}elseif(isVideo($val)){
			$src = "fa fa-file-video-o";
			$filetype = "视频";
		}
		?>
		<span class="<?php echo $src;?>" title="<?php echo $filetype; ?>"></span>
		</td>
		<td><?php echo transByte(filesize($p));?></td>
		<td><?php $src=is_readable($p)?"glyphicon glyphicon-ok text-success":"glyphicon glyphicon-remove text-danger";?><span class="<?php echo $src; ?>"></span></td>
		<td><?php $src=is_writable($p)?"glyphicon glyphicon-ok text-success":"glyphicon glyphicon-remove text-danger";?><span class="<?php echo $src; ?>"></span></td>
		<td><?php $src=is_executable($p)?"glyphicon glyphicon-ok text-success":"glyphicon glyphicon-remove text-danger";?><span class="<?php echo $src; ?>"></span></td>
		<td><?php echo date("Y-m-d H:i:s",filectime($p));?></td>
		<td><?php echo date("Y-m-d H:i:s",filemtime($p));?></td>
		<td><?php echo date("Y-m-d H:i:s",fileatime($p));?></td>
		<td>
		<div class="btn-group btn-group-xs" role="toolbar" aria-label="...">
		<?php if(isImage($val)){ ?>	
				<a class="btn btn-default" href="javascript:;" data-toggle="modal" data-target="#myModal<?php echo $OnlyFileName[0];?>">
					<span class="glyphicon glyphicon-eye-open" title="查看"></span>
				</a>
				<div class="modal fade" id="myModal<?php echo $OnlyFileName[0];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" style="width:90%;max-height:100%;padding-top: 20px;">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true" style="font-size: 300%;">&times;</span></button>
							<div class="text-center">
								<img style="max-width: 90%;" src="<?php echo $p2?>" alt=""/>
							</div>
						</div>
				</div>
		<?php 
			}elseif(isMusic($val)){?>
			<a class="btn btn-default" href="Listening.php?path=<?php echo $path;?>&filename=<?php echo $p2;?>&fileTitle=<?php echo $FileNameVal; ?>" target="_blank">
				<span class="fa fa-headphones" title="聆听"></span>
			</a>
		<?php }elseif(isVideo($val)){ ?>
			<a class="btn btn-default" href="Watching.php?path=<?php echo $path;?>&filename=<?php echo $p2;?>&fileTitle=<?php echo $FileNameVal; ?>" target="_blank">
				<span class="fa fa-video-camera" title="观看"></span>
			</a>
			<?php
		}else{
		?>
			<a class="btn btn-default" href="doFileAction.php?act=showContent&path=<?php echo $path;?>&filename=<?php echo $p2;?>&fileTitle=<?php echo $FileNameVal; ?>" target="_blank"><span class="glyphicon glyphicon-eye-open" title="查看"></span></a>
			<?php }?>
			<?php if(!isMusic($val)&&!isVideo($val)): ?>
			<a class="btn btn-default" href="doFileAction.php?act=editContent&path=<?php echo $path;?>&filename=<?php echo $p;?>" target="_blank"><span class="glyphicon glyphicon-edit" title="修改"></span></a>
			<?php endif; ?>
			<a class="btn btn-default" href="index.php?act=renameFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><span class="
	glyphicon glyphicon-pencil" title="重命名"></span></a>
			<a class="btn btn-default" href="index.php?act=copyFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><span class="glyphicon glyphicon-copy" title="复制"></span></a>
			<a class="btn btn-default" href="index.php?act=cutFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><span class="glyphicon glyphicon-scissors" title="剪切"></span></a>
			<a class="btn btn-default" href="javascript:;"  onclick="delFile('<?php echo $p;?>','<?php echo $path;?>')"><span class="glyphicon glyphicon-remove" title="删除"></span></a>
			<a class="btn btn-default" href="doFileAction.php?act=downFile&path=<?php echo $path;?>&filename=<?php echo $p;?>">
				<span class="glyphicon glyphicon-download-alt" title="下载"></span>
			</a>
		</div>
		</td>		
	</tr>
<?php 
$i++;
	}
}



?>

<!-- 读取目录的操作-->
<?php 
if(@$info['dir']){
	$i=$i==null?1:$i;
	foreach($info['dir'] as $val){
		$p=$path."/".$val;
		$p=mb_convert_encoding($p, 'gbk', 'UTF-8,GBK,GB2312,BIG5');
		$FolderNameVal=mb_convert_encoding($val, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
		$p2=$path."/".$FolderNameVal;
?>
	<tr align="center" class="dirback
<?php
if($keyword){
	$key = '/'.$keyword.'/i';
 	if (preg_match($key, $p)) {
     echo 'searchFile';
	}
}
 ?>
 "
	>
		<td><?php echo $i;?></td>
		<td><?php 
		echo $FolderNameVal;?></td>
		<td><?php $src=filetype($p)=="file"?"glyphicon glyphicon-file":"glyphicon glyphicon-folder-close";
		$filetype=filetype($p)=="file"?"文件":"文件夹";?>
		<span class="<?php echo $src;?>" title="<?php echo $filetype; ?>"></span></td>
		<td><?php  $sum=0; echo transByte(dirSize($p));?></td>
		<td><?php $src=is_readable($p)?"glyphicon glyphicon-ok text-success":"glyphicon glyphicon-remove text-danger";?><span class="<?php echo $src; ?>"></span></td>
		<td><?php $src=is_writable($p)?"glyphicon glyphicon-ok text-success":"glyphicon glyphicon-remove text-danger";?><span class="<?php echo $src; ?>"></span></td>
		<td><?php $src=is_executable($p)?"glyphicon glyphicon-ok text-success":"glyphicon glyphicon-remove text-danger";?><span class="<?php echo $src; ?>"></span></td>
		<td><?php echo date("Y-m-d H:i:s",filectime($p));?></td>
		<td><?php echo date("Y-m-d H:i:s",filemtime($p));?></td>
		<td><?php echo date("Y-m-d H:i:s",fileatime($p));?></td>
		<td>
		<div class="btn-group btn-group-xs" role="toolbar" aria-label="...">
		  	<a class="btn btn-default" href="index.php?path=<?php echo $p2;?>" ><span class="glyphicon glyphicon-eye-open" title="查看"></span></a>
		  	<a class="btn btn-default" href="index.php?act=renameFolder&path=<?php echo $path;?>&dirname=<?php echo $p;?>"><span class="glyphicon glyphicon-pencil" title="重命名"></span></a>
		  	<a class="btn btn-default" href="index.php?act=copyFolder&path=<?php echo $path;?>&dirname=<?php echo $p;?>"><span class="glyphicon glyphicon-copy" title="复制"></span></a>
		  	<a class="btn btn-default" href="index.php?act=cutFolder&path=<?php echo $path;?>&dirname=<?php echo $p;?>"><span class="glyphicon glyphicon-scissors" title="剪切"></span></a>
		  	<a class="btn btn-default" href="#"  onclick="delFolder('<?php echo $p;?>','<?php echo $path;?>')"><span class="glyphicon glyphicon-remove" title="删除"></span></a>
		</div>
		</td>		
	</tr>
<?php 
$i++;
	}
}

?>
	
</table>
<div class="table-responsive">
</form>

<?php include('../common/footer.php');?>

</body>
</html>