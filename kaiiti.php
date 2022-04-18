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
<title>会員一覧</title>
</head>
<body>
<div>
  <?php
  //MySQLデータベースに接続する
  try {
    $pdo = new PDO($dsn, $user, $password);
    // プリペアドステートメントのエミュレーションを無効にする
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // 例外がスローされる設定にする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文を作る（全レコード）
    $sql = "SELECT * FROM member";

    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    // SQL文を実行する
    $stm->execute();
    // 結果の取得（連想配列で返す）
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    // テーブルのタイトル行
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>", "会員番号", "</th>";
    echo "<th>", "氏名", "</th>";
    echo "<th>", "住所", "</th>";
    echo "<th>", "電話番号", "</th>";
    echo "<th>", "生年月日", "</th>";
    echo "<th>", "入会日", "</th>";
    echo "<th>", "有効期限", "</th>";
    echo "<th>", "メールアドレス", "</th>";
    echo "<th>", "パスワード", "</th>";

    echo "</tr></thead>";
    // 値を取り出して行に表示する
    echo "<tbody>";
    foreach ($result as $row){
      // １行ずつテーブルに入れる
      echo "<tr>";
      echo "<td>", $row['m_id'], "</td>";
      echo "<td>", $row['m_name'], "</td>";
      echo "<td>", $row['m_ad'], "</td>";
      echo "<td>", $row['m_tell'], "</td>";
      echo "<td>", $row['m_bd'], "</td>";
      echo "<td>", $row['m_jd'], "</td>";
      echo "<td>", $row['m_limit'], "</td>";
      echo "<td>", $row['m_mail'], "</td>";
      echo "<td>", $row['m_pw'], "</td>";

      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  } catch (Exception $e) {
    echo '<span class="error">エラーがありました。</span><br>';
    echo $e->getMessage();
    exit();
  }
  ?>
</div>
<a href="kanri.html" class=btn>管理用ページに戻る</a>
</body>
</html>
