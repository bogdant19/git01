<?php
class settings_controller extends app_controller {
    function settings_general() {
        $this->data["_p"] = $this->settings->getSettings();
        if($this->isRequest()) {
            $this->settings->saveSettings($this->data["p"]);
            $this->addMessage("Setting have been succesfully saved!");
            $this->redirect($this->backUrl(array(55)));
        }
    }
}
?>