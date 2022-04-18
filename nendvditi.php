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
<title>年度ごとのDVDの貸出件数</title>
</head>
<body>
<div>
  <?php
  $errors = [];
  if(isset($_POST['nendvd'])) {
  	$nend = $_POST['nendvd'];// 入力値のチェック
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
    $sql = "SELECT d_id,COUNT(d_id) as dkei FROM rental WHERE r_kasi LIKE '$nend%' GROUP BY d_id ORDER BY dkei DESC";


    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    // SQL文を実行する
    $stm->execute();
    // 結果の取得（連想配列で返す）
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    // テーブルのタイトル行    echo "{$tai}";
    if($nend==NULL){
      echo "年度を入力してください";
    }
    elseif($nend<=2000){
      echo "西暦2000年以上で入力してください";
    }
    // テーブルのタイトル行
    else{
        echo "{$nend}年の検索結果";
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>", "DVD番号", "</th>";
    echo "<th>", "貸出数", "</th>";

    echo "</tr></thead>";
    // 値を取り出して行に表示する
    echo "<tbody>";
    foreach ($result as $row){
      // １行ずつテーブルに入れる
      echo "<tr>";
      echo "<td>", $row['d_id'], "</td>";
      echo "<td>", $row['dkei'], "</td>";

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
