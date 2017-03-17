<?php
namespace libs;

use core\UPLOADERCONFIG;

class uploader {

    static protected $instance;

    protected $properties;

    protected $db;

    protected $uploadedFile;

    protected $uploadFolder;

    protected $id;

    protected $filename;

    function __construct() {
        if(self::$instance===null) {
            $this->properties = UPLOADERCONFIG::get();
            self::$instance = $this;
            $this->db = \libs\db::get();
        }
    }

    static function get() {
        if (self::$instance === null)
            self::$instance = new self();
        return self::$instance;
    }

    function checkExtension($file=null) {
        if(!empty($file))
            $this->uploadedFile = $file;
        $extension = pathinfo($this->uploadedFile["name"],PATHINFO_EXTENSION);

        $this->id = null;
        $this->uploadFolder = null;
        $this->uploadedFile["extension"] = $extension;

        return in_array($extension,explode(',',$this->properties["allowedExtensions"]));
    }

    function checkImage($file=null) {
        if(!empty($file))
            $this->uploadedFile = $file;
        $this->checkExtension();
        $imageInfo = @getimagesize($file["tmp_name"]);
        if($imageInfo!==false) {
            $this->uploadedFile["isImage"] = true;
            $this->uploadedFile["width"] = $imageInfo[0];
            $this->uploadedFile["height"] = $imageInfo[1];
            return true;
        }
        return false;
    }

    function checkImageSize($cond,$width,$height) {
        // cond - hasMin, hasMax, eq
        if($cond=='eq' && $width==$this->uploadedFile["width"] && $height==$this->uploadedFile["height"])
            return true;
        elseif($cond=='hasMin' && $width<=$this->uploadedFile["width"] && $height<=$this->uploadedFile["height"])
            return true;
        elseif($cond=='hasMax' && $width>=$this->uploadedFile["width"] && $height>=$this->uploadedFile["height"])
            return true;
        return false;
    }

    function getUploadId() {
        if(!empty($this->id))
            return $this->id;
        if($this->uploadFolder===null)
            $this->setUploadFolder();
        $this->__setFilename();
        move_uploaded_file($this->uploadedFile["tmp_name"], $this->uploadFolder.$this->filename);

        $query = "
            insert into app_upload(filename,mime,is_image,di)
            values('".basename($this->uploadedFile["name"])."','".$this->uploadedFile["type"]."','".($this->uploadedFile["isImage"] === true ? 1 : 0)."',now())";
        $this->id = $this->db->execute($query);

        $path = str_replace("../data/uploads","",$this->uploadFolder.$this->filename);
        if($this->uploadedFile["isImage"]===true)
            $query = "
			  insert into app_upload_path (id_upload,size,width,height,path)
			  values('{$this->id}','orig','{$this->uploadedFile["width"]}','{$this->uploadedFile["height"]}','$path')";
        else
            $query = "insert into app_upload_path (id_upload,size,path) values('{$this->id}','orig','$path')";
        $this->db->execute($query);

        return $this->id;
    }

    function setUploadFolder($folder=null) {
        if($folder===null)
            $this->uploadFolder = "../data/uploads/".date("Y")."/".date("m")."/".date("d")."/";
        else
            $this->uploadFolder = $folder;
        if(!file_exists($this->uploadFolder))
            mkdir($this->uploadFolder, 0777, true);
        /* foreach($this->properties["imageSizes"] as $k => $v)
            if(!file_exists($this->uploadFolder.$k))
                mkdir($this->uploadFolder.$k, 0777, true); */
    }

    protected function __setFilename() {
        $this->filename = \libs\random_string(10);
        while(file_exists($this->uploadFolder.$this->filename))
            $this->filename = \libs\random_string(10);
    }

    function display($file, $width, $height, $cropped, $extension) {
        $imageCreateFunction = "imagecreatefrom".(strtolower($extension)=="jpg" ? "jpeg" : $extension);
        $img = $imageCreateFunction(APPROOT."/data/uploads".$file["path"]);
        if($cropped) {
            if( $file["width"] > $file["height"] && ( $file["width"] / $file["height"] ) > ( $width / $height ) ) {
                $start_y = 0;
                $start_x = intval(($file["width"] - $width*$file["height"]/$height) / 2);
            } else {
                $start_y = intval(($file["height"] - $height*$file["width"]/$width) / 2);
                $start_x = 0;
            }
            $thumb = imagecreatetruecolor($width, $height);
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            imagecopyresampled($thumb, $img, 0, 0, $start_x, $start_y, $width, $height, $file["width"] - 2*$start_x, $file["height"] - 2*$start_y);
        } else {
            if($file["height"]>=$file["width"] || ( $height>0 && $width == 0 ))
                $width = intval($height * $file["width"] / $file["height"]);
            else
                $height = intval($width * $file["height"] / $file["width"]);
            $thumb = imagecreatetruecolor($width, $height);
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            imagecopyresampled($thumb, $img, 0, 0, 0, 0, $width, $height, $file["width"], $file["height"]);
        }
        header("content-type: image/png");
        imagepng($thumb);
        imagedestroy($img);
        imagedestroy($thumb);
        die();
    }

}