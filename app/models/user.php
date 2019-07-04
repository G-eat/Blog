<?php

/**
 *  Post
 */
class User extends Database {
  public $errors=[];

  // if is set remmember me log in
  public function isSetRemmember_me() {
    $cookie = $_COOKIE['remmember_me'] ?? false;

    if ($cookie) {
      $data = Database::select(['*'],['remmember_me'],[['token_hash','LIKE',"'".$cookie."'"]]);
      $isExpireToken = User::isExpireToken($data[0]['expire_at']);
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

  // login post
  public function logIn($password,$username,$remmeberme) {
    User::validatelogin($password,$username);
    if ($this->errors == null) {

      session_regenerate_id(true);

      $_SESSION['user'] = $username;
      if ($remmeberme == 1) {
        USER::remmmemberLogin($_POST['username']);
      }
      Controller::redirect('/user/login/success');
    } else {
      return $this->errors;
    }
  }

  // validate login
  public function validateLogin($password,$username) {
    $password1 = md5($password);
    $mysql = 'SELECT COUNT(*) FROM `users` WHERE `username` = "'.$username.'" AND `password` ="'. $password1.'" AND `token` =1';
    $data = Database::raw($mysql);

    if ($data[0] !== '1' ) {
      $this->errors[] ='Incorrect Password or you not verify your email.';
    }
  }

  // remmember me
  public function remmmemberLogin($username) {
    $token = new Token();
    $hash_token = $token->getHash();

    $expiry_token = time() + 60 * 60 * 24 * 7;
    $expire_at = date('Y-m-d H:i:s',$expiry_token);

    setcookie('remmember_me' , $hash_token , $expiry_token , '/');
    Database::insert(['remmember_me'],['token_hash','user_name','expire_at'],["'".$hash_token."'","'".$username."'","'".$expire_at."'"]);
    $_SESSION['user'] = $username;
    Controller::redirect('/user/login');
  }

  // register post
  public function save($password,$confirmpassword,$username,$email){
    User::validateRegister($password,$confirmpassword,$username,$email);
    if ($this->errors == null) {
      $md5password = md5($password);

      $token = User::generateRandomString();

      $data = Database::insert(['users'],['username','email','password','token'],["'".$username."'","'".$email."'","'".$md5password."'","'".$token."'"]);

      User::sendMail($username,$email,$token);

      Controller::redirect('/user/login');
    } else {
      return $this->errors;
    }
  }

  // validate register
  public function validateRegister($password,$confirmpassword,$username,$email) {
    $mysql = 'SELECT COUNT(*) FROM `users` WHERE `username` = '."'".$username."'";
    $mysql2 = 'SELECT COUNT(*) FROM `users` WHERE `email` = '."'".$email."'";

    $data = Database::raw($mysql);
    $data2 = Database::raw($mysql2);

    if ($data[0] == 1) {
      $this->errors[] ='This username is used.';
    }

    if ($data2[0] == 1) {
      $this->errors[] ='This email is used.';
    }

    if ($confirmpassword === $password) {
      if (strlen($password) >= 20 || strlen($password) <= 7) {
        $this->errors[] ='Your password must have between 8-16 characters.';
      }
    } else {
      $this->errors[] ='Not same Password-Confirm Password.';
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
    $mysql = 'SELECT COUNT(*) FROM `users` WHERE `token` ='."'".$token."'".' AND `username` = '."'".$username."'";
    // $data = USER::confirmToken($mysql,$token,$username);
    $data = Database::raw($mysql);

    if ($data[0] == 1) {
      Database::update(['users'],[['token','=','1']],[['username','=',"'".$username."'"]]);
      session_regenerate_id(true);

      $_SESSION['user'] = $username;
      Controller::redirect('/user/login/success');
    } else {
      Controller::redirect('/user/login/error');
    }
  }

  public function confirmToken($mysql,$token,$username) {
    self::connect();
    $query = self::$db->prepare($mysql);
    $query->execute([$token,$username]);
    return  $query->fetch();
  }

    //reset password form to get email
    public function reset() {
      $token = User::generateRandomString();
      $data = Database::select(['*'],['users'],[['email','LIKE',"'".$_POST['email']."'"]]);
      if ($data[0]['username']) {
        User::sendResetMail($data[0]['username'],$data[0]['email'],$token);
        Database::insert(['reset_password'],['user_name','reset_token'],["'".$data[0]['username']."'","'".$token."'"]);
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
      $data = Database::select(['*'],['reset_password'],[['reset_token','LIKE',"'".$token."'"]]);
      return $data[0];
    }

    //reset password see if user exists
    public function userExist($username) {
      $data = Database::select(['*'],['users'],[['username','LIKE',"'".$username."'"]]);
      return $data[0];
    }

    public function exist($mysql,$token) {
      self::connect();
      $query = self::$db->prepare($mysql);
      $query->execute([$token]);
      $data = $query->fetch();
      return $data;
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



  // // confirma email with link
  // public function confirmationemail($username,$password,$token) {
  //   $mysql = 'SELECT COUNT(*) FROM `users` WHERE `username` = ? AND `password` = ? AND `token` = ?';
  //   $data = USER::confirm($mysql,$username,$password,$token);
  //
  //   if ($data[0] == 1) {
  //     $mysql = 'UPDATE `users` SET `token`= 1 WHERE `username` = ?';
  //     User::updatetokentrue($mysql,$username);
  //   } else {
  //     echo 'Error';
  //   }
  // }
  //
  // public function confirm($mysql,$username,$password,$token) {
  //   self::connect();
  //   $query = self::$db->prepare($mysql);
  //   $query->execute([$username,$password,$token]);
  //   return  $query->fetch();
  // }
  //
  // public function updatetokentrue($mysql,$username) {
  //   self::connect();
  //   $query = self::$db->prepare($mysql);
  //   $query->execute([$username]);
  //
  //   session_regenerate_id(true);
  //
  //   $_SESSION['user'] = $username;
  //   Controller::redirect('/user/login/success');
  // }


}
