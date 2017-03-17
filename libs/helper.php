<?php
namespace libs;
class helper {

    static protected $db;

    static function init() {
        if(self::$db===null)
            self::$db = db::get();
    }

    static function link($path) {
        return APPURL.$path;
    }

    static function flag($bgcolor, $color, $text) {
        $html = "<span style=\"background:$bgcolor;color:$color\" class=\"status\">$text</span>";
        echo $html;
    }

    static function img($id_upload, $size) {
        self::init();
        if(empty($id_upload))
            return "";
        $basename = self::$db->get_cell("select filename from app_upload where id_upload = $id_upload","filename");
        $sizeFolder = $size."/";
        return substr(APPFOLDER,0,strlen(APPFOLDER)-1)."/images/".$sizeFolder.$id_upload."/".$basename;
    }

    static function href($href) {
        return substr(APPFOLDER,0,strlen(APPFOLDER)-1).$href;
    }

    static function block($id_block) {
        self::init();
        return self::$db->get_cell("select content from content where id_content = $id_block","content");
    }

    static function blockTitle($id_block) {
        self::init();
        return self::$db->get_cell("select title from content where id_content = $id_block","title");
    }

    static function url($id_url) {
        self::init();
        return self::$db->get_cell("select url from app_url where id_url = $id_url","url");
    }
}