<?php
$user = 'root';// データベースユーザ
$password = 'root';
$dbName = 'testdb';// 利用するデータベース
// MySQLサーバ
$host = 'localhost:3306';
// MySQLのDSN文字列
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>レコードを追加する(INSERT)</title>
<!-- テーブル用のスタイルシート -->
</head>
<body>
<div>
  <?php
  if (!isset($_POST["mname"])){
    echo "追加データがありません";
  }else{
    $mname = $_POST["mname"];
    $age = $_POST["age"];
    $sex = $_POST["sex"];

  //MySQLデータベースに接続する
  try {
    $pdo = new PDO($dsn, $user, $password);
    // プリペアドステートメントのエミュレーションを無効にする
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // 例外がスローされる設定にする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "データベース{$dbName}に接続しました。", "<br>";
    // SQL文を作る（新規レコードを追加する）
    $sql = "INSERT member (name, age, sex) VALUES"
    ."(:name, :age, :sex)";
    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    $stm->bindValue(":name",$mname,PDO::PARAM_STR);
    $stm->bindValue(":age",$age,PDO::PARAM_STR);
    $stm->bindValue(":sex",$sex,PDO::PARAM_STR);
    // SQL文を実行する
    $count=$stm->execute();
    // 更新後の確認
    echo "<br>{$count}件追加しました。<br>\n";
    $sql = "SELECT * FROM member";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    // 結果の取得（連想配列で受け取る）
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    // テーブルのタイトル行
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>", "ID", "</th>";
    echo "<th>", "名前", "</th>";
    echo "<th>", "年齢", "</th>";
    echo "<th>", "性別", "</th>";
    echo "</tr></thead>";
    // 値を取り出して行に表示する
    echo "<tbody>";
    foreach ($result as $row){
      // １行ずつテーブルに入れる
      echo "<tr>";
      echo "<td>", $row['id'], "</td>";
      echo "<td>", $row['name'], "</td>";
      echo "<td>", $row['age'], "</td>";
      echo "<td>", $row['sex'], "</td>";
      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  } catch (Exception $e) {
    echo '<span class="error">エラーがありました。</span><br>';
    echo $e->getMessage();
    exit();
  }
}
  ?>
</div>
</body>
</html>
