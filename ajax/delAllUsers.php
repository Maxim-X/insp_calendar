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
$department = R::loadAll('department', $_POST['company_id']);

R::exec('DELETE FROM register WHERE register.id_user IN (SELECT users.id FROM users WHERE id_department IN (SELECT department.id FROM department WHERE id_company = ?))', array($_POST['company_id']));

R::exec('DELETE FROM users WHERE id_department IN (SELECT department.id FROM department WHERE id_company = ?)', array($_POST['company_id']));
echo json_encode(["status"=> true, "message"=> 'Success!']);
?>