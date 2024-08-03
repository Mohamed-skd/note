<?php

namespace Util\Func;

use Exception;
use Util\Base;

class File extends Base
{
  /**
   * Return .env file datas
   */
  function getEnv()
  {
    try {
      $env = $_SERVER["DOCUMENT_ROOT"] ? $_SERVER["DOCUMENT_ROOT"] . "/.env" : ".env";
      $datas = [];

      if (file_exists($env)) {
        $envFile = file($env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($envFile as $line) {
          if (str_starts_with($line, "#"))
            continue;

          $tabline = explode("=", $line);
          $key = trim($tabline[0]);
          $value = trim($tabline[1]);
          if (str_starts_with($value, "#"))
            continue;

          $datas[$key] = $value;
        }
      }
    } catch (Exception $err) {
      return $this->error($err);
    }

    return $datas;
  }

  /**
   * Return dir content
   * @param string $dir The folder to search
   */
  function getDir(string $dir)
  {
    try {
      $dir = array_map(fn ($name) => "$dir/$name", array_values(array_diff(scandir($dir), [".", ".."])));
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $dir;
  }

  /**
   * Manage text file 
   * @param string $file File path
   */
  function textFile(string $path)
  {
    try {
      $fileClass = new class($path)
      {
        private ?string $path = null;

        function __construct($path)
        {
          $this->path = $path;
        }
        function get()
        {
          return file_get_contents($this->path);
        }
        function set($value = "")
        {
          return file_put_contents($this->path, $value);
        }
        function edit($regexp, $edition)
        {
          $content = preg_replace($regexp, $edition, $this->get());
          return $this->set($content);
        }
      };
    } catch (Exception $err) {
      return $this->error($err);
    }

    return $fileClass;
  }

  /**
   * Download file
   * @param string $file File Path
   * @param string $filename Name of the file (default:data.json)
   * @param string $type Header content-type (default:json)
   */
  function downFile(string $file, string $filename = "data.json", string $type = "application/json")
  {
    try {
      header("content-disposition:attachment; filename=$filename");
      header("content-type:$type");
      readfile($file);
      unlink($file);
    } catch (Exception $err) {
      return $this->error($err);
    }
    exit;
  }

  /**
   * Upload file
   * @param array $file
   * @param string $dest
   * @param ?array $types
   * @param ?int $maxSize
   */
  function upFile(array $file, string $dest, ?array $types = null, ?int $maxSize = null)
  {
    try {
      if ($types && !in_array($file["type"], $types)) throw new Exception("Wrong file type.");
      if ($maxSize && $file["size"] > $maxSize) throw new Exception("File too big.");
      if ($file["error"] !== 0) throw new Exception("Error while uploading: Error {$file["error"]}");

      move_uploaded_file($file["tmp_name"], $dest . $file["name"]);
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $file["name"];
  }
}