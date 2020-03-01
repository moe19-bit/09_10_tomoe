<?php
// 関数ファイル読み込み
include('functions.php');

//DB接続
$pdo = connectToDb();

// データ表示SQL作成
$sql = 'SELECT * FROM keijiban_table';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// データ表示
$view = '';
if ($status == false) {
  showSqlErrorMsg($stmt);
} else {
  // fetchAll()でSQL実行結果を全件取得できる
  echo json_encode($stmt->fetchAll());
  exit();
}
