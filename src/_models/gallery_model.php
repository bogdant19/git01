<?php
class gallery_model extends app_model {
    
	function getImages($id_gallery = null, $onlyActive = null) {
		if($id_gallery === null) {
			$cond = "";
			if($onlyActive === true)
				$cond .= "and active = 1";
			$query = "
				select id_gallery, title, id_upload, ord, active
				from gallery
				where 1=1
				$cond
				order by ord";
			if($onlyActive === true)
				return $this->db->get_rows($query);
			else
				return $this->db->get_page_rows($query, $this->getPageNumber(), $this->getRowsPerPage());
		} else {
			$query = "
				select id_gallery, title, id_upload, active
				from gallery
				where id_gallery = $id_gallery";
			return $this->db->get_row($query);
		}
	}
	
	function createImageCheck($vars) {
        $this->data = $vars;
        $errors = array();

        if(trim($vars["title"])=="")
            $errors[] = "Please provide the image title!";
        if($vars["id_gallery"]===null) {
            if($vars["_files"]["image"]["name"]=="")
                $errors[] = "Please provide the image file!";
            elseif(!$this->uploader->checkImage($vars["_files"]["image"]))
                $errors[] = "The uploaded file is not a valid image!";
        } else {
            if($vars["_files"]["image"]["name"]!="" && !$this->uploader->checkImage($vars["_files"]["image"]))
                $errors[] = "The uploaded file is not a valid image!";
        }
        return $errors;
    }
    
    function createImageCommit() {
    	$vars = $this->data;
    	\libs\set_checkbox($vars["active"]);
    	if($vars["_files"]["image"]["name"]!="")
    		$vars["id_upload"] = $this->uploader->getUploadId();
    	if($vars["id_gallery"]===null) {
    		$query = "
	    		insert into gallery(title,id_upload,ord,active,di)
	    		values('{$vars["title"]}',{$vars["id_upload"]},0,{$vars["active"]},now())";
    		$id_gallery = $this->db->execute($query);
    		$this->reorderImages($id_gallery);
    	} else {
	    	$query = "
		    	update gallery set title = '{$vars["title"]}',
			    	id_upload = {$vars["id_upload"]},
			    	active = {$vars["active"]}
		    	where id_gallery = {$vars["id_gallery"]}";
	    	$this->db->execute($query);
    	}
	}
	
	function reorderImages($id_gallery=null, $ord=null) {
		if($id_gallery===null) {
			$query = "
				select a.id_gallery
				from gallery a
				order by a.ord";
			$images = $this->db->get_rows($query);
			$i = 1;
			foreach($images as $g) {
				$query = "update gallery set ord = $i where id_gallery = {$g["id_gallery"]}";
				$this->db->execute($query);
				$i++;
			}
		} else {
			if($ord===null) {
				$ord = $this->db->get_cell("select max(ord) as max_ord from gallery","max_ord");
				if(empty($ord)) $ord = 0;
				$ord++;
				$query = "update gallery set ord = $ord where id_gallery = $id_gallery";
				$this->db->execute($query);
			} else {
				$o_id_gallery = $this->db->get_cell("select id_gallery from gallery where ord = $ord","id_gallery");
				$o_ord = $this->db->get_cell("select ord from gallery where id_gallery = $id_gallery","ord");
				$this->db->execute("update gallery set ord = $ord where id_gallery = $id_gallery");
				$this->db->execute("update gallery set ord = $o_ord where id_gallery = $o_id_gallery");
			}
		}
	}
}
?>