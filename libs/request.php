<?php
namespace libs;

require_once(APPROOT."/libs/app_user.php");

require_once(APPROOT."/libs/app_action.php");

class request {

    static protected $instance;

    static $data;

    static protected $idAccessLog;

    static protected $db;

    static function init() {
        if(empty(self::$instance)) {
            self::$instance = new self();
            self::$db = db::get();
            self::process();
            self::log_access();
            self::fetch_files();
            app_action::init();
            app_user::init();
            self::log_access();
        }
    }

    static function get() {
        if (self::$instance === null)
            self::$instance = new self();
        return self::$instance;
    }

    static protected function fetch_files() {

        $ext = pathinfo(basename(self::$data["g"]["path"]),PATHINFO_EXTENSION);

        if(!empty($ext)) {
            $urlParts = explode("/",self::$data["g"]["path"]);
            $id_upload = @$urlParts[2];
            $size = @$urlParts[1];

            $query = "
				select a.mime, b.path, b.width, b.height
				from app_upload a
				join app_upload_path b on a.id_upload = b.id_upload
					and b.size = 'orig'
				where a.id_upload = '{$id_upload}'";
            $file = self::$db->get_row($query);

            if(!empty($file)) {
                if($size=='orig') {
                    header("Content-type: " . $file["mime"]);
                    readfile(APPROOT . "/data/uploads" . $file["path"]);
                    die();
                } else {
                    $vars = explode("x",$size);
                    if(!in_array($vars[2],array("C","N")))
                        die();
                    $uploader = \libs\uploader::get();
                    $uploader->display($file,$vars[0],$vars[1],$vars[2]==="C",$ext);
                }
            }
        }

    }

    static protected function log_access() {
        if(empty(self::$idAccessLog)) {
            // self::$db->execute("call appLogClean;");

            $sessionId = session_id();
            $url = APPURL;
            $ip = $_SERVER["REMOTE_ADDR"];
            $postVars = "";
            if (!empty($_POST))
                $postVars = serialize(self::$data['p']);
            $query = "
                insert into app_access_log(session_id,url,ip,post_vars,di)
                values('$sessionId','$url','$ip','$postVars',now())";
            self::$idAccessLog = self::$db->execute($query);
        } else {
            $query = "
                update app_access_log set id_action = ".(empty(app_action::getActionId()) ? '0' : app_action::getActionId()).",
                    id_url = ".(empty(app_action::getURLId()) ? '0' : app_action::getURLId())."
                where id_log = ".self::$idAccessLog;
            self::$db->execute($query);
        }
    }

    static protected function process() {

        if(!empty($_GET)) {
            if(empty(self::$data['g']) && empty(self::$data['_g'])) {
                self::$data['g'] = $_GET;
                self::$data['_g'] = escape($_GET);
             } else
                die('FATAL ERROR: variable "g" or "_g" should not be set');
        }

        if(!empty($_POST)) {
            if(empty(self::$data['p']) && empty(self::$data['_p'])) {
                self::$data['p'] = escape($_POST);
                self::$data['_p'] = $_POST;
                self::$data['p']['_files'] = escape(@$_FILES);
                self::$data['_p']['_files'] = @$_FILES;
            } else
                die('FATAL ERROR: variable "p" or "_p" should not be set');
        }

    }

}