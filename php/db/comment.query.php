<?php 
namespace db;

use db\DataSource;
use model\CommentModel;

class CommentQuery {

    /**
     * 
     */
    public static function fetchByTopicId($topic) {

        // ユーザーIDチェック
        // if (!$topic->isValidId()) {
        //     return false;
        // }

        $db = new DataSource;
        $sql = '
        select
            c.*, u.nickname 
        from comments c 
        inner join users u 
            on c.user_id = u.id 
        where c.topic_id = :id
            and c.body != ""
            and c.del_flg != 1
            and u.del_flg != 1
        order by c.id desc;
        ';
        
        $result = $db->select($sql, [
            ':id' => $topic->id
        ], DataSource::CLS, CommentModel::class);
        return $result;
    }


    /**
     * コメント追加クエリ
     * 
     * @param object $comment コメントオブジェクト
     */
    public static function insert($comment) {   

        // コメントのバリデーションチェック
        // if (!($comment->isValidTopicId()
        //     * $comment->isValidBody()
        //     * $comment->isValidAgree())) {
        //         return false;
        //     }


        $db = new DataSource;
        $sql = '
        INSERT INTO comments
            (topic_id, agree, body, user_id)
        VALUES
            (:topic_id, :agree, :body, :user_id);
        ';

        $result = $db->execute($sql, [
            ':topic_id' => $comment->topic_id,
            ':agree' => $comment->agree,
            ':body' => $comment->body,
            ':user_id' => $comment->user_id
        ]);

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