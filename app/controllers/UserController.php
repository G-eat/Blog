<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Database\Database;
use App\Models\User;

/**
 * homeController
 */
class UserController extends Controller {
    public function __construct($params = null) {
       $user = new User();

       $user->isSetRemmember_me();

       $this->params = $params;
       $this->model = 'App\Models\User';
       parent::__construct($params);
    }

  public function login($msg = '') {
    //login method POST
    if (isset($_POST['submit'])) {
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
      //login method get
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
    $this->view('user\register',[
        'page' => 'Register',
        'error' => ''
    ]);
    $this->view->render();
  }

  //confirm email with link
  public function confirmation($username = '',$token='') {
        $user = new User();

        if ($token == '' || $username == '') {
          Controller::redirect('/post/index');
        } else {
          $user->confirmationToken($username,$token);
        }
  }

  //reset password form to get email
  public function reset() {
        $user = new User();

        //reset method Post
        if (isset($_POST['email'])) {
          $user->reset();

          $this->view('user\reset',[
              'success' => 'Your get the info from email.'
          ]);
          $this->view->render();
        //reset method get
        } else {
          $this->view('user\reset',[]);
          $this->view->render();
        }
  }

  //reset password
  public function resetpassword($token='',$username='',$error = '') {
        $user = new User();
        $database = new Database();

        $tokenExist = $user->tokenExist($token);
        $userExist = $user->userExist($username);

        //after posting to rememmber $token and $username
        if(!$tokenExist['reset_token'] || !$userExist['username']) {
          //if isset $_POST
          if (isset($_POST['submit'])) {
            $validate = $user->validate($_POST['confirmpassword'],$_POST['password']);
            $username = $_POST['hidden'];
            $token = $_POST['hiddenToken'];

            //if not valid return to same link to try again
            if ($validate == '') {
              Controller::redirect('/user/resetpassword/'.$token.'/'.$username.'/error');
            }

            $database->delete(['reset_password'],[['reset_token','=',"'".$token."'"]]);
            $database->update(['users'],[['password','=',"'".$validate."'"]],[['username','=',"'".$username."'"]]);

            Controller::redirect('/user/login/ok');
          } else {
            Controller::redirect('/user/login/error');
          }
        }
        //mothod Get
        $this->view('user\resetpassword',[
            'username' => $username,
            'token' => $token,
            'error' => $error
        ]);
        $this->view->render();
  }

  public static function logOut() {
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
      // delete  cookie
      $cookie = $_COOKIE['remmember_me'];
      setcookie('remmember_me','',time() - 3600,'/');
      $user = new User();
      $user->deleteCookie($cookie);
      }

    // Finally, destroy the session.
    session_destroy();

    Controller::redirect('/post/index');
  }

}
