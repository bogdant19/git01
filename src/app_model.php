<?php
use \libs\db;
use \libs\utils;
use \libs\uploader;

class app_model {
    public $db;
    public $id;
    public $item;
    public $data;
    public $uploader;

    function __construct() {
        $this->db = db::get();
        $this->uploader = uploader::get();
    }

    function getPageNumber() {
        if(!empty($_GET["p"]) && is_numeric($_GET["p"]) && $_GET["p"] > 1)
            return \libs\escape($_GET["p"]);
        else
            return 1;
    }

    function getRowsPerPage() {
        return 20;
    }
}

?>