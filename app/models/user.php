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
      $mysql = 'SELECT * FROM `remmember_me` WHERE `token_hash` LIKE ?';
      $data = User::select($mysql,$cookie);
      $isExpireToken = User::isExpireToken($data['expire_at']);
      if ( $data['user_name'] !== ''  && !$isExpireToken) {
        $_SESSION['user'] = $data['user_name'];
      }
    }
  }

  public function isExpireToken($expire_at) {
    return strtotime($expire_at) < time();
  }

  // queries in database user
  public function select($mysql , $data = array()) {
    self::connect();
    $query = self::$db->prepare($mysql);
    if (!empty($data)) {
      $query->execute([$data]);
    } else {
      $query->execute();
    }
    $data = $query->fetch();
    return $data;
  }

  public function delete($mysql , $data = array()) {
    self::connect();
    $query = self::$db->prepare($mysql);
    if (!empty($data)) {
      $query->execute([$data]);
    } else {
      $query->execute();
    }
    $data = $query->fetchAll();
    Controller::redirect('/user/login');
    return $data;
  }

  public function insert($mysql , $username,$email,$password,$token) {
    self::connect();
    $query = self::$db->prepare($mysql);
    $query->execute([$username,$email,$password,$token]);
    // Controller::redirect('/user/login');
  }

  public function isRegister($mysql,$username,$password) {
    self::connect();
    $query = self::$db->prepare($mysql);
    $query->execute([$username,$password]);
    $data = $query->fetch();
    return $data;
  }

  // register post
  public function save($password,$confirmpassword,$username,$email){
    User::validateRegister($password,$confirmpassword,$username,$email);
    if ($this->errors == null) {
      $mysql = 'INSERT INTO `users`(`username`,`email`,`password`,`token`) VALUES (?,?,?,?)';
      $md5password = md5($password);

      $token = User::generateRandomString();

      User::insert($mysql,$username,$email,$md5password,$token);

      User::sendMail($username,$email,$token);

      Controller::redirect('/user/login');
    } else {
      return $this->errors;
    }
  }

  // validate register
  public function validateRegister($password,$confirmpassword,$username,$email) {
    $mysql = 'SELECT COUNT(*) FROM `users` WHERE `username` = ? OR `email` = ?';
    $data = USER::selectvalidate($mysql,$username,$email);

    if ($data[0] == 1) {
      $this->errors[] ='This username and email is used.';
    }

    if ($confirmpassword === $password) {
      if (strlen($password) >= 20 || strlen($password) <= 7) {
        $this->errors[] ='Your password must have between 8-16 characters.';
      }
    } else {
      $this->errors[] ='Not same Password-Confirm Password.';
    }
  }

  public function selectvalidate($mysql,$username,$email) {
    self::connect();
    $query = self::$db->prepare($mysql);
    $query->execute([$username,$email]);
    $data = $query->fetch();
    return $data;
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

  // remmember me
  public function remmmemberLogin($username) {
    $token = new Token();
    $hash_token = $token->getHash();

    $expiry_token = time() + 60 * 60 * 24 * 7;

    setcookie('remmember_me' , $hash_token , $expiry_token , '/');
    $mysql = 'INSERT INTO `remmember_me`(`token_hash`,`user_name`,`expire_at`) VALUES(?,?,?)';
    USER::remmember_me($mysql,$hash_token,$username,$expiry_token);
  }

  public function remmember_me($mysql,$hash_token,$username,$expiry_token) {
    self::connect();
    $query = self::$db->prepare($mysql);
    $query->execute([$hash_token,$username,date('Y-m-d H:i:s' , $expiry_token)]);
    $_SESSION['user'] = $username;
    Controller::redirect('/user/login');
  }

  // validate login
  public function validateLogin($password,$username) {
    $password1 = md5($password);
    $mysql = 'SELECT COUNT(*) FROM `users` WHERE `username` = ? AND `password` = ? AND `token` =1';
    $data = USER::isRegister($mysql,$username,$password1);

    if ($data[0] !== '1' ) {
      $this->errors[] ='Incorrect Password or you not verify your email.';
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
    $mysql = 'SELECT COUNT(*) FROM `users` WHERE `token` = ? AND `username` = ?';
    $data = USER::confirmToken($mysql,$token,$username);

    if ($data[0] == 1) {
      $mysql = 'UPDATE `users` SET `token`= 1 WHERE `username` = ?';
      User::updatetokentrue($mysql,$username);
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

  public function updatetokentrue($mysql,$username) {
    self::connect();
    $query = self::$db->prepare($mysql);
    $query->execute([$username]);

    session_regenerate_id(true);

    $_SESSION['user'] = $username;
    Controller::redirect('/user/login/success');
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

  public function insertReset($mysql,$username,$token) {
    self::connect();
    $query = self::$db->prepare($mysql);
    $query->execute([$username,$token]);
  }

  public function tokenExist($token) {
    $mysql = 'SELECT * FROM `reset_password` WHERE `reset_token` LIKE ?';
    $data = User::exist($mysql,$token);
    return $data;
  }

  public function exist($mysql,$token)
  {
    self::connect();
    $query = self::$db->prepare($mysql);
    $query->execute([$token]);
    $data = $query->fetch();
    return $data;
  }

  public function reset() {
    $token = User::generateRandomString();
    $mysql = 'SELECT * FROM `users` WHERE `email` LIKE ?';
    $data = User::select($mysql,$_POST['email']);
    if ($data[1]) {
      User::sendResetMail($data[1],$data[2],$token);
      $mysql = 'INSERT INTO `reset_password`(`user_name`,`reset_token`) VALUES(?,?)';
      User::insertReset($mysql,$data[1],$token);
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

  public function updatePass($mysql,$validate,$username) {
    self::connect();
    $query = self::$db->prepare($mysql);
    $query->execute([$validate,$username]);

    Controller::redirect('/user/login/ok');
  }

  public function deleteResetPassToken($mysql,$token) {
    self::connect();
    $query = self::$db->prepare($mysql);
    $query->execute([$token]);
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

}
