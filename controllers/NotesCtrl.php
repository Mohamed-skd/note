<?php

namespace Controller;

use Exception;
use PDO;
use Util\DB;
use Model\Note;
use Model\User;

class NotesCtrl extends DB
{
  public User $user;
  public array $notes;
  public array $cats;

  function __construct()
  {
    global $envDatas;
    global $currentUser;

    try {
      parent::setDB($envDatas["DB_NAME"], pwd: $envDatas["DB_PASSWORD"]);
      $this->user = $currentUser;
      $this->getAll();
      $this->getCats();
    } catch (Exception $err) {
      return $this->error($err);
    }
  }

  function getAll()
  {
    try {
      $this->notes = [];
      $req = $this->req("SELECT * from notes WHERE noteAuthor=?", [$this->user->name]);
      while ($res = $req->fetch(PDO::FETCH_ASSOC)) {
        array_unshift($this->notes, new Note(
          $res["noteId"],
          $res["noteContent"],
          $res["noteAuthor"],
          $res["noteCreatedAt"],
          $res["noteCat"],
          $res["noteUpdatedAt"]
        ));
      }
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $this;
  }
  function getCats()
  {
    try {
      $this->cats = array_values(array_filter(array_unique(array_map(fn ($note) => $note->cat, $this->notes))));
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $this;
  }

  /**
   * Add note
   * @param string $content
   * @param ?string $cat
   */
  function add(string $content, ?string $cat = null)
  {
    global $strFn;
    global $dateFn;

    try {
      $content = $strFn->validateInput($content, 1000);
      $cat = $strFn->validateInput($cat);
      if (!$content) return $this;

      $createdAt = $dateFn->formatDate(format: "Y-m-d");
      $this->req("INSERT INTO notes (noteContent, noteAuthor, noteCat, noteCreatedAt) VALUES (?,?,?,?)", [$content, $this->user->name, $cat, $createdAt]);
      $this->getAll();
      $this->getCats();
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $this;
  }
  /**
   * Update note
   * @param int $id
   * @param string $content
   * @param ?string $cat
   */
  function update(int $id, string $content, string $cat)
  {
    global $strFn;
    global $dateFn;

    try {
      $id = $strFn->validateInput($id);
      $content = $strFn->validateInput($content, 1000);
      $cat = $strFn->validateInput($cat);
      if (!$id || !$content) return $this;

      $updatedAt = $dateFn->formatDate(format: "Y-m-d");
      $this->req("UPDATE notes SET noteContent=?, noteCat=?, noteUpdatedAt=? WHERE noteId=?", [$content, $cat, $updatedAt, $id]);
      $this->getAll();
      $this->getCats();
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $this;
  }
  /**
   * Delete note
   * @param int $id
   */
  function delete(int $id)
  {
    global $strFn;

    try {
      $id = $strFn->validateInput($id);
      if (!$id) return $this;

      $this->req("DELETE FROM notes WHERE noteId=?", [$id]);
      $this->getAll();
      $this->getCats();
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $this;
  }

  /**
   * Search note
   * @param string $search
   */
  function search(string $search)
  {
    global $strFn;
    try {
      $res = [];
      $search = $strFn->validateInput($search);
      if (!$search) return $res;

      $res = array_values(array_filter($this->notes, function ($note) use ($search) {
        return str_contains(strtolower($note->content), strtolower($search));
      }));
    } catch (Exception $err) {
      return $this->error($err);
    }
    return $res;
  }
}