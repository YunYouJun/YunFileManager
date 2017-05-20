<?php 
//打开指定目录
/**
 * 遍历目录函数，只读取目录中的最外层的内容
 * @param string $path
 * @return array
 */
function readDirectory($path) {
	//windows要用gbk中文   好麻烦……
	$path = mb_convert_encoding( $path, 'GBK', 'UTF-8,GBK,GB2312,BIG5' );
	$handle = opendir ( $path );
	while ( ($item = readdir ( $handle )) !== false ) {
		//.和..这2个特殊目录
		if ($item != "." && $item != "..") {
			//这里也是中文转码 还有点小毛病没解决
			if (is_file ( $path . "/" . $item )) {
				$arr ['file'] [] = $item;
			}
			if (is_dir ( $path . "/" . $item )) {
				$arr ['dir'] [] = $item;
			}
		}
	}
	closedir ( $handle );
	return $arr;
}
//$path="file";
//print_r(readDirectory($path));

/**
 * 得到文件夹大小
 * @param string $path
 * @return int 
 */
function dirSize($path){
	$sum=0;
	// global $sum;
	$handle=opendir($path);
	while(($item=readdir($handle))!==false){
		if($item!="."&&$item!=".."){
			if(is_file($path."/".$item)){
				$sum+=filesize($path."/".$item);
			}
			if(is_dir($path."/".$item)){
				$func=__FUNCTION__;
				$sum+=$func($path."/".$item);
				//这里是递归
			}
		}
		
	}
	closedir($handle);
	return $sum;
}
//$path="file";
//echo dirSize($path);

function createFolder($dirname){
	//检测文件夹名称的合法性
	if(checkFilename(basename($dirname))){
		//当前目录下是否存在同名文件夹名称
		if(!file_exists($dirname)){
			if(mkdir($dirname,0777,true)){
				$mes="CreateFolderSuccess";
			}else{
				$mes="CreateFolderFail";
			}
		}else{
			$mes="SameNameFolder";
		}
	}else{
		$mes="IllegalNameFolder";
	}
	return $mes;
}
/**
 * 重命名文件夹
 * @param string $oldname
 * @param string $newname
 * @return string
 */
function renameFolder($oldname,$newname){
	//检测文件夹名称的合法性
	if(checkFilename(basename($newname))){
		//检测当前目录下是否存在同名文件夹名称
		if(!file_exists($newname)){
			if(rename($oldname,$newname)){
				$mes="RenameFolderSuccess";
			}else{
				$mes="RenameFolderFail";
			}
		}else{
			$mes="SameNameFolder";
		}
	}else{
		$mes="IllegalNameFolder";
	}
	return $mes;
}

function copyFolder($src,$dst){
	//echo $src,"---",$dst."----";
	if(!file_exists($dst)){
		mkdir($dst,0777,true);
	}
	$handle=opendir($src);
	while(($item=readdir($handle))!==false){
		if($item!="."&&$item!=".."){
			if(is_file($src."/".$item)){
				copy($src."/".$item,$dst."/".$item);
			}
			if(is_dir($src."/".$item)){
				$func=__FUNCTION__;
				$func($src."/".$item,$dst."/".$item);
			}
		}
	}
	closedir($handle);
	return "CopyFolderSuccess";
}

/**
 * 剪切文件夹
 * @param string $src
 * @param string $dst
 * @return string
 */
function cutFolder($src,$dst){
	//echo $src,"--",$dst;
	if(file_exists($dst)){
		if(is_dir($dst)){
			if(!file_exists($dst."/".basename($src))){
				if(rename($src,$dst."/".basename($src))){
					$mes="CutFolderSuccess";
				}else{
					$mes="CutFolderFail";
				}
			}else{
				$mes="SameNameFolder";
			}
		}else{
			$mes="NotAFolder";
		}
	}else{
		$mes="DstFolderNotExist";
	}
	return $mes;
}

/**
 * 删除文件夹
 * @param string $path
 * @return string
 */
function delFolder($path){
	$handle=opendir($path);
	while(($item=readdir($handle))!==false){
		if($item!="."&&$item!=".."){
			if(is_file($path."/".$item)){
				unlink($path."/".$item);
			}
			if(is_dir($path."/".$item)){
				$func=__FUNCTION__;
				$func($path."/".$item);
			}
		}
	}
	closedir($handle);
	rmdir($path);
	return "DelFolderSuccess";
}











