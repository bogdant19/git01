<?php
namespace libs;

class app_user {

    static protected $instance;

    static protected $db;

    static function init() {
        if(empty(self::$instance)) {
            self::$instance = new self();
            self::$db = db::get();
        }
        if(empty(self::getOnlineUser("visitor")))
            self::setOnlineUser("visitor",0);
        if(app_action::getSectionPath()!="_error")
            self::checkAccess();
    }

    static function get() {
        if (self::$instance === null)
            self::$instance = new self();
        return self::$instance;
    }

    static function getUser($idUser) {
        if(empty($idUser))
            return null;
        $query = "select a.* from app_user a join app_user_group b on a.id_user_group = b.id_user_group where a.id_user = $idUser";
        return self::$db->get_row($query);
    }

    static function getGroups() {
        $query = "select distinct identifier from app_user_group";
        return self::$db->get_rows($query);
    }

    static function getOnlineUser($groupName=null) {
        if(empty($groupName)) {
            $ids = "";
            foreach(self::getGroups() as $g) {
                if($_SESSION["app_user"][$g["identifier"]]["id_user"]!==null)
                    $ids .= $_SESSION["app_user"][$g["identifier"]]["id_user"].",";
            }
            return substr($ids,0,strlen($ids)-1);
        } else {
            $idUser = $_SESSION["app_user"][$groupName]["id_user"];
            return self::getUser($idUser);
        }
    }

    static function setOnlineUser($groupName,$idUser) {
        $_SESSION["app_user"][$groupName]["id_user"] = $idUser;
    }

    static function checkAccess() {
        $query = "
            select 1
            from app_access_rules
            where id_action = '".app_action::getActionId()."' and type = 'allow' and id_user in (".self::getOnlineUser().")
            limit 1";

        if(empty(self::$db->get_row($query))) {
            // user doesnt have access - but check if this is not an authorization form
            $query = "
                select 1
                from app_action a
                join app_section b on a.id_section = b.id_section and b.active = 1
                join app_interface c on c.id_interface = b.id_interface and c.active = 1
                where a.id_action = ".app_action::getActionId()." and a.id_action = c.unauthorized_action_id";

            // if it is not an authorization form
            if(empty(self::$db->get_row($query)))
                app_action::setAction(app_action::getAuthForm(app_action::getActionId()));
        }
    }

    static function isOnline($identifiers) {
        $isOnline = false;
        foreach($identifiers as $identifier)
            if(!empty($_SESSION["app_user"][$identifier]))
                $isOnline = true;
        return $isOnline;
    }

}

?>