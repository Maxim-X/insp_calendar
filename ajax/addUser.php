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

if (empty(trim($_POST['fio']))) {
  echo json_encode(["status"=> false, "message" => "Введите ФИО!"]);
  exit;
}
if (empty(trim($_POST['department']))) {
  echo json_encode(["status"=> false, "message" => "Выберите отдел!"]);
  exit;
}

$user = R::dispense('users');
$user->fio = $_POST['fio'];
$user->id_department = $_POST['department'];
R::store($user);

echo json_encode(["status"=> true, "message"=> 'Success!']);

?>