<!DOCTYPE html>
<html lang="ja">
<head>
  <link rel="stylesheet" href="touroku.css">
<meta charset="utf-8">
<title>レコード追加</title>
<link href="../../css/style.css" rel="stylesheet">
</head>
<body>
<html>
<?php
$name1 = $_POST["name"];
$name2 = $_POST["address"];
if(isset($_POST['tell'])) {
  $name3 = $_POST['tell'];// 入力値のチェック
}else{// 未設定エラー
  $errors[] = "エラー１";
}
$name4= $_POST["birth"];
if(isset($_POST['mail'])) {
  $name5 = $_POST['mail'];// 入力値のチェック
}else{// 未設定エラー
  $errors[] = "エラー２";
}
$name6 = $_POST["pw"];
// データベースユーザ
$user = 'root';
$password = 'root';
// 利用するデータベース
$dbName = 'rentaldb';
// MySQLサーバ
$host = 'localhost';
// MySQLのDSN文字列
$dns = "mysql:host={$host};dbname={$dbName};charset=utf8";
//MySQLデータベースに接続する
try {
  $pdo = new PDO($dns,$user,$password);
  //プリペアドレステートメントのエミュレーションを無効にする
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
  //例外がスローされる設定にする
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //SQL文を作る
  $sql="SELECT * FROM member";
  // プリペアドステートメントを作る
  $stm = $pdo->prepare($sql);

  // SQL文を実行する
  $stm->execute();
  // 結果の取得（連想配列で返す）
  $result = $stm->fetchAll(PDO::FETCH_ASSOC);
 $today = date("y-m-d");
 $limit = date("y-m-d", strtotime("1 year"));
  //テーブルのタイトル行
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
  $id = $row['m_id'];
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
echo "</today>";
echo "</table>";
$sql = "INSERT member ( m_name, m_ad,m_tell,m_bd,m_jd,m_limit,m_mail,m_pw) VALUES"
."(:name,:ad,:tell,:bd,:jd,:limit,:mail,:pw)";

// プリペアドステートメントを作る
$stm = $pdo->prepare($sql);
$stm->bindValue(":name",$name1,PDO::PARAM_STR);
$stm->bindValue(":ad",$name2,PDO::PARAM_STR);
$stm->bindValue(":tell",$name3,PDO::PARAM_STR);
$stm->bindValue(":bd",$name4,PDO::PARAM_STR);
$stm->bindValue(":jd",$today,PDO::PARAM_STR);
$stm->bindValue(":limit",$limit,PDO::PARAM_STR);
$stm->bindValue(":mail",$name5,PDO::PARAM_STR);
$stm->bindValue(":pw",$name6,PDO::PARAM_STR);
$stm->execute();

$sql = "INSERT sales ( s_kubun, m_id,s_date,s_money) VALUES"
."(:name,:ad,:tell,:bd)";

// プリペアドステートメントを作る
$stm = $pdo->prepare($sql);
$stm->bindValue(":name",2,PDO::PARAM_STR);
$stm->bindValue(":ad",$id,PDO::PARAM_STR);
$stm->bindValue(":tell",$today,PDO::PARAM_STR);
$stm->bindValue(":bd",330,PDO::PARAM_STR);
$stm->execute();
}catch (Exception $e) {
  echo '<span class="error">エラーがありました。</span><br>';
echo $e->getMessage();
}
?>
</div>
</body>
</html>
