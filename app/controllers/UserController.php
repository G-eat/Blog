<?php

/**
 * homeController
 */
class UserController extends Controller {

  // public function select($order = '')
  // {
  //   // $username = "'andi'";
  //   $data = Database::select(['*'],['users'],null,null,null,[2]);
  //   var_dump($data);
  // }

  // public function update()
  // {
  //   $username = "andi";
  //   $data = Database::update(['users'],[['token','=','1']],[['username','=',"'".$username."'"]]);
  //   var_dump($data);
  // }

  // public function delete()
  // {
  //   $data = Database::delete1(['users'],[['token','=','1'],['OR'],['dsa','=','a']]);
  //   var_dump($data);
  // }


  public function login($msg = '') {
    User::isSetRemmember_me();
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
      // register method post
      if (isset($_POST['submit'])) {
        $user = new User;
        $user->save($_POST['password'],$_POST['confirmpassword'],$_POST['username'],$_POST['email']);
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
      User::confirmationToken($username,$token);
    }
  }

  //reset password form to get email
  public function reset() {
    //reset method Post
    if (isset($_POST['email'])) {
      User::reset();
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
    $tokenExist = User::tokenExist($token);
    $userExist = User::userExist($username);
    //after posting to rememmber $token and $username
    if(!$tokenExist['reset_token'] || !$userExist['username']) {
      //if isset $_POST
      if (isset($_POST['submit'])) {
          $validate = User::validate($_POST['confirmpassword'],$_POST['password']);
          $username = $_POST['hidden'];
          $token = $_POST['hiddenToken'];
          //if not valid return to same link to try again
          if ($validate == '') {
            Controller::redirect('/user/resetpassword/'.$token.'/'.$username.'/error');
          }
          Database::delete1(['reset_password'],[['reset_token','=',"'".$token."'"]]);
          Database::update(['users'],[['password','=',"'".$validate."'"]],[['username','=',"'".$username."'"]]);
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


  //confirm email with form
  // public function confirmationemail() {
  //   $username = $_POST['username'];
  //   $password = md5($_POST['password']);
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
      // delete  cookie
      $cookie = $_COOKIE['remmember_me'];
      setcookie('remmember_me','',time() - 3600,'/');
      Database::delete(['remmember_me'],[['token_hash','LIKE',"'".$cookie."'"]]);
      }

    // Finally, destroy the session.
    session_destroy();

    Controller::redirect('/user/login');
  }

}
