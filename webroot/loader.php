<?php
namespace libs;

define('APPLIBS',serialize(array('utils','db','request','helper', 'uploader', 'specific')));

require_once(APPROOT.'/config/core.php');

class app {

    function __construct($libs = null) {
        if(empty($libs))
            $libs = unserialize(APPLIBS);

        foreach($libs as $lib) {
            $libFile = '/libs/'.$lib.'.php';
            require_once(APPROOT.$libFile);
        }

        request::init();

    }

    function display() {

        require_once(APPROOT."/src/app_controller.php");

        require_once(APPROOT."/src/app_model.php");

        require_once(APPROOT."/src/".app_action::getInterfacePath()."/".app_action::getSectionPath()."_controller.php");

        require_once(APPROOT."/src/_models/".app_action::getSectionPath()."_model.php");

        $ctrlName = app_action::getSectionPath()."_controller";

        $ctrl = new $ctrlName(app_action::getSectionPath());

        $methodName = str_replace("-","_",app_action::getActionPath());

        $ctrl->call($methodName);

    }

}
