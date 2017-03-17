<?php

session_start();

define('APPFOLDER','/');

define('APPROOT',$_SERVER["DOCUMENT_ROOT"].substr(APPFOLDER,1));

define('APPHOST','http://'.$_SERVER["HTTP_HOST"]);

define('APPBASE',APPHOST.APPFOLDER);

define('APPURL',APPBASE.substr($_SERVER["REQUEST_URI"],1));

require_once(APPROOT.'/webroot/loader.php');

$app = new \libs\app;

$app->display();

