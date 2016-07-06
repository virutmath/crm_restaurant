<?php
/**
 * Created by PhpStorm.
 * User: Administrator PC
 * Date: 9/29/2015
 * Time: 10:16 AM
 */

namespace Patterns\Common;

class Note
{
    var $content;
    public function __construct() {

    }
    public function setContent(String $content ) {
        $this->content = $content->cleanup()->content;
        return $this;
    }
}