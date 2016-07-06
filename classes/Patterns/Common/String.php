<?php
/**
 * Created by PhpStorm.
 * User: Administrator PC
 * Date: 9/29/2015
 * Time: 10:20 AM
 */

namespace Patterns\Common;


class String
{
    public $content;

    public function __construct($content = '') {
        if($content) {
            $this->content = $content;
        }
    }
    public function __toString() {
        return $this->content;
    }
    public function __invoke() {
        return $this->content;
    }

    public function cleanup() {
        $this->content = htmlspecialbo($this->content);
        return $this;
    }
}