<?php
class content_model extends app_model {
    private $types = array(
                        "page"=>"Webpage",
                        "seo"=>"SEO",
                        "block"=>"Text Block");

    function getContentType($type=null) {
        if($type===null)
            return $this->types;
        else
            return $this->types[$type];
    }

    private function _getContentFilter() {
        $type = \libs\escape(@$_GET["type"]);
        $active = \libs\escape(@$_GET["active"]);
        $query = "";
        if($type!="")    $query .= " and type = '{$type}' ";
        if($active!="")  $query .= " and active = {$active} ";
        return $query;
    }

    function getContent($id=null) {
        if($id===null)
            return $this->db->get_page_rows("select * from content where 1=1 ".$this->_getContentFilter()."order by type, name",
                $this->getPageNumber(),
                $this->getRowsPerPage());
        else {
            $query = "
                select a.id_content, a.name, a.title, substring(b.url,2,length(b.url)-2) as url, a.content, a.active, c.title as seo_title,
                    c.description as seo_description, c.keywords as seo_keywords, b.id_url, a.type
                from content a
                left join app_url b on a.id_url = b.id_url
                left join app_seo c on c.id_url = a.id_url
                where a.id_content = $id";
            $p = $this->db->get_row($query);

            $this->item = $p;
            return $p;
        }
    }

    function createContentCheck($vars) {
        $errors = array();
        $this->data = $vars;

        if(trim($vars["name"])=="")
            $errors[] = "Specify the name of the page for backend usage!";
        if(!in_array($vars["type"],array("seo","block"))) {
            if(trim($vars["title"])=="")
                $errors[] = "Specify the title of the page for frontend display!";
        }
        if($vars["type"]!="block") {
            if(trim($vars["url"])=="" && $vars["id_content"]==43);
            elseif(trim($vars["url"])=="")
                $errors[] = "URL must be provided!";
            else {
                $query_part = "";
                if(!empty($vars["id_content"]))
                    $query_part = "and id_content!={$vars["id_content"]}";
                $query = "
					select count(1) as nr
					from app_url a
					join content b on a.id_url = b.id_url
					where url = '/{$vars["url"]}/'
					$query_part";
                if($this->db->get_cell($query,"nr")>0)
                    $errors[] = "This URL already exists on the website!";
            }
        }
        return $errors;
    }

    function createContentCommit() {

        $vars = $this->data;

        \libs\set_checkbox($vars["active"]);
        \libs\set_checkbox($vars["member"]);
        \libs\set_checkbox($vars["visitor"]);
        \libs\set_checkbox($vars["sitemap"]);

        if(!empty($vars["id_content"])) {
            $query = "
				update content set name = '{$vars["name"]}',
					title = '{$vars["title"]}',
					content = '{$vars["content"]}',
					active = {$vars["active"]}
				where id_content = {$vars["id_content"]};
				
				update app_url set url = '/{$vars["url"]}/'
				where id_url = {$vars["id_url"]};
				
				update app_seo set title = '{$vars["seo_title"]}',
					keywords = '{$vars["seo_keywords"]}',
					description = '{$vars["seo_description"]}'
				where id_url = {$vars["id_url"]};";
            $this->db->execute($query);

            $display_reorder = false;
            $params = array("visitor","sitemap");
            foreach($params as $param) {
                if($vars[$param]!=$this->item[$param]) {
                    if($vars[$param]==0)
                        $this->displayContentDelete(($param=="sitemap" ? 0 : $this->item[$param]), $vars["id_content"]);
                    else
                        $this->displayContentAdd(($param=="sitemap" ? 0 : $vars[$param]), ($param=="sitemap" ? "none" : $vars[$param."_display"]), $vars["id_content"]);
                    $display_reorder = true;
                }
            }
            if($display_reorder)
                $this->displayContentReorder();
        } else {
            $query = "
				insert into content(type,name,title,content,active,di)
				values('page','{$vars["name"]}','{$vars["title"]}','{$vars["content"]}',{$vars["active"]},now())";
            $id_content = $this->db->execute($query);

            $query = "
				insert into app_url(url,id_action,params_passthrough,active)
				values('/{$vars["url"]}/',156,'a:1:{s:10:\"id_content\";i:{$id_content};}',{$vars["active"]})";
            $id_url = $this->db->execute($query);

            $query = "
				insert into app_seo(id_url,title,description,keywords)
				values($id_url,'{$vars["seo_title"]}','{$vars["seo_description"]}','{$vars["seo_keywords"]}');
				
				update content set id_url = $id_url
				where id_content = {$id_content}";
            $this->db->execute($query);

            $params = array("visitor","sitemap");
            foreach($params as $param)
                if(!empty($vars[$param]))
                    $this->displayContentAdd(($param=="sitemap" ? 0 : $vars[$param]), ($param=="sitemap" ? "none" : $vars[$param."_display"]), $id_content);

        }
    }

    function deleteContent($id) {
        $query = "
			delete a,c,d
			from content a
			left join app_url c on c.id_url = a.id_url
			left join app_seo d on d.id_url = c.id_url
			where a.id_content = $id";
        $this->db->execute($query);

    }

    function getSettings() {
        return $this->db->get_row("select * from settings");
    }
    
    function getBlocks() {
    	return $this->db->get_rows("select * from content where type = 'block' order by id_content");
    }
}
?>