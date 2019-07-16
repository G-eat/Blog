<?php

// Error messages
if (isset($_SESSION['error'])) {
    Message::display('error');
}

if (isset($_SESSION['error1'])) {
    Message::display('error1');
}

if (isset($_SESSION['error2'])) {
    Message::display('error2');
}

if (isset($_SESSION['error3'])) {
    Message::display('error3');
}

// Success messages
if (isset($_SESSION['success'])) {
    Message::display('success');
}
