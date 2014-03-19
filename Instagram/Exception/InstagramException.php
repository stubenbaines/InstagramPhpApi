<?php

/**
 * InstagramAPI
 */

namespace Instagram\Exception;

class InstagramException extends \Exception {
    public function __toString() {
        return "Instagram API: [{$this->code}] {$this->message} (" . __CLASS__ . ") ";
    }
}

