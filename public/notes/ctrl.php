<?php
require_once "../../app.php";
if (!$currentUser) {
  $templFn->notify("Veuillez d'abord vous connecter.", "error");
  $serverFn->goLocation(PUBLIC_LINK . "login");
}

$page = basename(__DIR__);
$title = "Notes";

function renderFormCats(array $cats)
{
  $res = "";
  foreach ($cats as &$cat) {
    $res .= "<option value=\"$cat\">$cat</option>";
  }
  return $res;
}
function renderNavCats(array $cats)
{
  $res = " 
      <li>
        <a target=\"_self\" href=\"?cat\" class=\"link\">Toute</a>
      </li>";
  foreach ($cats as &$cat) {
    $res .= "
      <li>
        <a target=\"_self\" href=\"?cat=$cat\" class=\"link\">$cat</a>
      </li>";
  }
  return $res;
}
function renderNotes(array $notes)
{
  $res = "";
  foreach ($notes as &$note) {
    $res .= " 
      <article  data-id=\"{$note->id}\">
        <input type=\"checkbox\" name=\"select\">
        <p class=\"cat\">{$note->cat}</p>
        <p class=\"content\">{$note->content}</p>

        <aside class=\"flex\">
        <button class=\"bt edit\">Modifier</button>
        <button class=\"bt delete\">Supprimer</button>
        </aside>
      </article>";
  }
  return $res;
}
function respond(Controller\NotesCtrl $res)
{
  global $serverFn;

  try {
    if ($res) {
      $formCats = renderFormCats($res->cats);
      $navCats = renderNavCats($res->cats);
      $resNotes = renderNotes($res->notes);
      $serverFn->resJson([$formCats, $navCats, $resNotes]);
    } else {
      $serverFn->resJson(false);
    }
  } catch (Exception $err) {
    return $serverFn->error($err);
  }
}

if (isset($_POST["content"])) {
  $res = $notesCtrl->add($_POST["content"], $_POST["cat"]);
  respond($res);
}
if (isset($_POST["edit"])) {
  $res = $notesCtrl->update($_POST["edit"], $_POST["cat"]);
  respond($res);
}
if (isset($_POST["delete"])) {
  $res = $notesCtrl->delete($_POST["delete"]);
  respond($res);
}

$currentCat = "Mes notes";
$notes = $notesCtrl->notes;
$cats = $notesCtrl->cats;

if (isset($_GET["cat"])) {
  $cat = $strFn->validateInput($_GET["cat"]);
  if ($cat) {
    $currentCat = $cat;
    $notes = array_values(array_filter(
      $notes,
      function ($note) use ($cat) {
        if (!$note->cat) return false;
        return strtolower($note->cat) === strtolower($cat);
      }
    ));
  }
}