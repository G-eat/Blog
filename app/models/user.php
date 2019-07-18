<?php

namespace App\Models;

use App\Core\Controller;
use App\Database\Database;
use App\Core\Data;
use App\Core\Message;
use App\Core\Token;
use App\Controllers\UserController;

/**
 *  Post
 */
class User {
  public $errors=[];

  // if is set remmember me log in
  public function isSetRemmember_me() {
    $database = new Database();

    $cookie = $_COOKIE['remmember_me'] ?? false;

    if ($cookie) {
      $data = $database->select(['*'],['remmember_me'],[['token_hash','LIKE',"'".$cookie."'"]]);
      $data_admin = $database->select(['*'],['users'],[['username','LIKE',"'".$data[0]['user_name']."'"],['AND'],['admin','=','1']]);
      $isExpireToken = $this->isExpireToken($data[0]['expire_at']);

      if ($data_admin[0]['username'] !== ''  && !$isExpireToken) {
        $_SESSION['admin'] = $data_admin[0]['username'];
      }

      if ( $data[0]['user_name'] !== ''  && !$isExpireToken) {
         $_SESSION['user'] = $data[0]['user_name'];
      } else {
         UserController::logout();
      }
    }
  }

  public function isExpireToken($expire_at) {
    return strtotime($expire_at) < time();
  }

  // login user
  public function logIn($password,$username,$remmeberme) {
    $database = new Database();

    $this->validatelogin($password,$username);

    $data_admin = $database->select(['*'],['users'],[['username','LIKE',"'".$username."'"],['AND'],['admin','=','1']]);

    if ($this->errors == null) {

      session_regenerate_id(true);

      $_SESSION['user'] = $username;
      //if is admin set session admin to his name
      if ($data_admin[0]['username'] !== '') {
        $_SESSION['admin'] = $data_admin[0]['username'];
      }

      if ($remmeberme == 1) {
        $this->remmmemberLogin($_POST['username']);
      }

      Controller::redirect('/post/index');
    } else {
      return $this->errors;
    }
  }

  // validate login
  public function validateLogin($password,$username) {
    $database = new Database();

    $password1 = md5($password);

    $mysql = 'SELECT COUNT(*) FROM `users` WHERE `username` = "'.$username.'" AND `password` ="'. $password1.'" AND `token` =1';
    $data = $database->raw($mysql);

    if ($data[0] !== '1' ) {
      $this->errors[] ='Incorrect Password or you not verify your email.';
    }
  }

  // remmember me
  public function remmmemberLogin($username) {
    $token = new Token();
    $database = new Database();

    $hash_token = $token->getHash();

    $expiry_token = time() + 60 * 60 * 24 * 7;
    $expire_at = date('Y-m-d H:i:s',$expiry_token);

    setcookie('remmember_me' , $hash_token , $expiry_token , '/');

    $database->insert(['remmember_me'],['token_hash','user_name','expire_at'],["'".$hash_token."'","'".$username."'","'".$expire_at."'"]);
    $data_admin = $database->select(['*'],['users'],[['username','LIKE',"'".$username."'"],['AND'],['admin','=','1']]);
    //if is admin set session admin to his name
    if ($data_admin[0]['username'] !== '') {
      $_SESSION['admin'] = $data_admin[0]['username'];
    }
    $_SESSION['user'] = $username;
    Controller::redirect('/post/index');
  }

  // register user /create
  public function create(){
    $database = new Database();
    $message = new Message();
    $data = new Data();

    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $this->validateRegister($password,$confirmpassword,$username,$email);

    if ($this->errors == null) {
      $md5password = md5($password);

      $token = $this->generateRandomString();

      $data = $database->insert(['users'],['username','email','password','token'],["'".$username."'","'".$email."'","'".$md5password."'","'".$token."'"]);

      $this->sendMail($username,$email,$token);

      $message->setMsg('You need to verify with email.','success');
      Controller::redirect('/user/login');
    } else {
        $data->setData($_POST['username'],'username');
        $data->setData($_POST['email'],'email');

        Controller::redirect('/user/register');
    }
  }

  // validate register
  public function validateRegister($password,$confirmpassword,$username,$email) {
    $database = new Database();
    $message = new Message();

    $mysql = 'SELECT COUNT(*) FROM `users` WHERE `username` = '."'".$username."'";
    $mysql2 = 'SELECT COUNT(*) FROM `users` WHERE `email` = '."'".$email."'";

    $data = $database->raw($mysql);
    $data2 = $database->raw($mysql2);

    if ($data[0] == 1) {
      $this->errors[] ='This username is used.';
      $message->setMsg('This username is use.','error');
    }

    if ($data2[0] == 1) {
      $this->errors[] ='This email is used.';
      $message->setMsg('This email is use.','error1');
    }

    if ($confirmpassword === $password) {
      if (strlen($password) >= 20 || strlen($password) <= 7) {
        $this->errors[] ='Your password must have between 8-16 characters.';
        $message->setMsg('Your password must have between 8-16 characters.','error2');
      }
    } else {
      $this->errors[] ='Not same Password-Confirm Password.';
      $message->setMsg('Not same Password-Confirm Password.','error3');
    }
  }


