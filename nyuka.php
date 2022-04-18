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
  if(isset($_POST['taitoru'])) {
  	$taitoru = $_POST['taitoru'];// 入力値のチェック
  }else{// 未設定エラー
  	$errors[] = "割引率が未設定";
  }
  if(isset($_POST['kateban'])) {
    $kateban = $_POST['kateban'];// 入力値のチェック
  }else{// 未設定エラー
    $errors[] = "割引率が未設定";
  }

  if(isset($_POST['syuen'])) {
    $syuen = $_POST['syuen'];// 入力値のチェック
  }else{// 未設定エラー
    $errors[] = "割引率が未設定";
  }
  if(isset($_POST['kantoku'])) {
    $kantoku = $_POST['kantoku'];// 入力値のチェック
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
    $sql = "SELECT * FROM category";


    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    // SQL文を実行する
    $stm->execute();
    // 結果の取得（連想配列で返す）
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    // テーブルのタイトル行    echo "{$tai}";
    $today = date("Y-m-d");
    if($kateban == "ca1"){
      $kateban = 11;
    }
    elseif($kateban == "ca2"){
      $kateban = 12;
    }
    elseif($kateban == "ca3"){
      $kateban = 13;
    }
    elseif($kateban == "ca4"){
      $kateban = 21;
    }
    elseif($kateban == "ca5"){
      $kateban = 22;
    }
    elseif($kateban == "ca6"){
      $kateban = 23;
    }
    if($taitoru==NULL OR $kateban==NULL OR $syuen==NULL OR $kantoku==NULL){
      echo "未入力の項目があります";
    }

    // テーブルのタイトル行
    else{
    echo "登録しました";
    echo "<table>";
    echo "<thead><tr>";
    echo "<th>", "タイトル", "</th>";
    echo "<th>", "カテゴリID", "</th>";
    echo "<th>", "入荷日", "</th>";
    echo "<th>", "主演", "</th>";
    echo "<th>", "監督", "</th>";

    echo "</tr></thead>";
    // 値を取り出して行に表示する
    echo "<tbody>";
    echo "<td>", $taitoru, "</td>";
    echo "<td>", $kateban, "</td>";
    echo "<td>", $today, "</td>";
    echo "<td>", $syuen, "</td>";
    echo "<td>", $kantoku, "</td>";
    echo "</tr>";
    echo "</tbody>";
    echo "</table>";
    $sql = "INSERT dvd (d_name,cat_id,d_date,d_act,d_sp) VALUES"
    ."(:name, :id,:date,:act,:sp)";
    // プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    $stm->bindValue(":name",$taitoru,PDO::PARAM_STR);
    $stm->bindValue(":id",$kateban,PDO::PARAM_STR);
    $stm->bindValue(":date",$today,PDO::PARAM_STR);
    $stm->bindValue(":act",$syuen,PDO::PARAM_STR);
    $stm->bindValue(":sp",$kantoku,PDO::PARAM_STR);
    $stm->execute();
  }


  } catch (Exception $e) {
    echo '<span class="error">エラーがありました。</span><br>';
    echo $e->getMessage();
    exit();
  }
  ?>
</div>
<a href="nyuka.html" class=btn>入荷ページに戻る</a>
</body>
</html>
