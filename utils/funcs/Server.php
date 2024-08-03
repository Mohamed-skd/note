<?php

namespace Util\Func;

use CurlHandle;
use Exception;
use Util\Base;

class Server extends Base
{
  public ?CurlHandle $curl = null;

  /**
   * Return JSON value and exit script
   * @param mixed $value Input value
   */
  function resJson(mixed $value)
  {
    try {
      header("content-type:application/json");
      echo json_encode($value);
    } catch (Exception $err) {
      return $this->error($err);
    }
    exit;
  }

  /**
   * Return String value and exit script
   * @param mixed $value Input value
   */
  function resText(mixed $value)
  {
    try {
      header("content-type:text/html");
      echo strval($value);
    } catch (Exception $err) {
      return $this->error($err);
    }
    exit;
  }

  /**
   * Send server side event
   * @param string $event Event name
   * @param string $data Event payload
   */
  function sendEventStream($event = "open", $data = null)
  {
    try {
      $id = uniqid();
      $data = json_encode($data);
      echo "id: $id\nevent: $event\ndata: $data\n\n";
    } catch (Exception $err) {
      return $this->error($err);
    }
    return true;
  }

  /**
   * Call to API with curl
   * @param string $link The API endpoint
   * @param bool $return Return response
   * @param ?array $opts Curl options: https://www.php.net/manual/fr/function.curl-setopt-array.php
   */
  function fetch(string $link, bool $return = true, ?array $opts = null)
  {
    try {
      $this->curl = curl_init($link);

      if (isset($opts)) {
        curl_setopt_array($this->curl, $opts);
      }

      curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, $return);
      $exec = curl_exec($this->curl);
      $res = $exec ? json_decode($exec, true) : false;
    } catch (Exception $err) {
      return $this->error($err);
    }

    return $res;
  }

  /**
   * Set a header location to go and exit script
   * @param string $location Basename to locate (default:homepage)
   */
  function goLocation(string $location = "/")
  {
    try {
      header("location:" . $location);
    } catch (Exception $err) {
      return $this->error($err);
    }
    exit;
  }
}