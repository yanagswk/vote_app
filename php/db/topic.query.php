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


    /**
     * 引数に渡されたトピックIDを返す
     * 
     * @param object $topic トピックのインスタンス
     * 
     * @return object 
     * @return bool
     */
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
        order by t.id desc
        ';

        $result = $db->selectOne($sql, [
            ':id' => $topic->id
        ], DataSource::CLS, TopicModel::class);
        return $result;
    }


    /**
     * topicテーブルの賛成か反対かをプラスするクエリを実行(自作)
     * 
     * @param object $topic トピックオブジェクト
     */
    // public static function topicUpdateJudgement($topic) {
    //     $db = new DataSource;
    //     if (isset($topic->likes)) {
    //         $sql = "
    //             UPDATE topics SET likes=likes+1 WHERE id=:id;
    //         ";
    //     } else {
    //         $sql = "
    //             UPDATE topics SET dislikes=dislikes+1 WHERE id=:id;
    //         ";
    //     }

    //     $result = $db->execute($sql, [
    //         ':id' => $topic->id,
    //     ]);

    //     return $result;
    // }


    /**
     * topicテーブルの賛成か反対かをプラスするクエリを実行
     * 
     * @param object $comment トピックオブジェクト
     * 
     * @return bool クエリ成功でtrue 失敗でfalse
     */
    public static function incrementLikesOrDislikes($comment) {

        // バリデーションチェック
        // if (!($comment->isValidTopicId()
        //     * $comment->isValidAgree())) {
        //         return false;
        //     }

        $db = new DataSource;
        if ($comment->agree) {
            $sql = "
                UPDATE topics SET likes=likes+1 WHERE id=:topic_id;
            ";
        } else {
            $sql = "
                UPDATE topics SET dislikes=dislikes+1 WHERE id=:topic_id;
            ";
        }

        return $db->execute($sql, [
            ':topic_id' => $comment->topic_id
        ]);
    }


    // ユーザーidの登録を行い、結果を返す
    // public static function insert($user) {
    //     $db = new DataSource;
    //     // ユーザー情報登録クエリ
    //     $sql = 'INSERT INTO users (id, pwd, nickname) VALUES
    //             (:id, :pwd, :nickname)';

    /**
     * viewカウントを+1するクエリを実行
     * 
     * @param object $topic トピック情報
     * 
     * @return bool クエリ実行結果がtrueかfalseか
     */
    public static function incrementViewCount($topic) {
        if (!($topic->isValidId())) {
            return false;
        }
        $db = new DataSource;
        $sql = "UPDATE topics SET views = views + 1 WHERE id = :id;";
        return $db->execute($sql, [
            ':id' => $topic->id
        ]);
    }



    /**
     * トピックに対するユーザーかを確認するクエリ
     * 
     * @param int $topic_id トピックID
     * @param object $user ユーザー情報
     * 
     * @return bool $result->count(1) = 0の場合は値が取得できていないのでfalse
     */
    public static function isUserOwnTopic($topic_id, $user) {

        if ((!TopicModel::validateId($topic_id) && $user->isValidId())) {
            return false;
        }

        $db = new DataSource;
        // データがいくつ取れたかわかればいいのでcountで指定。
        $sql = '
        select count(1) from pollapp.topics t 
        where t.id = :topic_id
            and t.user_id = :user_id
            and t.del_flg != 1;
        ';

        $result = $db->selectOne($sql, [
            ':topic_id' => $topic_id,
            ':user_id' => $user->id,
        ]);

        return !empty($result) && isset($result['count(1)']) && $result['count(1)'] != 0;
        // 上の処理とイコール
        // if (!empty($result) && isset($result['count(1)']) && $result['count(1)'] != 0) {
        //     return true;
        // } else {
        //     return false;
        // }
    }

    /**
     * トピックを更新するクエリを実行
     * 
     * @param object $topic トピックのオブジェクト
     * 
     * @return bool クエリが成功か失敗か
     */
    public static function update($topic){
        // 値のチェック

        $db = new DataSource;

        $sql = "UPDATE topics SET published=:published, title=:title where id=:id;";

        return $db->execute($sql, [
            ':published' => $topic->published,
            ':title' => $topic->title,
            ':id' => $topic->id,
        ]);
    }


    /**
     * トピック新規追加クエリを実行
     */
    public static function insert($topic, $user){
        // 値のチェック

        // if (!($user->isValidId()
        //     * $topic->isValidTitle()
        //     * $topic->isValidPublished())) {
        //     return false;
        // }

        $db = new DataSource;
        $sql = 'insert into topics(title, published, user_id) values (:title, :published, :user_id)';

        return $db->execute($sql, [
            ':title' => $topic->title,
            ':published' => $topic->published,
            ':user_id' => $user->id,
        ]);
    }

}