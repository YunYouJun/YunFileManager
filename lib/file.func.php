<?php 

//Bytes/Kb/MB/GB/TB/EB
/**
 * 转换字节大小
 * @param number $size
 * @return number
 */
function transByte($size) {
	$arr = array ("B", "KB", "MB", "GB", "TB", "EB" );
	$i = 0;
	while ( $size >= 1024 ) {
		$size /= 1024;
		$i ++;
	}
	return round ( $size, 2 ) ." [". $arr [$i]."]";
}

/**
 * 创建文件
 * @param string $filename//传入路径 和 文件名
 * @return string
 */
function createFile($path,$filename) {
	//file/1.txt
	//验证文件名的合法性,是否包含/,*,<>,?,|
	// basename ( $filename ) 好像有点问题 反斜杠会清除掉 待定
	$pattern = "/[\/,\*,<>,\?\|]/";
	if (! preg_match ( $pattern, $filename )) {
		//检测当前目录下是否存在同名文件
		$filename = $path."/".$filename;
		if (! file_exists ( $filename )) {
			//通过touch($filename)来创建
			if (touch ( $filename )) {
				return 'CreateFileSuccess';
			}else{
				return 'CreateFileFail';
			} 
		}else{
			return 'FileExists';
		} 
	}else {
		return 'IllegalName';
	}
}

/**
 * 重命名文件
 * @param string $oldname
 * @param string $newname
 * @return string
 */
function renameFile($oldname,$newname){
//	echo $oldname,$newname;
//验证文件名是否合法
	if(checkFilename($newname)){
		//检测当前目录下是否存在同名文件
		$path=dirname($oldname);
		if(!file_exists($path."/".$newname)){
			//进行重命名
			if(rename($oldname,$path."/".$newname)){
				return "RenameFileSuccess";
			}else{
				return "RenameFileFail";
			}
		}else{
			return "SameNameFile";
		}
	}else{
		return "IllegalName";
	}
	
}

/**
 *检测文件名是否合法
 * @param string $filename
 * @return boolean
 */
function checkFilename($filename){
	$pattern = "/[\/,\*,<>,\?\|]/";
	if (preg_match ( $pattern,  $filename )) {
		return false;
	}else{
		return true;
	}
}

/**
 * 删除文件
 * @param string $filename
 * @return string
 */
function delFile($filename){
	if(unlink($filename)){
		$mes="FileDeleteSuccess";
	}else{
		$mes="FileDeleteFail";
	}
	return $mes;
}

/**
 * 复制文件
 * @param string $filename
 * @param string $dstname
 * @return string
 */
function copyFile($filename,$dstname){
	if(file_exists($dstname)){
		if(!file_exists($dstname."/".basename($filename))){
			if(copy($filename,$dstname."/".basename($filename))){
				$mes="CopyFileSuccess";
			}else{
				$mes="CopyFileFail";
			}
		}else{
			$mes="SameNameFile";
		}
	}else{
		$mes="DstFolderNotExist";
	}
	return $mes;
}

function cutFile($filename,$dstname){
	if(file_exists($dstname)){
		if(!file_exists($dstname."/".basename($filename))){
			if(rename($filename,$dstname."/".basename($filename))){
				$mes="CutFileSuccess";
			}else{
				$mes="CutFileFail";
			}
		}else{
			$mes="SameNameFile";
		}
	}else{
		$mes="DstFolderNotExist";
	}
	return $mes;
}

/**
 * 上传文件
 * @param array $fileInfo
 * @param string $path
 * @param array $allowExt
 * @param int $maxSize
 * @return string
 */
