<?php
namespace libs;

class app_action {

    static protected $instance;

    static protected $db;

    static protected $data;

    static function init() {
        if(empty(self::$instance)) {
            self::$instance = new self();
            self::$db = db::get();
            self::identify();
        }
    }

    static function get() {
        if (self::$instance === null)
            self::$instance = new self();
        return self::$instance;
    }

    static protected function identify() {
        if(empty(request::$data["g"]["path"]))
            self::setHome();
        else {
            $path = request::$data["_g"]["path"];
            while(substr($path,strlen($path)-1,1)=='/')
                $path = substr($path,0,strlen($path)-1);
            $path = '/'.$path.'/';

            self::setURL($path);
            if(empty(self::$data)) {
                $id = request::$data["_g"]["id"];
                self::setURL($path,$id);
                if(empty(self::$data))
                    self::setAction($path);
            }
        }

        if(empty(self::$data)) {
            self::setError('404');
            return;
        }
        
        if(self::$data["interface_path"]=="/")
            self::$data["interface_path"] = "/visitor/";
    }

    static protected function setHome() {
        $query = "
            select a.id_interface, a.folder as interface_path, b.id_section, b.folder as section_path,
                c.id_action, c.url as action_path, c.section_layout as action_layout, e.title, e.description, e.keywords, null as params, d.id_url
            from app_interface a
            join app_section b on a.id_interface = b.id_interface and b.default = 1 and b.active = 1
            join app_action c on c.id_section = b.id_section and c.default = 1 and c.active = 1
            join app_url d on d.id_action = c.id_action and d.active = 1 and d.lang = 'en'
            left join app_seo e on e.id_url = d.id_url
            where a.folder = '/' and a.active = 1";
        self::$data = self::$db->get_row($query);
    }

    static protected function setURL($path, $id = null) {
        if(empty($id)) {
            $query = "
                select a.id_interface, a.folder as interface_path, b.id_section, b.folder as section_path,
                    c.id_action, c.url as action_path, c.section_layout as action_layout, e.title, e.description, e.keywords, params_passthrough as params, d.id_url
                from app_interface a
                join app_section b on a.id_interface = b.id_interface and b.active = 1
                join app_action c on c.id_section = b.id_section and c.active = 1
                join app_url d on d.url = '$path' and d.active = 1 and d.id_action = c.id_action and d.parameter_dependent = 0 and d.lang = 'en'
                left join app_seo e on e.id_url = d.id_url
                where a.active = 1";
        } else {
            $query = "
                select a.id_interface, a.folder as interface_path, b.id_section, b.folder as section_path,
                    c.id_action, c.url as action_path, c.section_layout as action_layout, e.title, e.description, e.keywords, params_passthrough as params, d.id_url
                from app_interface a
                join app_section b on a.id_interface = b.id_interface and b.active = 1
                join app_action c on c.id_section = b.id_section and c.active = 1
                join app_url d on d.url = '$path' and d.active = 1 and d.id_action = c.id_action and d.parameter_dependent = 1 and d.parameter = '{$id}' and d.lang = 'en'
                left join app_seo e on e.id_url = d.id_url
                where a.active = 1";
        }
        self::$data = self::$db->get_row($query);
    }

    static function setAction($path) {
        $query = "
            select a.id_interface, a.folder as interface_path, b.id_section, b.folder as section_path,
                c.id_action, c.url as action_path, c.section_layout as action_layout, null as title, null as description, null as keywords, null as params, null as id_url
            from app_interface a
            join app_section b on a.id_interface = b.id_interface and b.active = 1
            join app_action c on c.id_section = b.id_section and c.active = 1
            where a.active = 1 and ( ( a.folder = '$path' and b.default = 1 and c.default = 1 ) or ( replace(concat(a.folder,b.folder),'//','/') = '$path' and c.default = 1 ) or replace(concat(a.folder,b.folder,c.url),'//','/') = '$path'	)";
        self::$data = self::$db->get_row($query);
    }

    static function setError($errCode) {
        self::$data["interface_path"]   = 'visitor';
        self::$data["section_path"]     = '_error';
        self::$data["action_path"]      = $errCode;
        self::$data["action_layout"]    = 'default';
    }

    static function getActionId() {
        return self::$data["id_action"];
    }

    static function getURLId() {
        return self::$data["id_url"];
    }

