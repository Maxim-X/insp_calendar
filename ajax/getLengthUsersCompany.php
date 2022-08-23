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

$count_user = R::getAll('SELECT * FROM users WHERE id_department IN (SELECT id FROM `department` WHERE id_company = ? ) ', array($_POST['id_company']));

$count_user_rec = R::getAll('SELECT * FROM register WHERE id_user IN (SELECT id FROM `users` WHERE id_department IN (SELECT id FROM `department` WHERE id_company = ? ) ) ', array($_POST['id_company']));

echo json_encode(["all_users" => count($count_user), "rec_users" => count($count_user_rec)]);

?>