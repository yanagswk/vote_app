<?php 
namespace db;

use db\DataSource;
use model\UserModel;

class UserQuery {

    // ユーザーidに一致するユーザー情報を返す。
    public static function fetchById($id) {
        $db = new DataSource;
        $sql = 'select * from users where id = :id;';

        $result = $db->selectOne($sql, [
            ':id' => $id
        ], DataSource::CLS, UserModel::class);
        return $result;
    }


    // ユーザーidの登録を行い、結果を返す
    public static function insert($user) {
        $db = new DataSource;
        // ユーザー情報登録クエリ
        $sql = 'INSERT INTO users (id, pwd, nickname) VALUES
                (:id, :pwd, :nickname)';

        // パスワードのハッシュ化
        $user->pwd = password_hash($user->pwd, PASSWORD_DEFAULT);
        // 成功すればtrue、失敗すればfalse
        return $db->execute($sql, [
            ':id' => $user->id,
            ':pwd' => $user->pwd,
            ':nickname' => $user->nickname
        ]);
    }



}