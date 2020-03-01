<?php
// 関数ファイル読み込み
include('functions.php');

// 必須項目のチェック
if (
  !isset($_POST['deadline']) || $_POST['deadline'] == '' ||
  !isset($_POST['select']) || $_POST['select'] == '' ||
  !isset($_POST['pref']) || $_POST['pref'] == '' ||
  !isset($_POST['task']) || $_POST['task'] == '' ||
  !isset($_POST['comment']) || $_POST['comment'] == '' ||
  !isset($_POST['name']) || $_POST['name'] == ''

) {
  echo json_encode('param error!');
  http_response_code(500);
  exit();
}

$deadline = $_POST['deadline'];
$select = $_POST['select'];
$pref = $_POST['pref'];
$task = $_POST['task'];
$comment = $_POST['comment'];
$name = $_POST['name'];


//DB接続
$pdo = connectToDb();

//データ登録SQL作成
$sql = 'INSERT INTO keijiban_table(id, deadline, select, pref, task, comment, name, created_at, updated_at) VALUES(NULL, :deadline, :select, :pref, :task, :comment, :name, sysdate(), sysdate())';

// SQL実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':deadline', $deadline, PDO::PARAM_STR);
$stmt->bindValue(':select', $select, PDO::PARAM_STR);
$stmt->bindValue(':pref', $pref, PDO::PARAM_STR);
$stmt->bindValue(':task', $task, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$status = $stmt->execute();

//データ登録処理後
if ($status == false) {
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  showSqlErrorMsg($stmt);
} else {
  echo json_encode(['msg' => 'Create successful!']);
  exit();
}
