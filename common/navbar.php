<nav class="navbar navbar-inverse navbar-fixed-bottom">
  <div class="container-fluid">
    	<div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#file-navbar-collapse" aria-expanded="false">
            <span class="sr-only">FileManager</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php" title="主目录">
				<span class="glyphicon glyphicon-home navbar-brand-active"></span>
          </a>
        </div>

            <div class="collapse navbar-collapse" id="file-navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="javascript:;"  onclick="show('createFile')" title="新建文件" >
						<span class="glyphicon glyphicon-file"></span>
					</a></li>
					<li><a href="javascript:;"  onclick="show('createFolder')" title="新建文件夹">
						<span class="glyphicon glyphicon-folder-open"></span>
					</a></li>
					<li><a href="javascript:;" onclick="show('uploadFile')"title="上传文件">
						<span class="glyphicon glyphicon-cloud-upload"></span>
					</a></li>
					<?php 
					$back=($path=="../file")?"../file":dirname($path);
					?>
					<li><a href="javascript:;" title="返回上级目录" onclick="goBack('<?php echo $back;?>')">
						<span class="glyphicon glyphicon-arrow-left"></span>
					</a></li>
				</ul>
			<div class="navbar-form navbar-left text-center">
				<form id="createFolder" action="index.php" method="post"  class="form-inline"  style="display:none;">
						<div class="form-group">
							<input type="text" id="dirname" class="form-control" name="dirname" placeholder="请输入文件夹名称" />
							<input type="hidden" name="path"  value="<?php echo $path;?>"/>
							<input type="hidden"  name="act"  value="createFolder"/>
						</div>
						<button class="btn btn-default" type="submit">
							<span class="fa fa-folder-open"></span> 创建文件夹
						</button>
				</form>
				<form id="createFile" action="index.php" method="post" class="form-inline"  style="display:none;">
					<div class="form-group">
						<input type="text" class="form-control" id="filename" name="filename" placeholder="请输入文件名称" />
						<input type="hidden" name="path" value="<?php echo $path;?>"/>
						<input type="hidden"  name="act" value="createFile" />	
					</div>
					<button class="btn btn-default" type="submit">
						<span class="fa fa-file"></span> 创建文件
					</button>
				</form>
				<form id="uploadFile" action="index.php" method="post" class="form-inline"  style="display:none;" enctype="multipart/form-data">
					<div class="form-group">
						<label for="myFile" style="color: #fff;">请选择要上传的文件（最大 20 MB）：</label>
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
				</form>
			</div>

			<?php
