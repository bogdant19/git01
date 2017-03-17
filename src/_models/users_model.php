<?php

class users_model extends app_model {
    function loginCheck($vars) {
        switch(\libs\app_action::getInterfaceId()) {
            case 2: $qPart = "and a.id_user_group in (2,3,5)"; break;
            case 3: $qPart = "and a.id_user_group in (4)"; break;
            default: $qPart = "";
        }
        $query = "
			select identifier, id_user, a.id_user_group
			from app_user a
			join app_user_group b on a.id_user_group = b.id_user_group
			where (username = '{$vars["email"]}' or email = '{$vars["email"]}') and password = '".md5($vars["password"])."' and a.active = 1 $qPart";
        return $this->db->get_row($query);
    }

    function loginCommit($vars) {
        $query = "
			update app_user set last_login = now()
			where id_user = {$vars["id_user"]}";
        $this->db->execute($query);
        $_SESSION["app_user"][$vars["identifier"]] = array("id_user" => $vars["id_user"]);
    }

    function logoutCommit() {
        switch(\libs\app_action::getInterfaceId()) {
            case 2: {
                unset($_SESSION["app_user"]["administrator"]);
                unset($_SESSION["app_user"]["developer"]);
            } break;
            case 3: {
                unset($_SESSION["app_user"]["member"]);
            } break;
        }
    }

    function updateField($field,$value) {
        $query = "
            update app_user set $field = '{$value}'
            where id_user = {$this->id}";
        $this->db->execute($query);
    }

    function getGroups($id=null,$includeVisitor=false) {
        $queryPart = "";
        if(!empty($id))
            $queryPart .= " and id_user_group = $id ";
        if(!$includeVisitor)
            $queryPart .= " and id_user_group != 1 ";
        $query = "select * from app_user_group where 1=1 $queryPart order by name";
        if(!empty($id))
            return $this->db->get_row($query);
        return $this->db->get_rows($query);
    }

    function checkUniqueEmail($vars) {
        $query_part = "";
        if(!empty($vars["id"]))
            $query_part = "and id_user != {$vars["id"]}";
        $query = "
			select count(1) as nr
			from app_user
			where id_user_group = '{$vars["id_user_group"]}'
			and email = '{$vars["email"]}'".$query_part;
        if($this->db->get_cell($query,"nr")>0)
            return false;
        return true;
    }

    function createMemberCheck($vars) {
        $errors = array();

        if(trim($vars["id_user_group"])=="")
            $errors[] = "Please select the user group!";

        if(trim($vars["email"])=="")
            $errors[] = "Please provide the email address!";
        elseif(!$this->checkUniqueEmail($vars))
            $errors[] = "This email address is alrady taken!";

        if(empty($vars["id"])) {
            if(trim($vars["password"])=="")
                $errors[] = "You must specify password!";
            elseif(strlen($vars["password"])<6)
                $errors[] = "Your password must be at least 6 characters long!";
            elseif($vars["password"]!=$vars["cpassword"])
                $errors[] = "Password confirmation failed!";
        }

        return $errors;
    }

    function createMemberCommit($vars) {
        \libs\set_checkbox($vars["active"]);

        if(!empty($vars["id"])) {
            $id_user = $vars["id"];
            $query = "
				update app_user set id_user_group = {$vars["id_user_group"]}, 
					email = '{$vars["email"]}',
					active = '{$vars["active"]}'
				where id_user = $id_user";
            $this->db->execute($query);
        } else {
            $query = "
				insert into app_user(id_user_group,email,password,active,di,last_login)
				values({$vars["id_user_group"]},'{$vars["email"]}','".md5($vars["password"])."',{$vars["active"]},now(),now())";
            $id_user = $this->db->execute($query);

            $query = "
				insert into app_access_rules(id_action,id_user,type,custom)
				select id_action, {$id_user}, 'allow', 0
				from app_access_group_rules
				where id_user_group = {$vars["id_user_group"]}";
            $this->db->execute($query);
        }
    }

    function getUser($id = null) {
        if($id === null)
            $id = $this->id;
        $query = "
            select *
            from app_user a 
            where a.id_user = $id";
        return $this->db->get_row($query);
    }

    function changePasswordCheck($vars) {
        $this->data = $vars;
        $errors = array();

        if(trim($vars["npass"])=="")
            $errors[] = "Please provide the new password!";
        elseif(strlen(trim($vars["npass"]))<6)
            $errors[] = "Password must be at least 6 characters long!";
        if($vars["npass"]!=$vars["cnpass"])
            $errors[] = "Password confirmation failed!";

        return $errors;
    }

    function changePasswordCommit() {
        $vars = $this->data;
        $pass = md5($vars["npass"]);
        $query = "update app_user set password = '$pass' where id_user = {$vars["id_user"]}";
        $this->db->execute($query);
    }

