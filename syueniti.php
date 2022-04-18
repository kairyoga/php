<?php
  // データベースユーザ
  $user = 'root';
  $password = 'root';
  // 利用するデータベース
  $dbName = 'rentaldb';
  // MySQLサーバ
  $host = 'localhost:3306';
// MySQLのDSN文字列
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>レコードを取り出す（すべて）</title>
</head>
<body>
<div>
  <?php
  $errors = [];
  //MySQLデータベースに接続する
  try {
    $pdo = new PDO($dsn, $user, $password);
    // プリペアドステートメントのエミュレーションを無効にする
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // 例外がスローされる設定にする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文を作る（全レコード）
    $sql = "SELECT * FROM member,sales,rental WHERE r_hen IS NULL AND sales.s_id = rental.s_id AND member.m_id = sales.m_id GROUP BY member.m_id ORDER BY member.m_id ASC";


    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    // SQL文を実行する
    $stm->execute();
    // 結果の取得（連想配列で返す）
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    // テーブルのタイトル行
    // テーブルのタイトル行
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>", "会員番号","</th>";
    echo "<th>", " 氏名","</th>";
    echo "<th>", "住所","</th>";
    echo "<th>", "電話番号","</th>";
    echo "<th>", "生年月日","</th>";
    echo "<th>", "入会日","</th>";
    echo "<th>", "有効期限","</th>";
    echo "<th>", "メールアドレス","</th>";
    echo "<th>", "pw","</th>";
    echo "<th>", "sid","</th>";
    echo "<th>", "rid","</th>";

    echo "</tr></thead>";
    // 値を取り出して行に表示する
    echo "<tbody>";
    $today = date("Y-m-d");
    foreach ($result as $row){
      // １行ずつテーブルに入れる
      $rhrn = $row['r_hen'];
      $henyo = $row['r_yotei'];
      echo "<tr>";
    if(((strtotime($today) / 86400) - (strtotime($henyo)/ 86400)) >= 7 /*AND empty($rhen)*/){
        echo "<td>" , $row['m_id'], "</td>";
        echo "<td>" , $row['m_name'], "</td>";
        echo "<td>" , $row['m_ad'], "</td>";
        echo "<td>" , $row['m_tell'], "</td>";
        echo "<td>" , $row['m_bd'], "</td>";
        echo "<td>" , $row['m_jd'], "</td>";
        echo "<td>" , $row['m_limit'], "</td>";
        echo "<td>" , $row['m_mail'], "</td>";
        echo "<td>" , $row['m_pw'], "</td>";
        echo "<td>" , $row['s_id'], "</td>";
        echo "<td>" , $row['r_id'], "</td>";
      }
      echo "</tr>";

    }
    echo "</tbody>";
    echo "</table>";
  }
   catch (Exception $e) {
    echo '<span class="error">エラーがありました。</span><br>';
    echo $e->getMessage();
    exit();
  }
  ?>
</div>
<a href="kanri.html" class=btn>管理用ページに戻る</a>
</body>
</html>
