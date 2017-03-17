<?php
namespace libs;

function escape($data) {
    if(is_array($data))
        foreach($data as $k => $v)
            $data[$k] = escape($v);
    elseif(gettype($data)=='string')
            return str_replace("\'","\\\'",$data);
    return $data;
}

function strip($data) {
    if(is_array($data))
        foreach($data as $k => $v)
            $data[$k] = strip($v);
    elseif(gettype($data)=='string')
            return str_replace("\\\'","\'",$data);
    return $data;
}

function addtext($text, $insert, $position, $tag) {
    if(!in_array($position,array('after','before','replaceBefore')))
        return false;
    $tag_position = strpos($text, $tag);
    if($tag_position===false)
        return $text;
    $new_text = "";
    if($position=='after')
        $new_text .= substr($text,0,$tag_position).$tag;
    elseif($position=='before')
        $new_text .= substr($text,0,$tag_position);
    elseif($position=='replaceBefore');
    $new_text .= $insert;
    if($position=='after')
        $new_text .= substr($text,$tag_position+strlen($tag),strlen($text)-$tag_position-strlen($tag));
    elseif(in_array($position,array('before','replaceBefore')))
        $new_text .= $tag." ".substr($text,$tag_position+strlen($tag),strlen($text)-$tag_position-strlen($tag));
    return $new_text;
}

function data_format($datetime, $format) {
    $mydatetime = date_create($datetime);
    return date_format($mydatetime, $format);
}

function set_checkbox(&$val) {
    $val=$val===null?0:$val;
}

function random_string($length=10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i=0;$i<$length;$i++)
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    return $randomString;

}