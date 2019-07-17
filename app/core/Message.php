<?php

namespace App\Core;

class Message {

	public static function setMsg($text ='', $type = '') {
		if ($text !== '' || $type == '') {
			$_SESSION[$type] = $text;
		}
	}

	public static function display($type) {
		if (isset($_SESSION[$type]) && $type !== 'success') {
            echo '<div class="alert alert-danger container">'.$_SESSION[$type].'</div>';
            unset($_SESSION[$type]);
        } else {
			echo '<div class="alert alert-success container">'.$_SESSION[$type].'</div>';
            unset($_SESSION[$type]);
		}
	}

}
