<?php
namespace libs;

class specific {
    protected $db;

    static function getPostType($type) {
        switch($type) {
            case "post": $t = "Blog Post"; break;
            case "image": $t = "Blog Image"; break;
            case "recipe": $t = "Recipe"; break;
        }
        return $t;
    }
}
?>