<?php

class users_controller extends app_controller {

    function member_login() {
        if(\libs\app_user::isOnline(array("administrator","developer")))
            $this->redirect(\libs\app_action::getInterfacePath());
        if(!empty($this->data["p"])) {
            $data = $this->users->loginCheck($this->data["p"]);
            if(!$data) {
                $this->errors[] = "Incorrect login information!";
            } else {
                $this->users->loginCommit($data);
                $this->redirect(\libs\app_action::getInterfacePath());
            }
        }
    }

    function member_list() {
        $query = "
            select a.id_user, a.email, b.name as group_name, a.di, a.last_login, a.active, a.id_user_group
            from app_user a
            join app_user_group b on a.id_user_group = b.id_user_group
            where 1=1
            order by a.di desc";
        $this->set("struct",$this->db->get_page_rows($query,@$this->data["_g"]["p"],15));
    }

    function member_logout() {
        $this->users->logoutCommit();
        $this->redirect(\libs\app_action::getInterfacePath());
    }

    function my_password() {
        $onlineUser = \libs\app_user::getOnlineUser("developer");
        if(empty($onlineUser))
            $onlineUser = \libs\app_user::getOnlineUser("administrator");
        $this->users->id = $onlineUser["id_user"];
        if(!empty($this->data["p"])) {
            if(md5($this->data["p"]["opassword"])!=$onlineUser["password"])
                $this->errors[] = "Incorrect old password provided!";
            else {
                if(strlen($this->data["p"]["password"]) < 6)
                    $this->errors[] = "New password must contain minimum 6 characters!";
                else {
                    if($this->data["p"]["password"]!=$this->data["p"]["cpassword"])
                        $this->errors[] = "New password confirmation failed!";
                }
            }

            if(empty($this->errors)) {
                $password = md5($this->data["p"]["password"]);
                $this->users->updateField("password",$password);
                $this->addMessage("You have succesfully changed your password!");
            }
        }
    }

    function member_create() {
        $edit = false;
        if(!empty($this->data["g"]["id"])) {
            $this->users->id = $this->data["g"]["id"];
            $edit = true;
        }

        if($this->isRequest()) {
            $this->errors = $this->users->createMemberCheck($this->data["_p"]);
            if(count($this->errors)==0) {
                $this->users->createMemberCommit($this->data["_p"]);
                if($edit)
                    $this->addMessage("User has been succesfully updated!");
                else
                    $this->addMessage("New user has been succesfully created!");
                $this->redirect($this->backUrl(array(9)));
            }
        } elseif($edit)
            $this->data["_p"] = $this->users->getUser();

        $this->set("edit",$edit);
        $this->set("userGroups",$this->users->getGroups());
    }

    function member_edit() {
        $this->member_create();
    }

    function member_password_change() {
        $this->users->id = $this->data["g"]["id"];

        if($this->isRequest()) {
            $this->errors = $this->users->changePasswordCheck($this->data["_p"]);
            if(count($this->errors)==0) {
                $this->users->changePasswordCommit();
                $this->addMessage("User's password has been succesfully changed!");
                $this->redirect($this->backUrl(array(9)));
            }
        } else {
            $this->data["_p"] = $this->users->getUser();
        }
    }

    function member_delete() {
        $this->users->id = $this->data["g"]["id"];
        $this->users->delete();
        $this->addMessage("User has been succesfully deleted!");
        $this->redirect($this->backUrl(array(9)));
    }

    function group_rights_list() {
        $this->set("groups",$this->users->getGroups($id=null,$includeVisitor=true));
    }

    function group_rights_edit() {
        $id_group = $this->data["g"]["id"];
	    $group = $this->users->getGroups($id_group);

        $interfaces = $this->users->getInterfaces();
        for($i=0;$i<count($interfaces);$i++) {
            $sections = $this->users->getSections($interfaces[$i]["id_interface"]);
            for($j=0;$j<count($sections);$j++)
                $sections[$j]["actions"] = $this->users->getGroupActions($id_group,$sections[$j]["id_section"]);
            $interfaces[$i]["sections"] = $sections;
        }

        if($this->isRequest()) {
            $this->users->groupActionsCommit($id_group, $this->data["_p"]);
            $this->addMessage("Group permissions have been succesfully updated!");
            $this->redirect($this->actionUrl(\libs\app_action::getActionId())."?id=$id_group");
        }

        $this->set("group",$group);
        $this->set("interfaces",$interfaces);
    }

    function website_section_list() {
        $interfaces = $this->users->getInterfaces();
        for($i=0;$i<count($interfaces);$i++) {
            $sections = $this->users->getSections($interfaces[$i]["id_interface"]);
            for($j=0;$j<count($sections);$j++)
                $sections[$j]["actions"] = $this->users->getActions($sections[$j]["id_section"]);
            $interfaces[$i]["sections"] = $sections;
        }
        $this->set("interfaces",$interfaces);
    }

    function website_section_create() {
        if(!empty($this->data["_g"]["id_section"])) {
            $id_section = $this->data["g"]["id_section"];
            $this->data["_p"] = $this->users->getSection($id_section);
        }

        if($this->isRequest()) {
            $this->errors = $this->users->createSectionCheck($this->data["p"]);
            if(count($this->errors)==0) {
                $this->users->createSectionCommit();
                if(!empty($id_section))
                    $this->addMessage("Website section has been succesfully updated!");
                else
                    $this->addMessage("New website section has been succesfully created!");
                $this->redirect($this->backUrl(array(142)));
            }
        }
        $this->set("p",$this->data["_p"]);
        $this->set("id_interface",$this->data["g"]["id_interface"]);
    }

    function website_section_delete() {
        $this->users->deleteSection($this->data["g"]["id_section"]);
        $this->addMessage("Website section has been succesfully removed!");
        $this->redirect($this->backUrl(array(142)));
    }

    function website_action_create() {
        $id_action = $this->data["g"]["id_action"];
        if(!empty($id_action)) {
            $this->data["_p"] = $this->users->getAction($id_action);
            $id_section = $this->data["_p"]["id_section"];
        } else
            $id_section = $this->data["g"]["id_section"];

        if($this->isRequest()) {
            $this->errors = $this->users->createActionCheck($this->data["p"]);
            if(count($this->errors)==0) {
                $this->users->createActionCommit();
                if(!empty($id_action))
                    $this->addMessage("Website action has been succesfully updated!");
                else
                    $this->addMessage("New website action has been succesfully created!");
                $this->redirect($this->backUrl(array(142)));
            }
        }

        $section = $this->users->getSection($id_section);

        $this->set("id_action",$id_action);
        $this->set("id_section",$id_section);
        $this->set("section",$section);
        $this->set("layouts",$this->users->getInterfaceLayouts($section["id_interface"]));
        $this->set("groups",$this->users->getGroups());
    }

    function website_action_delete() {
        $this->users->deleteAction($this->data["g"]["id_action"]);
        $this->addMessage("Website action has been succesfully removed!");
        $this->redirect($this->backUrl(array(142)));
    }

    function website_action_edit() {
        $this->website_action_create();
    }

    function website_section_edit() {
        $this->website_section_create();
    }

}

?>