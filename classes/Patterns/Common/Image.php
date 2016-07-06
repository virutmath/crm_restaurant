<?php
/**
 * Created by PhpStorm.
 * User: Administrator PC
 * Date: 9/29/2015
 * Time: 10:03 AM
 */

namespace Patterns\Common;


class Image
{
    var $name;
    public function __construct($name = '') {
        $this->name = $name;
    }
    public function setName($image_name = '') {
        $this->name = $image_name;
    }
    public function getImagePath() {
        return get_picture_path($this->name);
    }
}