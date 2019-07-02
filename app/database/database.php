<?php

/**
 *  Database
 */
class Database implements DBInterface {
  private static $user = 'root';
  private static $pass = '';
  private static $db_name = 'blog';

  public static $db;

  public function __construct() {
    try {
       self::$db = new PDO('mysql:host=localhost;dbname='.self::$db_name, self::$user, self::$pass);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
  }

  public static function connect() {
        if (!self::$db) {
            new Database();
        }
        return self::$db;
    }

    public function select1($fields,$tables,$conditions=null,$groups=null,$orders=null,$limit=null)
    {
      // $mysql = 'SELECT * FROM users';
      $mysql = 'SELECT ';
      if ($fields == 0) {
        Controller::redirect('/user/login');
      } elseif($fields == 1) {
          $mysql .= $fields;
      } else {
        $lastElement = end($fields);
        foreach ($fields as $field) {
          $mysql .= $field;
          if (!$field == $lastElement) {
            $mysql .= ',';
          }
        }
      }

      $mysql .= ' FROM ';
      if ($tables == 0) {
        Controller::redirect('/users/login');
      } elseif($tables == 1) {
        $mysql .= $tables;
      } else {
        $lastElement = end($tables);
        foreach ($tables as $table) {
          $mysql .= $table;
          if (!$table == $lastElement) {
            $mysql .= ',';
          }
        }
      }

      // if ($conditions !== null) {
      //   if ($conditios == 0) {
      //     Controller::redirect('/users/login');
      //   } elseif($conditios == 1) {
      //     $mysql .= $conditios;
      //   } else {
      //     $lastElement = end($conditios);
      //     foreach ($conditios as $conditio) {
      //       $mysql .= $conditio;
      //       if (!$conditio == $lastElement) {
      //         $mysql .= ',';
      //       }
      //     }
      //   }
      // }

      self::connect();
      $query = self::$db->prepare($mysql);
      $query->execute();
      $data = $query->fetchAll();
      var_dump($query);
      return $data;
    }

}