    function delete($id=null) {
        if($id===null)
            $id = $this->id;
        $query = "delete from app_user where id_user = $id";
		$this->db->execute($query);
    }

    function getInterfaces($adminIncluded=true,$id_interface=null) {
        if($id_interface===null) {
            if ($adminIncluded)
                $query = "SELECT * FROM app_interface ORDER BY name";
            else
                $query = "SELECT * FROM app_interface WHERE id_interface != 2 ORDER BY name";
            return $this->db->get_rows($query);
        } else {
            return $this->db->get_row("select * from app_interface where id_interface = $id_interface");
        }
    }

    function getSections($id_interface = null) {
        $query = "select * from app_section where id_interface = $id_interface order by name";
        return $this->db->get_rows($query);
    }

    function getGroupActions($id_group, $id_section) {
        $query = "
            select a.*,case when b.id_access_rule is null then '' else 'checked' end as checked
            from app_action a
            left join app_access_group_rules b on a.id_action = b.id_action
              and b.id_user_group = {$id_group}
            where id_section = {$id_section}
            order by name";
        return $this->db->get_rows($query);
    }

    function getActions($id_section) {
        $query = "
            select a.*
            from app_action a
            where id_section = {$id_section}
            order by name";
        return $this->db->get_rows($query);
    }

    function groupActionsCommit($id_group, $vars) {
        $query = "
			delete a
			from app_access_group_rules a
			where a.id_user_group = {$id_group}";
        $this->db->execute($query);

        if($id_group!=1) {
            $query = "
				delete a
				from app_access_rules a
				join app_user b on a.id_user = b.id_user
					and b.id_user_group = $id_group";
        } else {
            $query = "
				delete a
				from app_access_rules a
				where id_user = 0";
        }
        $this->db->execute($query);

        foreach($vars["actions"] as $id_action) {
            $query = "
				insert into app_access_group_rules(id_user_group,id_action)
				values($id_group,$id_action)";
            $this->db->execute($query);

            if($id_group!=1) {
                $query = "
					insert into app_access_rules(id_action,id_user,type,custom)
					select $id_action,id_user,'allow',0
					from app_user
					where id_user_group = $id_group";
            } else {
                $query = "
					insert into app_access_rules(id_action,id_user,type,custom)
					values($id_action,0,'allow',0)";
            }
            $this->db->execute($query);
        }
    }

    public function createSectionCheck($vars) {
        $errors = array();

        if(trim($vars["id_interface"])=="")
            $errors[] = "You must select the interface!";
        if(trim($vars["name"])=="")
            $errors[] = "You must provide a name for the section!";
        $query_part = "";
        if(!empty($vars["id_section"]))
            $query_part = "and a.id_section!={$vars["id_section"]}";
        $query = "
			select count(1) as nr
			from app_section a
			join app_interface b on a.id_interface = b.id_interface
			where a.folder = '/{$vars["folder"]}/'
			and b.id_interface = '{$vars["id_interface"]}'
			$query_part";
        if($this->db->get_cell($query,"nr")>0)
            $errors[] = "This section URL already exists under this interface!";
        $this->data = $vars;
        return $errors;
    }

    public function createSectionCommit() {
        $vars = $this->data;

        \libs\set_checkbox($vars["active"]);
        \libs\set_checkbox($vars["default"]);

        $default = 0;
        if(@$vars["default"]==1) {
            $query = "
                update app_section set `default` = 0
                where `default` = 1
                and id_interface = {$vars["id_interface"]}";
            $this->db->execute($query);
            $default = 1;
        }


        if(!empty($vars["id_section"])) {
            $query = "
				update app_section set id_interface = {$vars["id_interface"]},
					name = '{$vars["name"]}',
					folder = '/{$vars["folder"]}/',
					`default` = $default,
					active = {$vars["active"]}
				where id_section = {$vars["id_section"]}";
        } else {
            $query = "
				insert into app_section(id_interface,name,folder,`default`,active)
				values({$vars["id_interface"]},'{$vars["name"]}','/{$vars["folder"]}/',$default,{$vars["active"]})";
        }
        $this->db->execute($query);
    }

    function deleteSection($id_section) {
        $query = "
			delete a,b,c,d
			from app_section a
			left join app_action b on a.id_section = b.id_section
			left join app_access_group_rules c on c.id_action = b.id_action
			left join app_access_rules d on d.id_action = b.id_action
			where a.id_section = '{$id_section}'";
        $this->db->execute($query);
    }

    function getSection($id_section) {
        $query = "
          select id_section, name, substring(folder,2,length(folder)-2) as folder, `default`, active, id_interface
          from app_section where id_section = $id_section";
        return $this->db->get_row($query);
    }

    function getInterfaceLayouts($id_interface) {
        $query = "select * from app_interface_layout where id_interface = $id_interface order by name";
        return $this->db->get_rows($query);
    }

