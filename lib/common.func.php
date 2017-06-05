<?php 
/**
 * 提示操作信息的，并且跳转
 * @param string $mes
 * @param string $url
 */
function alertMes($mes,$url){
	echo "<script type='text/javascript'>alert('{$mes}');location.href='{$url}';</script>";
}

//重定向链接并且输出漂亮的警告框
function redirect($mes,$url){
	$url = $url."&mes=".$mes;
	echo "<script type='text/javascript'>location.href='{$url}';</script>";
}

/**
 * 截取文件扩展名
 * @param string $filename
 * @return string
 */
function getExt($filename){
	return strtolower(pathinfo($filename,PATHINFO_EXTENSION));
}

/**
 * 产生唯一名称
 * @param int $length
 * @return string
 */
function getUniqidName($length=10){
	return substr(md5(uniqid(microtime(true),true)),0,$length);
}
//几种不同样式的警告框（成功，警告，失败，信息）
//几种样式的警告框 =。= 贼好看
function showSuccessMsg($mes){
	echo '<div class="alert alert-success alert-dismissible text-center" role="alert">
  		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong></strong> '.$mes.' </div>';
}
function showWarningMsg($mes){
	echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">
  		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong>警告！</strong> '.$mes.' </div>';
}
function showDangerMsg($mes){
	echo '<div class="alert alert-danger alert-dismissible text-center" role="alert">
  		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong>错误！</strong> '.$mes.' </div>';
}
function showInfoMsg($mes){
	echo '<div class="alert alert-info alert-dismissible text-center" role="alert">
  		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong>提示：</strong> '.$mes.' </div>';
}

//根据返回的mes信息，显示不同警告框。
function showAlertMes($mes){
	switch ($mes) {
		case 'CreateFileSuccess':
			showSuccessMsg('文件创建成功！');
			break;
		case 'CreateFileFail':
			showDangerMsg('文件创建失败！');
			break;
		case 'CreateFolderSuccess':
			showSuccessMsg('文件夹创建成功！');
			break;
		case 'CreateFolderFail':
			showDangerMsg('文件夹创建失败！');
			break;
		case 'FileExists':
			showWarningMsg('文件已存在，请先重命名！');
			break;
		case 'IllegalName':
			showWarningMsg('非法文件名！');
			break;
		case 'IllegalNameFolder':
			showWarningMsg('非法文件夹名称！');
			break;
		case 'SameNameFile':
			showWarningMsg('存在同名文件！');
			break;
		case 'SameNameFolder':
			showWarningMsg('存在同名文件夹！');
			break;
		case 'RenameFileSuccess':
			showSuccessMsg('文件重命名成功！');
			break;
		case 'RenameFileFail':
			showDangerMsg('文件重命名失败！');
			break;
		case 'RenameFolderSuccess':
			showSuccessMsg('文件夹重命名成功！');
			break;
		case 'RenameFolderFail':
			showDangerMsg('文件夹重命名失败！');
			break;
		case 'DelFolderSuccess':
			showSuccessMsg('删除文件夹成功！');
			break;
		case 'FileDeleteSuccess':
			showSuccessMsg('删除文件成功！');
			break;
		case 'FileDeleteFail':
			showDangerMsg('删除文件失败！');
			break;
		case 'NoneFileOrFolder':
			showWarningMsg('没有文件或目录！');
			break;
		case 'CopyFileSuccess':
			showSuccessMsg('文件复制成功！');
			break;
		case 'CopyFileFail':
			showDangerMsg('文件复制失败！');
			break;
		case 'CutFileSuccess':
			showSuccessMsg('文件剪切成功！');
			break;
		case 'CutFolderSuccess':
			showSuccessMsg('文件夹剪切成功！');
			break;
		case 'CutFileFail':
			showDangerMsg('文件剪切失败！');
			break;
		case 'DstFolderNotExist':
			showWarningMsg('目标文件夹不存在！');
			break;
		case 'NotAFolder':
			showWarningMsg('不是一个文件夹！');
			break;
		case "error:1":
			$mes="超过了配置文件的大小!";
			showWarningMsg($mes);
			break;
		case "error:2":
			$mes="超过了表单允许接收数据的大小!";
			showWarningMsg($mes);
			break;
		case "error:3":
			$mes="文件部分被上传!";
			showWarningMsg($mes);
			break;
		case "error:4":
			$mes="没有文件被上传!";
			showWarningMsg($mes);
			break;
		case "NotUploadByHttpPost":
			$mes="文件不是通过HTTP POST方式上传上来的!";
			showWarningMsg($mes);
			break;
		case "IllegalFileType":
			showWarningMsg("非法文件类型！");
			break;
		case "FileOversize":
			showWarningMsg("文件过大！");
			break;
		case 'UploadFileSuccess':
			showSuccessMsg("文件上传成功！");
			break;
		case 'UploadFileFail':
			showDangerMsg("文件上传失败！");
			break;
		default:
			if($mes){
				showInfoMsg($mes);			
			}
			break;
	}
}

// 显示当前路径信息，并提供跳转链接。
function showPath($path){
	$path = str_replace("../file","",$path);
	$folderPath = "../file";
	$folders = explode('/', $path);
	$foldnum = count($folders);
	echo '<ol class="breadcrumb">';
	echo 'Path > ';
	if($foldnum>1){
		for($i=0;$i<$foldnum-1;$i++){
			if($folders[$i]){
				$folderPath = $folderPath.'/'.$folders[$i];
				echo '<li><a href="index.php?path='.$folderPath.'">'.$folders[$i].'</a></li>';
			}else{
				echo '<li><a href="index.php?path=../file">..</a></li>';
			}
		}
	}
	echo '<li class="active">'.$folders[$foldnum-1].'</li>';
	echo '</ol>';
}