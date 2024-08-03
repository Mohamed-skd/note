<?php
require_once "../../app.php";
if ($currentUser) {
  $serverFn->goLocation(PUBLIC_LINK . "notes");
}

$page = basename(__DIR__);
$title = "Notes";

if (isset($_POST["signup"])) {
  $usersCtrl->signup($_POST["signup"], $_POST["pwd"]);
}
if (isset($_POST["login"])) {
  $usersCtrl->login($_POST["login"], $_POST["pwd"]);
}