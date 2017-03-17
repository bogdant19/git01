<?php
$interface = \libs\app_action::getInterfacePath();
$action = \libs\app_action::getActionPath();
$action = str_replace("-","_",$action);
$section = \libs\app_action::getSectionPath();
require_once(APPROOT."/src/$interface/views/$section/$action.php");
?>