    function createActionCheck($vars) {
        $this->data = $vars;
        $errors = array();
        if($vars["section_layout"]=="")
            $errors[] = "Please select the layout!";
        if(trim($vars["section_layout"])=="")
            $errors[] = "Please specify name!";
        $query_part = "";
        if(!empty($vars["id_action"]))
            $query_part = "and a.id_action!={$vars["id_action"]}";
        $query = "
			select count(1) as nr
			from app_action a
			where url = '/{$vars["url"]}/'
			and id_section = {$vars["id_section"]}
			$query_part";
        if($this->db->get_cell($query,"nr")>0)
            $errors[] = "This action already exists under this section!";
        return $errors;
    }

    function createActionCommit() {
        $vars = $this->data;
        $default = 0;
        if(@$vars["default"]==1) {
            $query = "update app_action set `default` = 0 where `default` = 1 and id_section = {$vars["id_section"]}";
            $this->db->execute($query);
            $default = 1;
        }
        \libs\set_checkbox($vars["active"]);

        if(empty($vars["id_action"])) {
            $query = "
				insert into app_action(id_section,name,url,section_layout,`default`,active)
				values({$vars["id_section"]},'{$vars["name"]}','/{$vars["url"]}/','{$vars["section_layout"]}',$default,{$vars["active"]})";
            $id_action = $this->db->execute($query);
        } else {
            $query = "
				update app_action set name = '{$vars["name"]}',
					url = '/{$vars["url"]}/',
					section_layout = '{$vars["section_layout"]}',
					`default` = $default,
					active = {$vars["active"]}
				where id_action = {$vars["id_action"]}";
            $this->db->execute($query);
            $id_action = $vars["id_action"];
        }

        if(@$vars["has_seo"]==1) {
            $query = "delete a,b from app_url a join app_seo b on a.id_url = b.id_url where a.id_action = $id_action";
            $this->db->execute($query);

            $query = "
                insert into app_url(lang,url,id_action,active)
                values('en','/{$vars["seo_url"]}/',{$id_action},{$vars["active"]})";
            $id_url = $this->db->execute($query);

            $query = "
                insert into app_seo(id_url,title,keywords,description)
                values($id_url,'{$vars["seo_title"]}','{$vars["seo_keywords"]}','{$vars["seo_description"]}')";
            $this->db->execute($query);

            $query = "
                insert into content(type,id_url,name,active,di)
                values('seo',$id_url,'{$vars["name"]}',{$vars["active"]},now())";
            $this->db->execute($query);
        }

        if(!empty($vars["groups"])) {
            $query = "delete from app_access_group_rules where id_action = $id_action";
            $this->db->execute($query);

            $query = "
				delete from app_access_rules where id_action = $id_action";
            $this->db->execute($query);

            foreach($vars["groups"] as $id_user_group) {
                $query = "
					insert into app_access_group_rules(id_user_group,id_action)
					values('{$id_user_group}','{$id_action}')";
                $this->db->execute($query);

                if($id_user_group!=1) {
                    $query = "
						insert into app_access_rules(id_action,id_user,type,custom)
						select $id_action, id_user, 'allow', 0
						from app_user
						where id_user_group = $id_user_group";
                } else {
                    $query = "
						insert into app_access_rules(id_action,id_user,type,custom)
						values($id_action, 0, 'allow', 0)";
                }
                $this->db->execute($query);
            }
        }
    }

    function deleteAction($id_action) {
        $query = "
			delete a,b,c
			from app_action a
			left join app_access_group_rules b on b.id_action = a.id_action
			left join app_access_rules c on c.id_action = a.id_action
			where a.id_action = '{$id_action}'";
        $this->db->execute($query);
    }

    function getAction($id_action) {
        $query = "
			select a.id_user_group, b.id_access_rule
			from app_user_group a
			left join app_access_group_rules b on a.id_user_group = b.id_user_group
				and b.id_action = $id_action
			order by a.name";
        $groups = $this->db->get_rows($query);

        $query = "
			select section_layout, name, substring(a.url,2,length(a.url)-2) as url, `default`, active, id_section,
				case ifnull(b.id_url,0)
					when 0 then 0
					else 1
				end as has_seo,
				b.seo_title, b.seo_keywords, b.seo_description,
				case b.seo_url
					when '/' then ''
					else substring(b.seo_url,2,length(b.seo_url)-2)
				end as seo_url
			from app_action a
			left join (
				select a1.title as seo_title, a1.id_url, b1.id_action, a1.keywords as seo_keywords, a1.description as seo_description,
					b1.url as seo_url
				from app_seo a1
				join app_url b1 on a1.id_url = b1.id_url
				where b1.params_passthrough = '' ) b on a.id_action = b.id_action
			where a.id_action = $id_action";
        $p = $this->db->get_row($query);

        foreach($groups as $group)
            if(!empty($group["id_access_rule"]))
                $p["groups"][] = $group["id_user_group"];
            else
                $p["groups"][] = 0;

        return $p;
    }

}

?>