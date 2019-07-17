<?php

namespace App\Database;

use App\Core\DBinterface;
use \PDO;

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

    public function select($fields,$tables,$conditions=null,$groups=null,$orders=null,$limit=null)
    {
      try {
        $mysql = 'SELECT ';
        if ($fields == [''] || $fields == '' || $fields == null) {
          throw new Exception('Field is null.');
        } elseif ($fields == 1) {
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
        if ($tables == null || $tables == [''] || $tables == '') {
          throw new Exception('Table is null.');
        } elseif ($tables == 1) {
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
          if ($conditions == 1) {
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
          if ($groups == 1) {
            $mysql .= $groups;
          } else {
            $lastElement = end($groups);
            foreach ($groups as $group) {
              $mysql .= $group;
              if ($group !== $lastElement) {
                $mysql .= ' ';
              }
            }
          }
        }

        if ($orders !== null) {
          $mysql .= ' ORDER BY ';
          if ($orders == 1) {
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
          if ($limit == 1) {
            $mysql .= $limit;
          } else {
            $lastElement = end($limit);
            foreach ($limit as $limiti) {
              $mysql .= $limiti;
              if ($limiti !== $lastElement) {
                $mysql .= ',';
              }
            }
          }
        }

        // var_dump($mysql);
        self::connect();
        $query = self::$db->prepare($mysql);
        $query->execute();
        $data = $query->fetchAll();
        // var_dump($query);
        return $data;
      } catch (\Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
      }
    }

    public function insert($tables,$values, $sequenceName=null){
      try {
        $mysql = 'INSERT INTO ';
        if ($tables == null || $tables == [''] || $tables == '') {
          throw new Exception('Table is null.');
        } elseif ($tables == 1) {
          $mysql .= $tables;
        } else {
          $lastElement = end($tables);
          foreach ($tables as $table) {
            $mysql .= $table;
            if ($table !== $lastElement) {
              $mysql .= ',';
            }
          }
        }

        $mysql .= '(';
        if ($values == null || $values == [''] || $values == '') {
          throw new Exception('Table is null.');
        } elseif ($values == 1) {
          $mysql .= $values;
        } else {
          $lastElement = end($values);
          foreach ($values as $value) {
            $mysql .= $value;
            $mysql .= ' ';
            if ($value !== $lastElement) {
              $mysql .= ',';
            }
          }
        }
        $mysql .= ')';

        $mysql .= ' VALUES (';
        if ($sequenceName == null || $sequenceName == [''] || $sequenceName == '') {
          throw new Exception('Sequence is null.');
        } else {
          if ($sequenceName == 1) {
            $mysql .= $sequenceName;
          } else {
            $lastElement = end($sequenceName);
            foreach ($sequenceName as $sequence) {
              $mysql .= $sequence;
              if ($sequence !== $lastElement) {
                $mysql .= ',';
              }
            }
          }
        }
        $mysql .= ')';

        // var_dump($mysql);
        self::connect();
        $query = self::$db->prepare($mysql);
        $data = $query->execute();
        return $data;
      } catch (\Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
      }
    }

    public function update($tables, $values, $conditions = null){
      try {
        $mysql = 'UPDATE ';
        if ($tables == null || $tables == [''] || $tables == '') {
          throw new Exception('Table is null.');
        } elseif ($tables == 1) {
          $mysql .= $tables;
        } else {
          $lastElement = end($tables);
          foreach ($tables as $table) {
            $mysql .= $table;
            if ($table !== $lastElement) {
              $mysql .= ',';
            }
          }
        }

        $mysql .= ' SET ';
        if ($values !== null) {
          if ($conditions == 1) {
            foreach ($values as $value) {
              $mysql .= $value;
              $mysql .= ' ';
            }
          } else {
            $nrIValues = count($values);
            $nr = 0;
            foreach ($values as $value) {
              $nr ++;
              foreach ($value as $value1) {
                $mysql .= $value1;
                $mysql .= ' ';
              }
              if ($nr !== $nrIValues) {
                $mysql .= ',';
              } else {
                $mysql .= ' ';
              }
            }
          }
         }

        if ($conditions !== null) {
           $mysql .= ' WHERE ';
           if ($conditions == 1) {
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

        // echo $mysql;
        self::connect();
        $query = self::$db->prepare($mysql);
        $data = $query->execute();
        return $data;
      } catch (\Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
      }
    }

    public function delete($tables, $conditions = null){
      try {
        $mysql = 'DELETE FROM ';
        if ($tables == null || $tables == [''] || $tables == '') {
          throw new Exception('Table is null.');
        } elseif ($tables == 1) {
          $mysql .= $tables;
        } else {
          $lastElement = end($tables);
          foreach ($tables as $table) {
            $mysql .= $table;
            if ($table !== $lastElement) {
              $mysql .= ',';
            }
          }
         }

        if ($conditions !== null) {
          $mysql .= ' WHERE ';
          if ($conditions == 1) {
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


        self::connect();
        $query = self::$db->prepare($mysql);
        $data = $query->execute();
        return $data;
      } catch (\Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
      }
    }

    public function raw($mysql){
      // var_dump($mysql);
      self::connect();
      $query = self::$db->prepare($mysql);
      $query->execute();
      $data = $query->fetch();
      return $data;
    }

}
