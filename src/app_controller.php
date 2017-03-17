<?php
class app_controller extends \libs\app {

    public $errors;

    public $data;

    public $vars;

    public $db;

    public $params;

    function __construct($name) {
        $modelName = $name;
        $modelClass = $modelName."_model";
        $this->$modelName = new $modelClass;
        $this->data = \libs\request::$data;
        $this->db = \libs\db::get();
        $this->params = \libs\app_action::getParams();
    }

    function call($methodName) {
        $this->$methodName();
        $this->set("p",$this->data["_p"]);
        $this->set("seo",\libs\app_action::getData());
        if(!empty($this->vars))
            extract($this->vars);
        require_once(APPROOT."/src/_layouts/".\libs\app_action::getActionLayout().".php");
    }

    function element($elementName) {
        $this->set("p",$this->data["_p"]);
        if(!empty($this->vars))
            extract($this->vars);
        require(APPROOT."/src/_elements/$elementName.php");
    }

    function redirect($url) {
        while(substr($url,0,1)=='/')
            $url = substr($url,1);
        if(substr($url,0,4)!='http')
            $url = APPBASE.$url;
        header("location: ".$url);
        die();
    }

    function actionUrl($idAction) {
        return \libs\app_action::getActionPath($idAction);
    }

    function sectionUrl($idSection) {
        return \libs\app_action::getSectionPath($idSection);
    }

    function interfaceUrl($idInterface) {
        return \libs\app_action::getInterfacePath($idInterface);
    }

    function backUrl($actions) {
        return \libs\app_action::getBackUrl($actions);
    }

    function set($key, $value) {
        $this->vars[$key] = $value;
    }

    function isRequest() {
        if(!empty($_POST))
            return true;
        return false;
    }

    function addMessage($msg) {
        $_SESSION["app_messages"][] = $msg;
    }

    function getMessages() {
        $msgs = $_SESSION["app_messages"];
        unset($_SESSION["app_messages"]);
        return $msgs;
    }

    function isActionAuth($id_action) {
        return \libs\app_action::isActionAuth($id_action);
    }

    function loadModel($modelName) {
        require_once(APPROOT."/src/_models/{$modelName}_model.php");
        $className = $modelName."_model";
        $this->$modelName = new $className();
    }

}

?>