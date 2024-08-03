<?php
require_once "../../app.php";
if (!$currentUser) {
  $templFn->notify("Veuillez d'abord vous connecter.", "error");
  $serverFn->goLocation(PUBLIC_LINK . "login");
}

$page = basename(__DIR__);
$title = "Notes";