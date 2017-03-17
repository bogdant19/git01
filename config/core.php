<?php
namespace core;

class DBCONFIG {

    static $default = array(
        'driver' => 'mysql',
        'host' => 'localhost',
        'login' => 'profixha_user',
        'password' => 'Tokyo712!',
        'database' => 'profixha_db'
    );

    static $remote = array(
        'driver' => 'mysql',
        'host' => 'localhost',
        'login' => '',
        'password' => '',
        'database' => ''
    );

    static function get($connection) {
        return self::${$connection};
    }

}

class UPLOADERCONFIG {

    static $properties = array(
        'allowedExtensions'    => 'pdf,doc,docx,rtf,odt,xls,xlsx,gif,png,jpg,jpeg,csv,txt',
        'imageSizes' => array(
            'banner_thumb' => array(200,200)
        )
    );

    static function get() {
        return self::$properties;
    }
}