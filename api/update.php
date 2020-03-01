<?php
// 関数ファイル読み込み
include('functions.php');

if (
  !isset($_POST['deadline']) || $_POST['deadline'] == '' ||
  !isset($_POST['select']) || $_POST['select'] == '' ||
  !isset($_POST['pref']) || $_POST['pref'] == '' ||
  !isset($_POST['task']) || $_POST['task'] == '' ||
  !isset($_POST['comment']) || $_POST['comment'] == '' ||
  !isset($_POST['name']) || $_POST['name'] == '' ||
  !isset($_GET['id']) || $_GET['id'] == ''
) {
  echo json_encode('param error');
  http_response_code(500);
  exit();
}

$deadline = $_POST['deadline'];
$select = $_POST['select'];
$pref = $_POST['pref'];
$task = $_POST['task'];
$comment = $_POST['comment'];
$name = $_POST['name'];
$id = $_GET['id'];

//DB接続
$pdo = connectToDb();

// データ更新SQL作成
$sql = 'UPDATE keijiban_table SET deadline=:deadline, select=:select, pref=:pref, task=:task, comment=:comment, name=:name, updated_at=sysdate() WHERE id=:id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':deadline', $deadline, PDO::PARAM_STR);
$stmt->bindValue(':select', $select, PDO::PARAM_STR);
$stmt->bindValue(':pref', $pref, PDO::PARAM_STR);
$stmt->bindValue(':task', $task, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
// $stmt->bindValue(':image', $fileNameToSave, PDO::PARAM_STR);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

// データ更新処理後
if ($status == false) {
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  showSqlErrorMsg($stmt);
} else {
  echo json_encode(['msg' => 'Update successful!']);
  exit();
}