  // sendMail
  public function sendMail($username,$email,$token) {
    $to = $email;
    $subject = "Confirm your Email";

    $message = "
    <html>
      <head>
      <title>HTML email</title>
      </head>
      <body>
      <p>This email is for confirmation your email in Blog!</p>
      <br>
      <p>Hello, <b>".$username.".</b></p>
      <br>
      <p>Please go to this link <span><a href='http://localhost:8000/user/confirmation/".$username.'/'.$token."'>here</a></span> and logIn.</p>
      </body>
    </html>
    ";

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Blog Registration <27dhjetor@gmail.com>';
    $headers[] = 'Cc: 27dhjetor@gmail.com';
    $headers[] = 'Bcc: 27dhjetor@gmail.com';

    // Mail it
    mail($to, $subject, $message, implode("\r\n", $headers));
  }

  //confirm email with link
  public function confirmationToken($username,$token) {
    $database = new Database();

    $mysql = 'SELECT COUNT(*) FROM `users` WHERE `token` ='."'".$token."'".' AND `username` = '."'".$username."'";
    $data = $database->raw($mysql);

    if ($data[0] == 1) {
      $database->update(['users'],[['token','=','1']],[['username','=',"'".$username."'"]]);

      session_regenerate_id(true);

      $data_admin = $database->select(['*'],['users'],[['username','LIKE',"'".$username."'"],['AND'],['admin','=','1']]);
      //if is admin set session admin to his name
      if ($data_admin[0]['username'] !== '') {
        $_SESSION['admin'] = $data_admin[0]['username'];
      }

      $_SESSION['user'] = $username;
      Controller::redirect('/post/index/success');
    } else {
      Controller::redirect('/post/index/error');
    }
  }

    //reset password form to get email
    public function reset() {
      $database = new Database();

      $token = $this->generateRandomString();

      $data = $database->select(['*'],['users'],[['email','LIKE',"'".$_POST['email']."'"]]);

      if ($data[0]['username']) {
        $this->sendResetMail($data[0]['username'],$data[0]['email'],$token);
        $database->insert(['reset_password'],['user_name','reset_token'],["'".$data[0]['username']."'","'".$token."'"]);
      }
    }

    // sendResetMail
    public function sendResetMail($username,$email,$token) {
      $to = $email;
      $subject = "Reset your Password";

      $message = "
      <html>
        <head>
        <title>HTML email</title>
        </head>
        <body>
        <p>This email is for reseting your password in Blog!</p>
        <br>
        <p>Hello, <b>".$username.".</b></p>
        <br>
        <p>Please go to this link <span><a href='http://localhost:8000/user/resetpassword/".$token.'/'.$username."'>here</a></span> to reset Password.</p>
        </body>
      </html>
      ";

      // To send HTML mail, the Content-type header must be set
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html; charset=iso-8859-1';

      // Additional headers
      $headers[] = 'From: Reset Password <27dhjetor@gmail.com>';
      $headers[] = 'Cc: 27dhjetor@gmail.com';
      $headers[] = 'Bcc: 27dhjetor@gmail.com';

      // Mail it
      mail($to, $subject, $message, implode("\r\n", $headers));
    }

    //reset password see if token exists
    public function tokenExist($token) {
      $database = new Database();

      $data = $database->select(['*'],['reset_password'],[['reset_token','LIKE',"'".$token."'"]]);

      return $data[0];
    }

    //reset password see if user exists
    public function userExist($username) {
      $database = new Database();

      $data = $database->select(['*'],['users'],[['username','LIKE',"'".$username."'"]]);

      return $data[0];
    }

    // validate reset password passwords
    public function validate($confirmpassword,$password) {
      if ($confirmpassword === $password) {
        if (strlen($password) >= 20 || strlen($password) <= 7) {
          Controller::redirect('/user/login/error');
        } else {
          return md5($password);
        }
      }
    }

    // random text
    public function generateRandomString($length = 10) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';

      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }

      return $randomString;
    }

    public function deleteCookie($cookie) {
        $database = new Database();
        return $database->delete(['remmember_me'],[['token_hash','LIKE',"'".$cookie."'"]]);
    }


}
