<?php
class content_controller extends app_controller {

    function content_list() {
        $this->set("contentTypes",$this->content->getContentType());
        $this->set("struct",$this->content->getContent());
    }

    function content_create() {
        if(!empty($this->data["_g"]["id_content"])) {
            $id_content = $this->data["g"]["id_content"];
            $this->data["_p"] = $this->content->getContent($id_content);
        }

        if($this->isRequest()) {
            $this->errors = $this->content->createContentCheck($this->data["p"]);
            if(count($this->errors)==0) {
                $this->content->createContentCommit();
                if(!empty($p["id_content"]))
                    $messages[] = "Webpage has been succesfully updated!";
                else
                    $messages[] = "New webpage has been succesfully created!";
                $this->redirect($this->backUrl(array(148)));
            }
        }
    }

    function content_edit() {
        $this->content_create();
    }

    function content_delete() {
        $this->content->deleteContent($this->data["g"]["id_content"]);
        $this->addMessage("Webpage has been succesfully removed!");
        $this->redirect($this->backUrl(array(148)));
    }

    function ajax() {
        $method = "ajax_".$this->data["g"]["o"];
        $term = $this->data["g"]["term"];
        $this->$method($term);
        die();
    }

    function gallery_list() {
    	$this->loadModel("gallery");
    	
    	if($this->isRequest()) {
    		$this->gallery->reorderImages($this->data["p"]["id_gallery"],$this->data["p"]["ord"]);
    		$this->redirect($this->backUrl(array(165)));
    	}
    	
    	$this->set("struct",$this->gallery->getImages());
    }
    
    function gallery_create() {
    	$this->loadModel("gallery");
    	
    	if(!empty($this->data["_g"]["id_gallery"])) {
    		$id_gallery = $this->data["g"]["id_gallery"];
    		$this->data["_p"] = $this->gallery->getImages($id_gallery);
    	}
    	
    	if($this->isRequest()) {
    		$this->errors = $this->gallery->createImageCheck($this->data["p"]);
    		if(count($this->errors)==0) {
    			$this->gallery->createImageCommit();
    			if(!empty($this->data["p"]["id_gallery"]))
    				$this->addMessage("Gallery image has been succesfully updated!");
    			else
    				$this->addMessage("New gallery image has been succesfully created!");
    			$this->redirect($this->backUrl(array(165)));
    		}
    	}
    	
    	$this->set("p",$this->data["_p"]);
    }
    
    function gallery_edit() {
    	$this->gallery_create();
    }

}
?>