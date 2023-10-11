<?php

// File:    socialclass.php
// Web:  http://blog.unijimpe.net
// Date:    13/03/2010
 
class SocialClass {
    var $url;
    var $title;
    var $target;
    var $type;
   
    function SocialClass($_url, $_title, $_target = "", $_type = "link") {
        $this->url = urlencode($_url);
        $this->title = urlencode($_title);
        $this->setTarget($_target);
        $this->setType($_type);
    }
    function setTarget($_target) {
        if ($_target != "") {
            $this->target = " target=\"".$_target."\"";
        }
    }
    function setType($_type) {
        $this->type = $_type;
    }
    function write($path, $label = "") {
        if ($this->type == "link") {
            return "<a href=\"".$path."\"".$this->target.">".$label."</a>";
        } else {
            return $path;
        }
    }
   
    function delicious($label = "Delicious") {
        $path = "http://delicious.com/save?v=5&url=".$this->url."&title=".$this->title;
        return $this->write($path, $label);
    }
    function digg($label = "Digg") {
        $path = "http://digg.com/submit?url=".$this->url."&amp;title=".$this->title;
        return $this->write($path, $label);
    }
    function facebook($label = "Facebook") {
        $path = "http://www.facebook.com/sharer.php?u=".$this->url."&t=".$this->title;
        return $this->write($path, $label);
    }
    function technorati($label = "Technorati") {
        $path = "http://technorati.com/faves?add=".$this->url."&title=".$this->title;
        return $this->write($path, $label);
    }
    function twitter($label = "Twitter") {
        $path = "http://twitter.com/home?status=".$this->title." - ".$this->url;
        return $this->write($path, $label);
    }
    function meneame($label = "Meneame") {
        $path = "http://meneame.net/submit.php?url=".$this->url;
        return $this->write($path, $label);
    }
    function reddit($label = "Reddit") {
        $path = "http://reddit.com/submit?url=".$this->url."&title=".$this->title;
        return $this->write($path, $label);
    }
    function stumbleupon($label = "StumbleUpon") {
        $path = "http://www.stumbleupon.com/submit?url=".$this->url."&title=".$this->title; 
        return $this->write($path, $label);
    }
    function buzz($label = "Google Buzz") {
        $path = "http://www.google.com/reader/link?url=".$this->url."&title=".$this->title; 
        return $this->write($path, $label);
    }
}








?>