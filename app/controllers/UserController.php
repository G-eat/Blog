<?php

/**
 * homeController
 */
class UserController extends Controller {

  public function login($msg = '') {
    if (isset($_POST['password'])) {
      $user = new User;
      $remmeberme = isset($_POST['remmember_me']);
      $user->logIn($_POST['password'],$_POST['username'],$remmeberme);
      $this->view('user\index',[
        'page' => 'LogIn',
        'error' => $user->errors,
        'username' => $_POST['username'],
        'msg' => ''
      ]);
      $this->view->render();
    } else {
      $this->view('user\index',[
        'page' => 'LogIn',
        'error' => '',
        'msg' => $msg
      ]);
      $this->view->render();
    }
  }

  public function register() {
      if (isset($_POST['passw'])) {
        $user = new User;
        $user->save($_POST['passw'],$_POST['conpassw'],$_POST['username'],$_POST['email']);
        $this->view('user\register',[
          'page' => 'Register',
          'error' => $user->errors,
          'username' => $_POST['username'],
          'email' => $_POST['email']
        ]);
        $this->view->render();
      } else {
        $this->view('user\register',[
          'page' => 'Register',
          'error' => ''
        ]);
        $this->view->render();
      }
  }

  //confirm email with link
  public function confirmation($username = '',$token='') {
    if ($token == '' || $username == '') {
      Controller::redirect('/user/login');
    } else {
      echo $username;
      User::confirmationToken($username,$token);
    }
  }

  //confirm email with form
  // public function confirmationemail() {
  //   $username = $_POST['username'];
  //   $password = md5($_POST['passw']);
  //   $token = $_POST['token'];
  //
  //   $isCoonfirmation = User::confirmationemail($username,$password,$token);
  //   echo $isCoonfirmation;
  // }

  public function logOut() {
    // If you are using session_name("something"), don't forget it now!
    session_start();

    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();

    Controller::redirect('/user/login');
  }

}
