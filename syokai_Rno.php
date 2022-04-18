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
<title>貸出中のDVDと会員情報の一覧</title>
</head>
<body>
<div>
  <?php
  $errors = [];

  if(isset($_POST['did'])) {
    $did = $_POST['did'];// 入力値のチェック
  }else{// 未設定エラー
    $errors[] = "割引率が未設定";
  }
  //MySQLデータベースに接続する
  try {
    $pdo = new PDO($dsn, $user, $password);
    // プリペアドステートメントのエミュレーションを無効にする
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // 例外がスローされる設定にする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文を作る（全レコード）
    $sql = "SELECT * FROM sales,rental,member WHERE r_hen IS NULL AND d_id LIKE '$did' AND sales.s_id = rental.s_id GROUP BY sales.m_id";

    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    // SQL文を実行する
    $stm->execute();
    // 結果の取得（連想配列で返す）
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
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
  echo "</th></thead>";
  // 値を取り出して行に表示する
  foreach ($result as $row){
  echo "<tr>";
  echo "<td>" , $row['m_id'], "</td>";
  echo "<td>" , $row['m_name'], "</td>";
  echo "<td>" , $row['m_ad'], "</td>";
  echo "<td>" , $row['m_tell'], "</td>";
  echo "<td>" , $row['m_bd'], "</td>";
  echo "<td>" , $row['m_jd'], "</td>";
  echo "<td>" , $row['m_limit'], "</td>";
  echo "<td>" , $row['m_mail'], "</td>";
  echo "<td>" , $row['m_pw'], "</td>";
  echo"</tr>";
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
<a href="syokai.html" class=btn>照会画面に戻る</a>
</body>
</html>
