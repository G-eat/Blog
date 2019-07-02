<?php

/**
 * homeController
 */
class UserController extends Controller {

  public function login($msg = '') {
    User::isSetRemmember_me();
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

  public function reset() {
    if (isset($_POST['email'])) {
      User::reset();
      $this->view('user\reset',[
        'success' => 'Your get the info from email.'
      ]);
      $this->view->render();
    } else {
      $this->view('user\reset',[]);
      $this->view->render();
    }
  }

  public function resetpassword($token='',$username='',$error = '') {
    $tokenExist = User::tokenExist($token);
    if(!$tokenExist[2]) {
      if (isset($_POST['password'])) {
          $validate = User::validate($_POST['confirmpassword'],$_POST['password']);
          $username = $_POST['hidden'];
          $token = $_POST['hiddenToken'];
          if ($validate == '') {
            Controller::redirect('/user/resetpassword/'.$token.'/'.$username.'/error');
          }
        } else {
          Controller::redirect('/user/login/error');
        }
    }

    if (isset($_POST['password'])) {
        $validate = User::validate($_POST['confirmpassword'],$_POST['password']);
        $username = $_POST['hidden'];
        $token = $_POST['hiddenToken'];
        if ($validate == '') {
          Controller::redirect('/user/resetpassword/'.$token.'/'.$username);
        }
        $mysql = 'UPDATE `users` SET `password` =? WHERE `username` = ?';
        User::updatePass($mysql,$validate,$username);
        // Controller::redirect('/user/login/4');
    }
    $this->view('user\resetpassword',[
      'username' => $username,
      'token' => $token,
      'error' => $error
    ]);
    $this->view->render();


    // if ($token == '') {
    //   Controller::redirect('/user/login/error');
    // }
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

    // delete cookies
    if (isset($_COOKIE['remmember_me'])) {
      $mysql = 'DELETE FROM `remmember_me` WHERE `token_hash` LIKE ?';
      $cookie = $_COOKIE['remmember_me'];
      // delete  cookie
      setcookie('remmember_me','',time() - 3600,'/');
      User::delete($mysql,$cookie);
      }

    // Finally, destroy the session.
    session_destroy();

    Controller::redirect('/user/login');
  }

}
