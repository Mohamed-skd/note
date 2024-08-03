<?php

namespace Util;

use Exception;
use PDO;

abstract class DB extends Base
{
  private static string $dsn;
  private static PDO $db;

  private function __construct()
  {
  }

  /**
   * Set PDO connexion to the DB
   * @param string $dbname DB name
   * @param string $engine DB engine (default: mysql)
   * @param ?string $host Host name (default: localhost)
   * @param ?string $user User name (default: root)
   * @param ?string $pwd Password
   */
  protected static function setDB(string $dbname, string $engine = "mysql", ?string $host = "localhost", ?string $user = "root", ?string $pwd = null)
  {
    try {
      if ($host && $dbname) {
        self::$dsn = "$engine:host=$host;dbname=$dbname";
        self::$db = new PDO(self::$dsn, $user, $pwd);
      } else if (!$host) {
        self::$dsn = "$engine:$dbname";
        self::$db = new PDO(self::$dsn);
      }
    } catch (Exception $err) {
      $base = new parent();
      return $base->error($err);
    }
  }

  /**
   * SQL request to the DB
   * @param string $req Prepared request
   * @param array $args (Optional) Request parameters
   */
  protected function req(string $req, array $args = [])
  {
    try {
      $results = self::$db->prepare($req);
      $results->execute($args);
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $results;
  }

  /**
   * Return sql SELECT query
   * @param string $table Table name
   * @param array $cols Table columns (["col1", "col2", ...] | ["*"])
   */
  protected function selectQuery(string $table, array $cols)
  {
    try {
      $query = "SELECT ";
      foreach ($cols as &$col) {
        $query .= "$col, ";
      }
      $query = substr($query, 0, -2);
      $query .= " FROM $table";
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $query;
  }

  /**
   * Return sql INSERT query
   * @param string $table Table name
   * @param array $content Table content (["table_col"=>[data1, data2, ...], ...])
   */
  protected function insertQuery(string $table, array $content)
  {
    try {
      $queryCol = "(";
      $qData = "";
      $queryDatas = "VALUES ";
      $args = [];
      $argsCount = implode(",", array_map(fn () => "?", $content));
      $numArr = array_values($content);

      foreach ($content as $colname => &$_) {
        $queryCol .= "$colname,";
      }
      foreach ($numArr[0] as &$_) {
        $qData .= "($argsCount), ";
      }
      for ($i = $j = 0; $i <= count($numArr); $i++) {
        if ($i === count($numArr)) {
          $i = 0;
          $j++;
        }
        if ($j === count($numArr[$i])) break;
        $args[] = $numArr[$i][$j];
      }

      $queryDatas .= $qData;
      $queryCol = substr($queryCol, 0, -1);
      $queryDatas = substr($queryDatas, 0, -2);
      $query = "INSERT INTO $table $queryCol) $queryDatas";
    } catch (Exception $err) {
      return $this->error($err);
    }

    return [$query, $args];
  }

  /**
   * Return sql UPDATE query
   * @param string $table Table name
   * @param array $content Table content (["table_col1"=>"data", "table_col2"=>"data", ...])
   */
  protected function updateQuery(string $table, array $content)
  {
    try {
      $queryCol = "";
      $args = [];

      foreach ($content as $colname => &$data) {
        $queryCol .= "$colname=?, ";
        $args[] = $data;
      }

      $queryCol = substr($queryCol, 0, -2);
      $query = "UPDATE $table SET $queryCol";
    } catch (Exception $err) {
      return $this->error($err);
    }

    return [$query, $args];
  }

  /**
   * Return sql DELETE query
   * @param string $table Table name
   */
  protected function deleteQuery(string $table)
  {
    try {
      $query = "DELETE FROM $table";
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $query;
  }

  /**
   * Read a sql table
   * @param string $table Table name
   */
  protected function selectTable(string $table)
  {
    try {
      $req = $this->req("SELECT * FROM $table");
      $res = $req->fetchAll();
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $res;
  }

  /**
   * INSERT in a sql table
   * @param string $table Table name
   * @param array $content Table content (["table_col"=>[data1, data2, ...], ...])
   */
  protected function insertTable(string $table, array $content)
  {
    try {
      [$query, $args] = $this->insertQuery($table, $content);
      $res = $this->req($query, $args);
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $res;
  }

  /**
   * Update every entries of a sql table
   * @param string $table Table name
   * @param array $content Table content (["table_col1"=>"data", "table_col2"=>"data", ...])
   */
  protected function updateTable(string $table, array $content)
  {
    try {
      [$query, $args] = $this->updateQuery($table, $content);
      $res = $this->req($query, $args);
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $res;
  }

  /**
   * Delete every entries of a sql table
   * @param string $table Table name
   */
  protected function deleteTable(string $table)
  {
    try {
      $res = $this->req("DELETE FROM $table; ALTER TABLE $table AUTO_INCREMENT= 1");
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $res;
  }
}