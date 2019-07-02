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
        $mysql .= ' ';
      } else {
        $lastElement = end($tables);
        foreach ($tables as $table) {
          $mysql .= $table;
          $mysql .= ' ';
          if (!$table == $lastElement) {
            $mysql .= ',';
          }
        }
      }

      if ($conditions !== null) {
        if ($conditions == 0) {
          Controller::redirect('/users/login');
        } elseif($conditions == 1) {
          $mysql .= $conditions;
        } else {
          $lastElement = end($conditions);
          foreach ($conditions as $condition) {
            $mysql .= $condition;
            if (!$condition == $lastElement) {
              $mysql .= ',';
            }
          }
        }
      }

      if ($groups !== null) {
        if ($groups == 0) {
          Controller::redirect('/users/login');
        } elseif($groups == 1) {
          $mysql .= $groups;
        } else {
          $lastElement = end($groups);
          foreach ($groups as $group) {
            $mysql .= $group;
            if (!$group == $lastElement) {
              $mysql .= ',';
            }
          }
        }
      }

      if ($orders !== null) {
        if ($orders == 0) {
          Controller::redirect('/users/login');
        } elseif($orders == 1) {
          $mysql .= $orders;
        } else {
          $lastElement = end($orders);
          foreach ($orders as $order) {
            $mysql .= $order;
            if (!$order == $lastElement) {
              $mysql .= ',';
            }
          }
        }
      }

      if ($limit !== null) {
        if ($limit == 0) {
          Controller::redirect('/users/login');
        } elseif($limit == 1) {
          $mysql .= $limit;
        } else {
          $lastElement = end($limit);
          foreach ($limit as $limiti) {
            $mysql .= $limiti;
            if (!$limiti == $lastElement) {
              $mysql .= ',';
            }
          }
        }
      }

      self::connect();
      $query = self::$db->prepare($mysql);
      $query->execute();
      $data = $query->fetchAll();
      var_dump($query);
      return $data;
    }

}
