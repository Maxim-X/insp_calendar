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

$all_reg = R::count( 'register' );
echo json_encode($all_reg);
?>