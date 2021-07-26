<?php 
namespace db;

use db\DataSource;
use model\TopicModel;

class TopicQuery {

    /**
     * ユーザーidに一致するトピック情報を返す。
     * 
     * @param object $user ユーザーオブジェクト
     * 
     * @return bool 
     */
    public static function fetchByUserId($user) {
        // ユーザーIDチェック
        if (!$user->isValidId()) {
            return false;
        }
        $db = new DataSource;
        $sql = 'SELECT * FROM pollapp.topics WHERE user_id=:id AND del_flg!=1 ORDER BY id desc;';
        
        $result = $db->select($sql, [
            ':id' => $user->id
        ], DataSource::CLS, TopicModel::class);
        return $result;
    }



    /**
     * 
     */
    public static function fetchPublishedTopics() {

        $db = new DataSource;
        $sql = '
        select
            t.*, u.nickname from pollapp.topics t 
        inner join pollapp.users u 
            on t.user_id = u.id
        where t.del_flg != 1
            and u.del_flg != 1
            and t.published = 1
        order by t.id desc
        ';
        
        $result = $db->select($sql, [], DataSource::CLS, TopicModel::class);
        return $result;
    }


    public static function fetchById($topic) {

        // ユーザーIDチェック
        // if (!$user->isValidId()) {
        //     return false;
        // }

        $db = new DataSource;
        $sql = '
        select
            t.*, u.nickname from pollapp.topics t 
        inner join pollapp.users u 
            on t.user_id = u.id
        where t.id = :id
            and u.del_flg != 1
            and t.published = 1
        order by t.id desc
        ';

        $result = $db->selectOne($sql, [
            ':id' => $topic->id
        ], DataSource::CLS, TopicModel::class);
        return $result;

    }

    // ユーザーidの登録を行い、結果を返す
    // public static function insert($user) {
    //     $db = new DataSource;
    //     // ユーザー情報登録クエリ
    //     $sql = 'INSERT INTO users (id, pwd, nickname) VALUES
    //             (:id, :pwd, :nickname)';

    //     // パスワードのハッシュ化
    //     $user->pwd = password_hash($user->pwd, PASSWORD_DEFAULT);
    //     // 成功すればtrue、失敗すればfalse
    //     return $db->execute($sql, [
    //         ':id' => $user->id,
    //         ':pwd' => $user->pwd,
    //         ':nickname' => $user->nickname
    //     ]);
    // }



}