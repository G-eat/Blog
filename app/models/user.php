<?php

/**
 *  Post
 */
class User extends Database {
  public $errors=[];

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

  public function insert($mysql , $username,$email,$password) {
    self::connect();
    $query = self::$db->prepare($mysql);
    $query->execute([$username,$email,$password]);
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
    User::validateRegister($password,$confirmpassword,$username);
    if ($this->errors == null) {
      $mysql = 'INSERT INTO `users`(`username`,`email`,`password`) VALUES (?,?,?)';
      $md5password = md5($password);

      $random = User::generateRandomString();

      User::insert($mysql,$username,$email,$md5password);

      User::sendMail($username,$email,$random);

      Controller::redirect('/user/login');
    } else {
      return $this->errors;
    }
  }

  // validate register
  public function validateRegister($password,$confirmpassword,$username) {
    $mysql = 'SELECT COUNT(*) FROM `users` WHERE `username` = ?';
    $data = USER::select($mysql,$username);

    if ($data[0] == 1) {
      $this->errors[] ='This username is used.';
    }

    if ($confirmpassword === $password) {
      if (strlen($password) >= 20 || strlen($password) <= 7) {
        $this->errors[] ='Your password must have between 8-16 characters.';
      }
    } else {
      $this->errors[] ='Not same Password-Confirm Password.';
    }
  }

  // login post
  public function logIn($password,$username) {
    User::validatelogin($password,$username);
    if ($this->errors == null) {

      session_regenerate_id(true);

      $_SESSION['user'] = $username;
      Controller::redirect('/user/login/success');
    } else {
      return $this->errors;
    }
  }

  // validate login
  public function validateLogin($password,$username) {
    $password1 = md5($password);
    $mysql = 'SELECT COUNT(*) FROM `users` WHERE `username` = ? AND `password` = ?';
    $data = USER::isRegister($mysql,$username,$password1);

    if ($data[0] !== '1' ) {
      $this->errors[] ='Incorrect Password.';
    }
  }

  // sendMail
  public function sendMail($username,$email,$random) {
    $to = $email;
    $subject = "Confirm your Email";

    $message = "
    <html>
      <head>
      <title>HTML email</title>
      </head>
      <body>
      <p>This email contains HTML Tags!</p>
      <table>
      <tr>
      <th>Firstname</th>
      <th>Lastname</th>
      </tr>
      <tr>
      <td>John</td>
      <td><a href='http://localhost/Blog/app/views/user/'>gogle</a></td>
      </tr>
      </table>
      </body>
    </html>
    ";

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Blog Registration <27dhjetor@gmail.com>';
    $headers[] = 'Cc: 27dhjetore@gmail.com';
    $headers[] = 'Bcc: 27dhjetore@gmail.com';

    // Mail it
    mail($to, $subject, $message, implode("\r\n", $headers));
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
