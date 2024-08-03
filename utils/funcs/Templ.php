<?php

namespace Util\Func;

use Exception;
use Util\Base;

class Templ extends Base
{
  public $notification;

  function __construct()
  {
    try {
      $this->notification = $_SESSION["notification"] ?? [];
      unset($_SESSION["notification"]);
    } catch (Exception $err) {
      return $this->error($err);
    }
  }

  /**
   * Store in session server notifications to client
   * @param string $content The information to display
   * @param mixed $type Type of the notification (success, error, ...)
   */
  function notify(string $content, mixed $type = null)
  {
    try {
      $_SESSION["notification"] = ["content" => $content, "type" => $type];
    } catch (Exception $err) {
      return $this->error($err);
    }
    return true;
  }

  /**
   * Return copyright string
   * @param string $info Information
   * @param array $link Link: ["ref"=>"", "text"=>""]
   */
  function copyright(string $info = "Par ", array $link = ["ref" => "https://ko-fi.com/mohsd", "text" => "Moh. SD"])
  {
    try {
      global $dateFn;

      if (!$info && $link) {
        $res = "© " . $dateFn->formatDate(format: "Y");
      } elseif (!$link) {
        $res = $info . " © " . $dateFn->formatDate(format: "Y");
      }
      $res = $info . "<a class='link' href=" . $link["ref"] . ">" . $link["text"] . "</a> © " . $dateFn->formatDate(format: "Y");
    } catch (Exception $err) {
      return $this->error($err);
    }

    return $res;
  }
}