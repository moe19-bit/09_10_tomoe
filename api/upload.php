<?php
// 関数ファイル読み込み
include('functions.php');
header('Access-Control-Allow-Origin: *');


if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0) {

    $uploadedFileName = $_FILES['upfile']['name']; //ファイル名の取得 
    $tempPathName = $_FILES['upfile']['tmp_name']; //tmpフォルダの場所 
    // tmp_name　＝一時保存先の名前
    $fileDirectoryPath = 'upload/'; //アップロード先フォルダ
    // (↑自分で決める)

    // ファイルごとにユニークな名前を作成.(最後に拡張子を追加) // ファイルの保存場所をファイル名に追加.
    $extension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);
    $uniqueName = date('YmdHis') . md5(session_id()) . "." . $extension;

    $fileNameToSave = $fileDirectoryPath . $uniqueName;
    // 最終的に「upload/hogehoge.png」のような形になる


    if (is_uploaded_file($tempPathName)) {
        if (move_uploaded_file($tempPathName, $fileNameToSave)) {

            chmod($fileNameToSave, 0644); // 権限の変更 } else { // アップロードに失敗　０６４４番に権限変更

        } else {
            echo json_encode(['error' => 'アップロードできませんでした']);
            http_response_code(500);
            exit();
        }
    } else { // tmpフォルダに画像が保存されていない
        echo json_encode(['error' => 'ファイルが見つかりません']);
        http_response_code(500);
        exit();
    }
} else {

    $savedFilename = '';
}




// ここからDBへの登録などの処理（create.phpとほぼ同じ）
// 必須項目のチェック
if (
    !isset($_POST['deadline']) || $_POST['deadline'] == '' ||
    !isset($_POST['selsct']) || $_POST['select'] == '' ||
    !isset($_POST['pref']) || $_POST['pref'] == '' ||
    !isset($_POST['task']) || $_POST['task'] == '' ||
    !isset($_POST['name']) || $_POST['name'] == ''

) {
    echo json_encode('param error!');
    http_response_code(500);
    exit();
}

// データの受け取り
$deadline = $_POST['deadline'];
$select = $_POST['select'];
$pref = $_POST['pref'];
$task = $_POST['task'];
$comment = $_POST['comment'];
$name = $_POST['name'];



// DB接続
$pdo = connectToDb();

// データ登録SQL作成
$sql = 'INSERT INTO keijiban_table(id, deadline, select, pref, task, comment, image, name, created_at, updated_at) VALUES(NULL, :deadline, :select, :pref, :task, :comment,:image, :name, sysdate(), sysdate())';
var_dump($sql);
// SQL実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':deadline', $deadline, PDO::PARAM_STR);
$stmt->bindValue(':select', $select, PDO::PARAM_STR);
$stmt->bindValue(':pref', $pref, PDO::PARAM_STR);
$stmt->bindValue(':task', $task, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':image', $fileNameToSave, PDO::PARAM_STR);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
// $fileNameToSavはimageファイルの名前である、$imageと間違えない。
$status = $stmt->execute();

var_dump($image);

// データ登録処理後
if ($status == false) {
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    showSqlErrorMsg($stmt);
} else {
    echo json_encode(['msg' => 'upload successful!']);
    exit();
}
