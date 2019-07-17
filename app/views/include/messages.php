<?php

use App\Core\Message;

// Error messages
if (isset($_SESSION['error'])) {
    $message = new Message;
    $message->display('error');
}

if (isset($_SESSION['error1'])) {
    $message = new Message;
    $message->display('error1');
}

if (isset($_SESSION['error2'])) {
    $message = new Message;
    $message->display('error2');
}

if (isset($_SESSION['error3'])) {
    $message = new Message;
    $message->display('error3');
}

// Success messages
if (isset($_SESSION['success'])) {
    $message = new Message;
    $message->display('success');
}
