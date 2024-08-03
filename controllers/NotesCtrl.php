<?php
namespace Controller;
use Exception;
use PDO;
use Util\DB;

class NotesCtrl extends DB {
function __construct() {
global $envDatas;
parent::setDB($envDatas["DB_NAME"], pwd: $envDatas["DB_PASSWORD"]);
}
}
