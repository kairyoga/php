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
<title>退会完了</title>
</head>
<body>
<div>
  <?php
  $errors = [];
  if(isset($_POST['bango'])) {
  	$bango = $_POST['bango'];// 入力値のチェック
  }else{// 未設定エラー
  	$errors[] = "割引率が未設定";
  }
  if(isset($_POST['riyuu'])) {
    $riyuu = $_POST['riyuu'];// 入力値のチェック
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
    $sql = "SELECT * FROM member WHERE m_id LIKE '$bango'";


    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    // SQL文を実行する
    $stm->execute();
    // 結果の取得（連想配列で返す）
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    // テーブルのタイトル行    echo "{$tai}";
    $today = date("Y-m-d");
    if($bango==NULL){
      echo "番号を入力してください";
    }
    // テーブルのタイトル行
    else{
    echo "退会を完了しました";
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>", "会員番号", "</th>";
    echo "<th>", "退会日", "</th>";
    echo "<th>", "退会理由", "</th>";

    echo "</tr></thead>";
    // 値を取り出して行に表示する
    echo "<tbody>";
    foreach ($result as $row){
      // １行ずつテーブルに入れる
      $sql = "INSERT taikai (m_id,m_name,m_ad,m_tell,m_bd,m_limit,m_mail,m_pw,t_taiday,t_riyuu) VALUES"
      ."(:id, :name,:ad,:tell,:bd,:limit,:mail,:pw, :taiday, :riyuu)";
      // プリペアドステートメントを作る
      $stm = $pdo->prepare($sql);
      $stm->bindValue(":id",$bango,PDO::PARAM_STR);
      $stm->bindValue(":name",$row['m_name'],PDO::PARAM_STR);
      $stm->bindValue(":ad",$row['m_ad'],PDO::PARAM_STR);
      $stm->bindValue(":tell",$row['m_tell'],PDO::PARAM_STR);
      $stm->bindValue(":bd",$row['m_bd'],PDO::PARAM_STR);
      $stm->bindValue(":limit",$row['m_limit'],PDO::PARAM_STR);
      $stm->bindValue(":mail",$row['m_mail'],PDO::PARAM_STR);
      $stm->bindValue(":pw",$row['m_pw'],PDO::PARAM_STR);
      $stm->bindValue(":taiday",$today,PDO::PARAM_STR);
      $stm->bindValue(":riyuu",$riyuu,PDO::PARAM_STR);
      $stm->execute();
      echo "<tr>";
      echo "<td>", $row['m_id'], "</td>";
      echo "<td>", $today, "</td>";
      echo "<td>", $riyuu, "</td>";



      /*$sql = "DELETE FROM member WHERE m_id LIKE '$bango'";
      $stm = $pdo->prepare($sql);
      $stm->execute();*/
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
<a href="taikai.html" class=btn>退会ページに戻る</a>
</body>
</html>
