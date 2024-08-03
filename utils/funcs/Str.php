<?php

namespace Util\Func;

use Exception;
use Util\Base;

class Str extends Base
{
  /**
   * Escape html string
   * @param string $string Input string
   */
  function escape(string $string)
  {
    try {
      $res = trim(htmlspecialchars($string));
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $res;
  }

  /**
   * Validate client input
   * @param string $input Client Input
   * @param int $size Max size authorized (default:100)
   */
  function validateInput(string $input, int $size = 100)
  {
    global $strFn;
    try {
      $input = $strFn->escape($input);
      if (!$input || strlen($input) > $size) return null;
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $input;
  }

  /**
   * Format string (capitalized and hyphens replaced by spaces)
   * @param string $text Input string
   */
  function formatString(string $text)
  {
    try {
      $res = str_replace("-", " ", trim(ucfirst(strtolower($text))));
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $res;
  }

  /**
   * Return the first word of a string
   * @param string $str Input string
   */
  function getSlug(string $str)
  {
    try {
      preg_match("/^\w+/i", trim($str), $slug);
      $res = ucfirst($slug[0]);
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $res;
  }
}