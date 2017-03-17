<?php
class settings_model extends app_model {
    function getSettings() {
        return $this->db->get_row("select * from settings");
    }

    function saveSettings($vars) {
        $query = "
            update settings set contact_email = '{$vars["contact_email"]}',
              phone = '{$vars["phone"]}',
              facebook = '{$vars["facebook"]}',
              twitter = '{$vars["twitter"]}',
              gplus = '{$vars["gplus"]}',
              linkedin = '{$vars["linkedin"]}',
              pinterest = '{$vars["pinterest"]}',
              youtube = '{$vars["youtube"]}',
              analytics = '{$vars["analytics"]}'";
        $this->db->execute($query);
        
        $query = "update app_user set email = '{$vars["contact_email"]}' where id_user = 5";
        $this->db->execute($query);
    }
}
?>