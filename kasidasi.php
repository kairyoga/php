<?php
 require_once("util.php");
 session_start();
 ?>

 <?php
 $user = 'root';
 $password = 'root';
 $dbName = 'rentaldb';
 $host = 'localhost:3306';
 $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
 ?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>貸出確認画面</title>
<link href="style.css"  rel="stylesheet">
</head>
<bdoy>
<div>
<?php
require_once("util.php");

if(!cken($_POST)){
$encoding=mb_internal_encoding();
$err="Encoding error! The expected encoding is".$encoding;
exit($err);
}
$_POST=es($_POST);
?>

<?php

function checked($value,$question){
if(is_array($question)){
$isChecked=in_array($value,$question);
}else{
$isChecked=($value===$question);
}
if($isChecked){
echo "checked";

}else{
 echo "　";
}
}
?>
<?php
error_reporting(E_ALL & ~E_NOTICE);
$id=$_POST["mem_id"];
$p=$_POST["dvd_id"];
$price = 0;
$name;
$yotei;
$title;
$uriage;
$kigen;

try {
  $pdo = new PDO($dsn, $user, $password);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT m_id as 会員番号,m_name as 名前,m_ad as 住所,m_tell as 電話番号,m_bd as 生年月日,m_jd as 入会日,m_limit as 有効期限,m_mail as メールアドレス,m_pw as パスワード FROM member WHERE m_id= $id";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $key => $val) {
    echo "会員情報";
    if (empty($val)) {
        continue;
    }
    echo "<table border=1 style=border-collapse:collapse;>";
    echo "<tr>";
    foreach ($result[$key] as $key2 => $val2) {
      echo "<th>";
      echo $key2;
      echo "</th>";
    }
    echo "</tr>";
    echo "<tr>";
    foreach ($result[$key] as $key2 => $val2) {
    echo "<td>";
    echo $val2;
    echo "</td>";
    }
    echo "</tr>";
    echo "</table>";
    foreach ($result as $row){
     $name=$row['名前'];

     $kigen=$row['有効期限'];
      echo "</tr>";
    }}
    echo "</tbody>";
    echo "</table>";
    echo "<HR>";
 } catch (Exception $e) {
  echo '<span class="error">エラーがありました。</span><br>';
  echo $e->getMessage();
  exit();
}

 ?>
<?php
$ren_kasi = date("Y-m-d ");
$henkyakuyotei=date("Y-m-d", strtotime("{$ren_kasi} 1 week"));
?>
<?php

$isError=false;
if(isset($_POST['d_id'])){
  $_SESSION['d_id']=$_POST['d_id'];
  $d_id=trim($_POST['d_id']);
  if($d_id===""){
    $isError=true;
  }
}else{
  $isError=true;
}


try {
  $pdo = new PDO($dsn, $user, $password);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$day1 = new DateTime('2015-09-24');
$day2 = new DateTime('2015-05-11');

$day3 = $day1->diff($day2);


if($day3 >= 90){
  $price =330;
  $uriage=$price;
}else{
$price=165;
$uriage=$price;
}


$sql="INSERT INTO sales(s_kubun,m_id,s_date,s_money) VALUES(1,:id,now(),$price)";
$stm = $pdo->prepare($sql);
$stm->bindValue(":id",$id,PDO::PARAM_INT);
$stm->execute();
  $result = $stm->fetchAll(PDO::FETCH_ASSOC);

$sal_id=$pdo-> lastInsertId();

  $sql = "INSERT INTO rental(d_id ,r_kasi,r_yotei,s_id)
    VALUES ( :d_id, now(), DATE_ADD(CURRENT_DATE(),INTERVAL 7 DAY), :s_id)";
  $stm = $pdo->prepare($sql);
  $stm->bindValue(":d_id",$p,PDO::PARAM_INT);
  $stm->bindValue(":s_id",$sal_id,PDO::PARAM_INT);

  $count=$stm->execute();
  $aaa = new DateTime();

  $yotei= $aaa -> modify('+8day') -> format('20y-m-d');

  $sql = "SELECT * FROM rental ";
  $stm = $pdo->prepare($sql);
  $stm->execute();
$result = $stm->fetchAll(PDO::FETCH_ASSOC);


      $sql = "SELECT DISTINCT d_date as 入荷日,d_name as タイトル名 FROM rental,dvd WHERE rental.d_id=dvd.d_id AND rental.d_id= $p";
      $stm = $pdo->prepare($sql);
      $stm->execute();

      $result = $stm->fetchAll(PDO::FETCH_ASSOC);
      foreach ($result as $key => $val) {
        echo "貸出DVD情報";
        if (empty($val)) {
          continue;
        }
        echo "<table border=1 style=border-collapse:collapse;>";
        echo "<tr>";
foreach ($result[$key] as $key2 => $val2) {
  echo "<th>";
  echo $key2;
  echo "</th>";
}
echo "</tr>";
echo "<tr>";
foreach ($result[$key] as $key2 => $val2) {
echo "<td>";
echo $val2;
echo "</td>";
}
}
  echo "</tbody>";
  echo "</table>";
  echo "<HR>";




    } catch (Exception $e) {
      echo '<span class="error">エラーがありました。</span><br>';
      echo $e->getMessage();
      exit();


  }catch (Exception $e) {
  echo '<span class="error">エラーがありました。</span><br>';
  echo $e->getMessage();
  exit();
}

echo "レシート";
echo "<table border=1 style=border-collapse:collapse;>";
echo "<th>", "会員番号" ,"</th>";
echo "<th>", "返却予定日" ,"</th>";
echo "<th>", "タイトル名" ,"</th>";
echo "<th>", "売上金額" ,"</th>";
echo "</tr></thead>";


foreach ($result as $row){
echo "<tr>";
echo "<td>",$id, "</td>";
echo "<td>",$yotei, "</td>";
echo "<td>",$row['タイトル名'], "</td>";
echo "<td>",$uriage, "</td>";
echo "</tr>";
}
echo "</table>";
?>
 <p><a href="kasidasimitame.html">戻る</a>
 <a href="menu.html">MENU</a></p>
 </div>
 </body>
 </html>