function uploadFile($fileInfo,$path,$maxSize=20971520){
	//判断错误号
	//最大20Mb
	if (! file_exists ($fileInfo['tmp_name'])) {
	if($fileInfo['error']==UPLOAD_ERR_OK){
		//文件是否是通过HTTP POST方式上传上来的
		if(is_uploaded_file($fileInfo['tmp_name'])){
			//上传文件的文件名，只允许上传jpeg|jpg、png、gif、txt的文件
			//$allowExt=array("gif","jpeg","jpg","png","txt");
			//取消限制
			// $ext=getExt($fileInfo['name']);
			// $uniqid=getUniqidName();
			// $destination=$path."/".pathinfo($fileInfo['name'],PATHINFO_FILENAME)."_".$uniqid.".".$ext;
			$destination=$path."/".$fileInfo['name'];
			//又是windows上中文转码
			$destination=mb_convert_encoding($destination, 'GBK','UTF-8');
			// if(in_array($ext,$allowExt)){
				if($fileInfo['size']<=$maxSize){
					if(move_uploaded_file($fileInfo['tmp_name'], $destination)){
						$mes="UploadFileSuccess";
					}else{
						$mes="UploadFileFail";
					}
				}else{
					$mes="FileOversize";
				}
			// }else{
			// 	$mes="IllegalFileType";
			// }
		}else{
			$mes="NotUploadByHttpPost";
		}
	}else{
		$mes="error:".$fileInfo['error'];
		}
	}else{
		$mes = 'FileExists';
	}
	return $mes;
}


//展示文件内容
function showContent($filename){
	$filename = mb_convert_encoding( $filename, 'GBK', 'UTF-8,GBK,GB2312,BIG5' );
	$content=file_get_contents($filename);
	//echo "<textarea readonly='readonly' rows='10'>{$content}</textarea>";
	//高亮显示字符串中的PHP代码
	$content=mb_convert_encoding( $content, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5' );
	if(strlen($content)){
		$newContent=highlight_string($content,true);
		//高亮显示文件中的PHP代码
		//highlight_file($filename);
		$str=<<<EOF
			<pre>
				{$newContent}
			</pre>

EOF;
		echo $str;
	}else{
		showWarningMsg('当前文件没有内容，请先编辑！');
	}
}

//编辑区域显示内容
function editContent($path,$filename){
	//修改文件内容
	$content=file_get_contents($filename);
	$str=<<<EOF
	<form action='doFileAction.php?act=doEdit' method='post'> 
		<div class="form-group">
			<textarea class="form-control" name='content' rows='22' style="font-family:Microsoft YaHei;">{$content}</textarea>
			<input type='hidden' name='filename' value='{$filename}'/>
			<input type="hidden" name="path" value="{$path}" />
		</div>
		<button type="submit" class="btn btn-default btn-block btn-lg">
		<span class="glyphicon glyphicon-floppy-disk"></span> &nbsp; &nbsp;保存
		</button>
	</form>
EOF;
	echo $str;
}

//修改文件内容的操作
function doEdit($path,$filename){
	$content=$_REQUEST['content'];
	//echo $content;
	if(file_put_contents($filename,$content)){
		showSuccessMsg('文件修改成功！');
	}else{
		showDangerMsg('文件修改失败！');
	}
	editContent($path,$filename);
}

/**
 * 下载文件操作
 * @param string $filename
 */
function downFile($filename){
	header("content-disposition:attachment;filename=".basename($filename));
	header("content-length:".filesize($filename));
	readfile($filename);
}

//判断是否为图片
function isImage($val){
	$ext=strtolower(end(explode(".",$val)));
	$imageExt=array("gif","jpg","jpeg","png");
	if(in_array($ext,$imageExt)){ 
		return true;
	}else{
		return false;
	}
}
//是否为文本
function isTxt($val){
	$ext=strtolower(end(explode(".",$val)));
	$txtExt=array("txt");
	if(in_array($ext,$txtExt)){ 
		return true;
	}else{
		return false;
	}
}
//是否为代码
function isCode($val){
	$ext=strtolower(end(explode(".",$val)));
	$codeExt=array("php","css","html","js","c","cpp","cs","java","py","h","jsp","asp");
	if(in_array($ext,$codeExt)){ 
		return true;
	}else{
		return false;
	}
}
//是否为音乐
function isMusic($val){
	$ext=strtolower(end(explode(".",$val)));
	$musicExt=array("mp3","wav","ogg");
	if(in_array($ext,$musicExt)){ 
		return true;
	}else{
		return false;
	}
}
//是否为视频
function isVideo($val){
	$ext=strtolower(end(explode(".",$val)));
	$videoExt=array("avi","mp4","mov","wmv");
	if(in_array($ext,$videoExt)){ 
		return true;
	}else{
		return false;
	}
}