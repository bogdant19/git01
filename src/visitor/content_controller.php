<?php
class content_controller extends app_controller {
    function __construct($name) {
        parent::__construct($name);
        $this->set("settings",$this->content->getSettings());
        $this->set("blocks",$this->content->getBlocks());
        if(!empty($this->params["id_content"]))
            $this->set("content",$this->content->getContent($this->params["id_content"]));
        
        $this->loadModel("gallery");
        $this->set("images",$this->gallery->getImages($id_gallery = null,$onlyActive = true));
    }

    function home() {
        if($this->isRequest()) {
            $p = $this->data["_p"];
            $s = $this->content->getSettings();
            $to = $s["contact_email"];
            //$to = "bogdant19@gmail.com";
            $subject = "New Message Received";
            $headers = "From: " . "sender@profixhardwood.com" . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $message = "<html><body>";
            $message .= "<b>Name: </b>".$p["name"]."<br/><br/>";
            $message .= "<b>Email: </b>".$p["email"]."<br/><br/>";
            $message .= "<b>Message: </b>".$p["message"];
            $message .= "</body></html>";
            mail($to, $subject, $message, $headers);
            $this->addMessage("Thank You For Your Message.");
            $this->addMessage("We Will Be In Contact With Your Shortly");
        }
    }

}
?>