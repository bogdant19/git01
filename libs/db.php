<?php
namespace libs;

use core\DBCONFIG;

class db {

    static protected $instance;

    static $connID;

    protected $properties;

    protected $conn;

    protected $query;

    public function __construct($connID = 'default') {
        if(empty(self::$instance) || $connID!=self::$connID) {
            $this->properties = DBCONFIG::get($connID);
            $this->connect($this->properties);
            self::$connID = $connID;
            self::$instance = $this;
        }
    }

    public static function get($connID = 'default') {
        //print $connID;
        if (self::$instance === null || $connID != self::$connID) {
            self::$connID = $connID;
            self::$instance = new self($connID);
        }
        return self::$instance;
    }

    public function get_rows($query) {
        $sth = $this->__query($query);
        $rows = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $sth->closeCursor();
        return strip($rows);
    }

    public function get_row($query,$debug=false) {
        $sth = $this->__query($query);
        $row = $sth->fetch(\PDO::FETCH_ASSOC);
        $sth->closeCursor();
        if($debug) {
            var_dump(strip($row));
            die();
        }
        return strip($row);
    }

    public function get_page_rows($query, $pageNo, $rowsPerPage = 20) {
        $struct = array();
        if(empty($pageNo))
            $pageNo = 1;
        $tmp_query = addtext($query,'select count(1) as nr ','replaceBefore','from');
        $struct["rowsPerPage"] = $rowsPerPage;
        $struct["pageNo"] = $pageNo;
        $struct["nofRows"] = $this->get_cell($tmp_query, "nr");
        $struct["nofPages"] = $struct["nofRows"]%$rowsPerPage==0 ? intval($struct["nofRows"]/$rowsPerPage) : intval($struct["nofRows"]/$rowsPerPage)+1;
        if($struct["nofPages"]>0 && $struct["pageNo"]>$struct["nofPages"])
            return $this->get_page_rows($query,1,$rowsPerPage);
        $struct["rows"] = strip($this->get_rows($query." limit ".(($pageNo-1)*$rowsPerPage).",".$rowsPerPage));
        return $struct;
    }

    public function get_cell($query, $cell) {
        $sth = $this->__query($query);
        $row = $sth->fetch(\PDO::FETCH_ASSOC);
        $sth->closeCursor();
        return strip($row[$cell]);
    }

    public function execute($query) {
        $sth = $this->__query($query);
        $sth->closeCursor();
        return $this->conn->lastInsertId();
    }

    private function __query($query) {
        $this->query = $query;
        try {
            $sth = $this->conn->prepare($query);
            $sth->execute();
        } catch (\Exception $pe) {
            echo '<strong>QUERY:</strong> '.$query.'<br><br>';
            echo '<strong>ERROR:</strong> '.$pe->getMessage();
            die();
        }
        return $sth;
    }

    private function connect($cp) {
        switch($cp["driver"]) {
            case "mysql": {
                $this->conn = new \PDO("mysql:host={$cp["host"]};dbname={$cp["database"]}", $cp["login"], $cp["password"]);
                $this->conn->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
            } break;
            default:
                die('FATAL ERROR: unknown database driver');
        }
    }
}