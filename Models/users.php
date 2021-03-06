<?php
///////////////////////////////////////
// ユーザーデータを処理
///////////////////////////////////////
 
/**
    * ユーザーを作成
    *
    * @param array $data
    * @return bool
    */
function createUser(array $data)
{
    // DB接続
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 
    // 接続エラーがある場合->処理停止
    if ($mysqli->connect_errno) {
        echo 'MySQLの接続に失敗しました。：' . $mysqli->connect_error . "\n";
        exit;
    }
 
    // 新規登録のSQLクエリを作成
    $query = 'INSERT INTO users (email, name, nickname, password) VALUES (?, ?, ?, ?)';
 
    // プリペアドステートメントに、作成したクエリを登録
    $statement = $mysqli->prepare($query);
 
    // パスワードをハッシュ値に変換
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
 
    // クエリのプレースホルダ（?の部分）にカラム値を紐付け
    $statement->bind_param('ssss', $data['email'], $data['name'], $data['nickname'], $data['password']);
 
    // クエリを実行
    $response = $statement->execute();
 
    // 実行に失敗した場合->エラー表示
    if ($response === false) {
        echo 'エラーメッセージ：' . $mysqli->error . "\n";
    }
 
    // DB接続を解放
    $statement->close();
    $mysqli->close();
 
    return $response;
}