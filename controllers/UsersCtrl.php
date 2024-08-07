<?php

namespace Controller;

use Exception;
use PDO;
use Util\DB;

class UsersCtrl extends DB
{
  function __construct()
  {
    global $envDatas;

    try {
      parent::setDB($envDatas["DB_NAME"], host: $envDatas["DB_HOST"], user: $envDatas["DB_USER"], pwd: $envDatas["DB_PASSWORD"]);
    } catch (Exception $err) {
      return $this->error($err);
    }
  }
  /**
   * Check if user $name exist
   * @param string $name 
   */
  protected function isUser(string $name)
  {
    try {
      $req = $this->req("SELECT * FROM users WHERE userName=?", [$name]);
      $res = isset($req->fetch(PDO::FETCH_NUM)[0]);
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $res;
  }
  function areUsers()
  {
    try {
      $req = $this->req("SELECT count(userId) FROM users");
      $res = $req->fetch(PDO::FETCH_NUM)[0] > 0;
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $res;
  }

  /**
   * Signup user
   * @param string $name
   * @param string $pwd
   */
  function signup(string $name, string $pwd)
  {
    global $strFn;
    global $serverFn;
    global $templFn;

    try {
      $name = $strFn->validateInput($name);
      $pwd = $strFn->validateInput($pwd);
      if (!$name || !$pwd) return;

      $pwd = password_hash($pwd, PASSWORD_ARGON2I);
      if ($this->isUser($name)) {
        $templFn->notify("Utilisateur existant.", "error");
        $serverFn->goLocation(PUBLIC_LINK . "login");
      }

      $res = $this->req("INSERT INTO users (userName, userPwd) VALUES (?,?)", [$name, $pwd]);
      $res ?
        $templFn->notify("Inscription réussi.", "success") :
        $templFn->notify("Erreur lors de l'inscription.", "error");
      $serverFn->goLocation(PUBLIC_LINK . "login");
    } catch (Exception $err) {
      return $this->error($err);
    }
  }
  /**
   * Login user 
   * @param string $name
   * @param string $pwd
   */
  function login(string $name, string $pwd)
  {
    global $serverFn;
    global $strFn;
    global $templFn;

    try {
      $name = $strFn->validateInput($name);
      $pwd = $strFn->validateInput($pwd);
      if (!$name || !$pwd) return;

      $req = $this->req("SELECT * FROM users WHERE userName=?", [$name]);
      $res = $req->fetch(PDO::FETCH_ASSOC);
      if (password_verify($pwd, $res["userPwd"])) {
        $_SESSION["user"]["id"] = $res["userId"];
        $_SESSION["user"]["name"] = $res["userName"];
        $serverFn->goLocation(PUBLIC_LINK . "notes");
      }

      $templFn->notify("Identifiants incorrect", "error");
      $serverFn->goLocation(PUBLIC_LINK . "login");
    } catch (Exception $err) {
      return $this->error($err);
    }
  }
  function logout()
  {
    global $serverFn;
    unset($_SESSION["user"]);
    $serverFn->goLocation();
  }
}