    static function getSectionPath($idSection = null) {
        if(!empty($idSection)) {
            $query = "
                select replace(ifnull(d.url,concat(c.folder,b.folder,a.url)),'//','/') as url
                from app_action a
                join app_section b on a.id_section = b.id_section and b.active = 1
                join app_interface c on c.id_interface = b.id_interface and c.active = 1
                left join app_url d on d.id_action = a.id_action and d.active = 1
                where b.id_section = $idSection
                and a.default = 1";
            return self::$db->get_cell($query,"url");
        }
        return str_replace("/","",self::$data["section_path"]);
    }

    static function getSectionId() {
        return self::$data["id_section"];
    }

    static function getInterfacePath($idInterface = null) {
        if(!empty($idInterface)) {
            $query = "
                select replace(ifnull(d.url,concat(c.folder,b.folder,a.url)),'//','/') as url
                from app_action a
                join app_section b on a.id_section = b.id_section and b.active = 1 and b.default = 1
                join app_interface c on c.id_interface = b.id_interface and c.active = 1
                left join app_url d on d.id_action = a.id_action and d.active = 1
                where c.id_interface = $idInterface
                and a.default = 1";
            return self::$db->get_cell($query,"url");
        }
        return str_replace("/","",self::$data["interface_path"]);
    }

    static function getInterfaceId() {
        return self::$data["id_interface"];
    }

    static function getActionPath($idAction = null) {
        if(!empty($idAction)) {
            $query = "
                select replace(ifnull(d.url,concat(c.folder,b.folder,a.url)),'//','/') as url
                from app_action a
                join app_section b on a.id_section = b.id_section and b.active = 1
                join app_interface c on c.id_interface = b.id_interface and c.active = 1
                left join app_url d on d.id_action = a.id_action and d.active = 1
                where a.id_action = $idAction";
            return self::$db->get_cell($query,"url");
        }
        return str_replace("/","",self::$data["action_path"]);
    }

    static function getActionLayout() {
        return self::$data["action_layout"];
    }

    static function getAuthForm($idAction) {
        $query = "
            select unauthorized_action_id
            from app_action a 
            join app_section b on a.id_section = b.id_section and b.active = 1
            join app_interface c on c.id_interface = b.id_interface and c.active = 1
            where a.id_action = $idAction";
        return self::getActionPath(self::$db->get_cell($query,"unauthorized_action_id"));
    }

    static function isActionAuth($idAction) {
        $query = "
			select 1
			from app_action a
			join app_access_rules b on a.id_action = b.id_action and b.id_user in (".app_user::getOnlineUser().") and b.type = 'allow'
			where a.id_action = $idAction";
        if(!empty(self::$db->get_row($query)))
            return true;
        return false;
    }

    static function isSectionAuth($idSection) {
        $query = "
            select 1
            from app_section a
            join app_action b on b.default = 1 and b.active = 1
                and a.id_section = b.id_section
            join app_access_rules c on c.id_action = b.id_action and c.id_user in (".app_user::getOnlineUser().") and c.type = 'allow'
            where a.id_section = $idSection
            /* and b.id_action not in (6,8,13) /* admin - login, admin - logout, admin - my password */";
        //var_dump($query);die();
        if(!empty(self::$db->get_row($query)))
            return true;
        return false;
    }

    static function getBackUrl($actions) {
        $session_id = session_id();

        $qry_part = "";
        $url_part = "";
        if(!empty(request::$data["g"]["id_log"])) {
            $qry_part = "and a.id_log < ".request::$data["_g"]["id_log"];
            $url_part = "?id_log=".request::$data["g"]["id_log"];
        }

        if(count($actions)!=0) {
            $query = "
				select url,id_log
				from app_access_log
				where session_id = '{$session_id}'
				and id_action in (".implode(",",$actions).")
				$qry_part
				order by id_log desc";
        } else {
            $query = "
				select a.url,a.id_log
				from app_access_log a
				join app_action b on a.id_action = b.id_action
				join app_section c on c.id_section = b.id_section 
				where a.session_id = '{$session_id}'
				and c.id_interface = '".self::getInterfaceId()."'
				and a.id_action != '".self::getActionId()."'
				$qry_part
				order by id_log desc";
        }
        $url = self::$db->get_cell($query,"url");

        return $url.$url_part;
    }

    static function getData() {
        if(!empty(self::$data["id_url"]))
            return self::$data;
    }

    static function getParams() {
        if(!empty(self::$data["params"]))
            return unserialize(self::$data["params"]);
    }

}
?>