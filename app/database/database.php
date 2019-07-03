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
      try {
        if (!isset($fields)) {
          throw new Exception('Field is not set.');
        }
        $mysql = 'SELECT ';
        if ($fields == ['']) {
          throw new Exception('Field is null.');
        } elseif($fields == 1) {
            $mysql .= $fields;
        } else {
          $lastElement = end($fields);
          foreach ($fields as $field) {
            $mysql .= $field;
            if ($field !== $lastElement) {
              $mysql .= ',';
            }
          }
        }

        $mysql .= ' FROM ';
        if ($tables == null) {
          throw new Exception('Table is null.');
        } elseif($tables == 1) {
          $mysql .= $tables;
          $mysql .= ' ';
        } else {
          $lastElement = end($tables);
          foreach ($tables as $table) {
            $mysql .= $table;
            $mysql .= ' ';
            if ($table !== $lastElement) {
              $mysql .= ',';
            }
          }
        }

        if ($conditions !== null) {
          $mysql .= ' WHERE ';
          if($conditions == 1) {
            foreach ($conditions as $condition) {
              $mysql .= $condition;
              $mysql .= ' ';
            }
          } else {
            foreach ($conditions as $condition) {
              foreach ($condition as $condition1) {
                $mysql .= $condition1;
                $mysql .= ' ';
            }
          }
        }
      }

        if ($groups !== null) {
          $mysql .= ' GROUP BY ';
          if($groups == 1) {
            $mysql .= $groups;
          } else {
            $lastElement = end($groups);
            foreach ($groups as $group) {
              $mysql .= $group;
              if ($group !== $lastElement) {
                $mysql .= ',';
              }
            }
          }
        }

        if ($orders !== null) {
          $mysql .= ' ORDER BY ';
          if($orders == 1) {
            $mysql .= $orders;
          } else {
            $lastElement = end($orders);
            foreach ($orders as $order) {
              $mysql .= $order;
              if ($order !== $lastElement) {
                $mysql .= ' ';
              }
            }
          }
        }

        if ($limit !== null) {
          $mysql .= ' LIMIT ';
          if($limit == 1) {
            $mysql .= $limit;
          } else {
            $lastElement = end($limit);
            foreach ($limit as $limiti) {
              $mysql .= $limiti;
              if ($limiti !== $lastElement) {
                $mysql .= ' ';
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
      } catch (\Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
      }
    }

}
