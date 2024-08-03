<?php

namespace Util;

use Exception;

class Base
{
  /**
   * Return value in wanted type if possible.
   * @param mixed $value Input value
   * @param string $type Types: "int"|"string"|"bool"|"null"
   */
  function typeval(mixed $value, string $type = "string")
  {
    try {
      switch (true) {
        case $type === "int":
          $value = intval($value);
          break;
        case $type === "string":
          $value = strval($value);
          break;
        case $type === "bool":
          $value = boolval($value);
          break;
        case $type === "null":
          $value = null;
          break;
      }
    } catch (Exception $err) {
      return $this->error($err);
    }

    return $value;
  }

  /**
   * Display catched error.
   * @param Exception $err The catched exception
   */
  function error(?Exception $err)
  {
    echo "\n❌ Oups ! An error occured 😔.\n";
    print_r($err);
    echo "\n";
    return false;
  }

  /** Dump variable 
   * @param mixed $elem Variable
   * @param string $name Dump name
   */
  function dump(mixed $elem, string $name = "DUMP")
  {
    try {
      echo "\n>> *****\n";
      echo "> ** {$name} :\n\n";
      var_dump($elem);
      echo "***** <<\n\n";
    } catch (Exception $err) {
      return $this->error($err);
    }
    return true;
  }
}