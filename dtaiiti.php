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
  if(isset($_POST['taitoru'])) {
  	$tai = $_POST['taitoru'];// 入力値のチェック
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
    if($tai=="*"){
      $sql = "SELECT * FROM dvd";
    }
    else{
      $sql = "SELECT * FROM dvd WHERE d_name LIKE '%$tai%'";
    }

    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    // SQL文を実行する
    $stm->execute();
    // 結果の取得（連想配列で返す）
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    // テーブルのタイトル行
    if($tai==NULL){
      echo "タイトルを入力してください";
    }
    // テーブルのタイトル行
    else{
      if($tai=="*"){
        $tai = "";
        echo "全てのタイトルを表示します";
      }
      else{
        echo "「{$tai}」が含まれたタイトルを検索します";
      }
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>", "DVD番号", "</th>";
    echo "<th>", "タイトル名", "</th>";
    echo "<th>", "カテゴリ番号", "</th>";
    echo "<th>", "入荷日", "</th>";
    echo "<th>", "主演", "</th>";
    echo "<th>", "監督", "</th>";

    echo "</tr></thead>";
    // 値を取り出して行に表示する
    echo "<tbody>";
    foreach ($result as $row){
      // １行ずつテーブルに入れる
      echo "<tr>";
      echo "<td>", $row['d_id'], "</td>";
      echo "<td>", $row['d_name'], "</td>";
      echo "<td>", $row['cat_id'], "</td>";
      echo "<td>", $row['d_date'], "</td>";
      echo "<td>", $row['d_act'], "</td>";
      echo "<td>", $row['d_sp'], "</td>";

      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  }
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
