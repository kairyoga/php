<!DOCTYPE html>
<html><head>
<title>照会処理</title>
<meta charset="utf-8">
<link rel="stylesheet" href="calender.css" >
</div>
<body>
<html>
<?php
$DVD = $_POST["DVD"];
//データベースユーザ
$user = 'root';
$password = 'root';
//利用するデータベース
$dbName = 'rentaldb';
//MySQLサーバー
$host = 'localhost';
//MySQLのDNS文字列
$dns = "mysql:host={$host};dbname={$dbName};charset=utf8";
//MySQLデータベースに接続する
try {
  $pdo = new PDO($dns,$user,$password);
  //プリペアドレステートメントのエミュレーションを無効にする
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
  //例外がスローされる設定にする
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //SQL文を作る
  $sql="SELECT * FROM dvd";
  // プリペアドステートメントを作る
  $stm = $pdo->prepare($sql);
  $stm->bindValue(":dvd",$DVD);
  // SQL文を実行する
  $stm->execute();
}catch(Exception $e){
  exit;
}
