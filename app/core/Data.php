<?php

namespace App\Core;

class Data {

	public static function setData($text, $type) {
		$_SESSION[$type] = $text;
	}

	public static function display($type) {
        if (isset($_SESSION[$type])) {
            echo $_SESSION[$type];
            unset($_SESSION[$type]);
        }
	}


}
