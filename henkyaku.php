<!DOCTYPE html>
<html><head>
<title>返却処理</title>
<meta charset="utf-8">
<link rel="stylesheet" href="calender.css" >
</div>
<body>
<html>
<?php
$DVD1 = $_POST["DVD1"];


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
  $sql="UPDATE rental SET r_hen =Now() WHERE d_id=:dvd and  r_hen IS NULL";
  // プリペアドステートメントを作る
  $stm = $pdo->prepare($sql);
  $stm->bindValue(":dvd",$DVD1);
  /*$stm->bindValue(":dvd",$DVD2);
  $stm->bindValue(":dvd",$DVD3);
  $stm->bindValue(":dvd",$DVD4);
  $stm->bindValue(":dvd",$DVD5);*/
  // SQL文を実行する
  $stm->execute();
}catch(Exception $e){
  exit;
}
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
  $sql = "SELECT * FROM rental,dvd,sales
  WHERE rental.d_id LIKE '$DVD1'
  AND rental.d_id = dvd.d_id
  AND rental.s_id = sales.s_id" ;

    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    // SQL文を実行する
    $stm->execute();
    // 結果の取得（連想配列で返す）
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);

    // テーブルのタイトル行
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>", "貸出番号", "</th>";
    echo "<th>", "DVD番号", "</th>";
    echo "<th>", "貸出日", "</th>";
    echo "<th>", "返却予定日", "</th>";
    echo "<th>", "返却日", "</th>";
    echo "<th>", "売上番号", "</th>";
    echo "<th>", "会員番号", "</th>";
    echo "</tr></thead>";
    // 値を取り出して行に表示する
    echo "<tbody>";
    $entai=0;
    $keisu=0;
    $uriage = 0;
    $today = date("Y-m-d");
    foreach ($result as $row){
      // １行ずつテーブルに入れる
      echo "<tr>";
      echo "<td>", $row['r_id'], "</td>";
      echo "<td>", $row['d_id'], "</td>";
      echo "<td>", $row['r_kasi'], "</td>";
      echo "<td>", $row['r_yotei'], "</td>";
      $yotei = $row['r_yotei'];
      echo "<td>", $row['r_hen'], "</td>";
      $hen = $row['r_hen'];
      echo "<td>", $row['s_id'], "</td>";
      echo "<td>", $row['m_id'], "</td>";
      $mid = $row['m_id'];
      echo "</tr>";


      if($row['r_yotei']<$row['r_hen']){
        $nissu = (strtotime($hen) - strtotime($yotei)) / 86400;
        $keisu = $keisu + $nissu;
        $entai = $entai + 110 * $nissu;
      }
      $nyuka = $row['d_date'];
      /*$kensa1 = strtotime($today)/86400;
      $kensa2 = strtotime($nyuka)/86400;
      $kensa3 = (strtotime($today) / 86400) - (strtotime($nyuka) / 86400);*/
      if(((strtotime($today) / 86400) - (strtotime($nyuka)/ 86400)) <= 90){
        $uriage = $uriage + 330;
      }
      else{
        $uriage = $uriage + 165;
      }
    }
    echo "</tbody>";
    echo "</table>";
    echo "レンタル料金は{$uriage}円です<br>";
    if($entai != 0){
      echo "延滞日数は{$keisu}日、延滞料金は{$entai}円です<br>";
      $gokei = $uriage + $entai;
      echo "合計{$gokei}円です<br>";
      $sql = "INSERT sales (s_kubun, m_id, s_date, s_money) VALUES"
      ."(:kubun, :id, :date, :money)";
      // プリペアドステートメントを作る
      $stm = $pdo->prepare($sql);
      $stm->bindValue(":kubun",3,PDO::PARAM_STR);
      $stm->bindValue(":id",$mid,PDO::PARAM_STR);
      $stm->bindValue(":date",$today,PDO::PARAM_STR);
      $stm->bindValue(":money",$entai,PDO::PARAM_STR);
      $stm->execute();
    }


  //接続を解除する
  $pdo = NULL;
} catch (Exception $e){
  echo '<span class="error">エラーがありました。</span><br>';
  echo $e->getMessage();
  exit();
}


?>
</div>
<a href="henkyaku.html" class=btn>返却ページに戻る</a>
</body>
</html>