if($act=="createFile"){
	//创建文件
	$mes=createFile($path,$filename);
	redirect($mes,$redirect);
}elseif($act=="renameFile"){
	//完成重命名
	$str=<<<EOF
	<form class="form-inline navbar-form navbar-left" action="index.php?act=doRename" method="post"> 
		<input type="text" class="form-control" name="newname" id="newnameFile" placeholder="请填写新文件名"/>
		<input type="hidden" name="path" value="{$path}" />
		<input type='hidden' name='filename' value='{$filename}' />
		<button class="btn btn-default" type="submit">
			<span class="fa fa-repeat"></span> 重命名
		</button>
	</form>
EOF;
echo $str;
}elseif($act=="doRename"){
	//实现重命名的操做
	$newname=$_REQUEST['newname'];
	$mes=renameFile($filename,$newname);
	redirect($mes,$redirect);
}elseif($act=="delFile"){
	// 删除文件
	$mes=delFile($filename);
	redirect($mes,$redirect);
}elseif($act=="createFolder"){
	// 创建文件夹
	$mes=createFolder($path."/".$dirname);
	redirect($mes,$redirect);
}elseif($act=="renameFolder"){
	// 输出重命名文件夹表单
	$str=<<<EOF
	<form class="form-inline navbar-form navbar-left" action="index.php?act=doRenameFolder" method="post"> 
		<input type="text" class="form-control" name="newname" id="newnameFolder" placeholder="请填写新文件夹名称"/>
		<input type="hidden" name="path" value="{$path}" />
		<input type='hidden' name='dirname' value='{$dirname}' />
		<button class="btn btn-default" type="submit">
			<span class="fa fa-repeat"></span> 重命名
		</button>
	</form>
EOF;
echo $str;
}elseif($act=="doRenameFolder"){
	// 执行重命名文件夹操作
	$newname=$_REQUEST['newname'];
	//echo $newname,"-",$dirname,"-",$path;
	$mes=renameFolder($dirname,$path."/".$newname);
	redirect($mes,$redirect);
}elseif($act=="copyFolder"){
		$str=<<<EOF
	<form class="form-inline navbar-form navbar-left" action="index.php?act=doCopyFolder" method="post"> 
		<input type="text" class="form-control" name="dstname" id="CopyFolderdstname" placeholder="填写复制到的目录"/>
		<input type="hidden" name="path" value="{$path}" />
		<input type='hidden' name='dirname' value='{$dirname}' />
		<button class="btn btn-default" type="submit">
			<span class="glyphicon glyphicon-copy"></span> 复制文件夹
		</button>
	</form>
EOF;
echo $str;
}elseif($act=="doCopyFolder"){
	// 执行复制文件夹操作
	$dstname=$_REQUEST['dstname'];

	if($dstname[0]=='/'){
		$dstname = $rootpath."/".$dstname."/".basename($dirname);
	}else{
		$dstname = $path."/".$dstname."/".basename($dirname);
	}
	$mes=copyFolder($dirname,$dstname);
	redirect($mes,$redirect);
}elseif($act=="cutFolder"){
			$str=<<<EOF
	<form class="form-inline navbar-form navbar-left" action="index.php?act=doCutFolder" method="post"> 
		<input type="text" class="form-control" name="dstname" id="CutFolderdstname" placeholder="填写剪切到的目录"/>
		<input type="hidden" name="path" value="{$path}" />
		<input type='hidden' name='dirname' value='{$dirname}' />
		<button class="btn btn-default" type="submit">
			<span class="glyphicon glyphicon-scissors"></span> 剪切文件夹
		</button>
	</form>
EOF;
echo $str;
}elseif($act=="doCutFolder"){
	// 剪切文件夹操作
	$dstname=$_REQUEST['dstname'];
	if($dstname[0]=='/'){
		$dstname = $rootpath."/".$dstname;
	}else{
		$dstname = $path."/".$dstname;
	}
	$mes=cutFolder($dirname,$dstname);
	redirect($mes,$redirect);
}elseif($act=="delFolder"){
	//完成删除文件夹的操作
	$mes=delFolder($dirname);
	redirect($mes,$redirect);
}elseif($act=="copyFile"){
				$str=<<<EOF
	<form class="form-inline navbar-form navbar-left" action="index.php?act=doCopyFile" method="post"> 
		<input type="text" class="form-control" name="dstname" id="CopyFiledstname" placeholder="填写复制到的目录"/>
		<input type="hidden" name="path" value="{$path}" />
		<input type='hidden' name='filename' value='{$filename}' />
		<button class="btn btn-default" type="submit">
			<span class="glyphicon glyphicon-copy"></span> 复制文件
		</button>
	</form>
EOF;
echo $str;
}elseif($act=="doCopyFile"){
	// 执行复制文件操作
	$dstname=$_REQUEST['dstname'];
	if($dstname[0]=='/'){
		$dstname = $rootpath."/".$dstname;
	}else{
		$dstname = $path."/".$dstname;
	}

	$mes=copyFile($filename,$dstname);
	redirect($mes,$redirect);
}elseif($act=="cutFile"){
	// 输出剪切文件夹表单
				$str=<<<EOF
	<form class="form-inline navbar-form navbar-left" action="index.php?act=doCutFile" method="post"> 
		<input type="text" class="form-control" name="dstname" id="CutFiledstname" placeholder="填写剪切到的目录"/>
		<input type="hidden" name="path" value="{$path}" />
		<input type='hidden' name='filename' value='{$filename}' />
		<button class="btn btn-default" type="submit">
			<span class="glyphicon glyphicon-scissors"></span> 剪切文件
		</button>
	</form>
EOF;
echo $str;
}elseif($act=="doCutFile"){
	// 执行剪切文件夹操作
	$dstname=$_REQUEST['dstname'];
	if($dstname[0]=='/'){
		$dstname = $rootpath."/".$dstname;
	}else{
		$dstname = $path."/".$dstname;
	}
	$mes=cutFile($filename,$dstname);
	redirect($mes,$redirect);
}elseif($act=="uploadFile"){
	// 上传文件夹操作
	if($_FILES['myFile']){
		$fileInfo=$_FILES['myFile'];
		$mes=uploadFile($fileInfo,$path);
		redirect($mes,$redirect);
	}
}
			 ?>

              <form class="navbar-form navbar-right">
                <div class="input-group">
                  	<input type="text" class="form-control" placeholder="Search" name="keyword" value="<?php echo $keyword;?>">
                  	<input type="hidden" name="path" value="<?php echo $path;?>">
	                <span class="input-group-btn">
		                <button type="submit" class="btn btn-default">
		                	<span class="glyphicon glyphicon-search"></span>
		                </button>
	                </span>
                </div>
              </form>

            </div><!-- /.navbar-collapse -->

  </div>
</nav>
<script type="text/javascript">
	$('.nav li a').click(function(){
		$('.nav li').removeClass('active');
		$('.glyphicon-home').removeClass('navbar-brand-active');
		$(this).parent().addClass('active');
	});
</script>