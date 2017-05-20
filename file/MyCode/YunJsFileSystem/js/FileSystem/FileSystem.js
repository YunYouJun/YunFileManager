$('#js-link-info').prepend('link file.js success!  --  ');
var MAX_NAME_SIZE=256;  
var MAX_PATH_SIZE=1024;  
var MAX_LINE_SIZE=256;  
var MAX_USERNAME=18;  
var MAX_TEXT_LINE_SIZE=1024;  
var MAX_TIME_SIZE=32;  

var DISK_SIZE=10*1024*1024;

// struct Fold {  
//  char name[MAX_NAME_SIZE];  
//  char path[MAX_PATH_SIZE];  
//  char time[MAX_TIME_SIZE];  
//  Fold *father;//Ö¸Ïò¸¸ÎÄ¼þ¼Ð  
//  Fold *fold_head;//Ö¸Ïò×ÓÄ¿Â¼µÄÄ¿Â¼Á´±íµÄÍ·½Úµã  
//  File *file_head;//Ö¸Ïò×ÓÄ¿Â¼µÄÎÄ¼þÁ´±íµÄÍ·½Úµã  
//  Fold *next;//Ö¸ÏòÏÂÒ»¸ö  
//  Fold();  
// };  
// Fold::Fold()  
// {//¹¹Ôìº¯Êý  
//  father=NULL;  
//  fold_head=NULL;  
//  file_head=NULL;  
//  next=NULL;  
//  strcpy(name,"");  
//  strcpy(path,"");  
//  getTime(curTime);  
//  strcpy(time,curTime);  
// }  
function Folder(name,path,time){
	this.name = name;
	this.path = path;
	this.time = time;
}
function user(name,password){
	this.name = name;
	this.password = password;
	this.next = null;
}

function InitFileSystem(){
	var curTime = new Date();
	$('#curTime').html(curTime);

	// user
}

InitFileSystem();