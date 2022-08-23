<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/config/db.php"); // информация о базе данных
require_once($_SERVER["DOCUMENT_ROOT"]."/config/rb-mysql.php"); // подключение RedBeanPHP

// подключение к базе данных
R::setup( "mysql:host=".db::$HostDB."; dbname=".db::$BaseDB, db::$UserDB, db::$PassDB );

R::ext('xdispense', function( $type ){
  return R::getRedBean()->dispense( $type );
});

if(!R::testConnection()){ 
  echo json_encode(["status"=> false, "message"=> 'Ошибка подключения к Базе Данных!']);
    exit;
}

$user_id = $_POST['user_id'];
R::hunt('users', 'id = ?', array($user_id));
R::hunt('register', 'id_user = ?', array($user_id));

echo json_encode(["status"=> true, "message"=> 'Success!']);
?>