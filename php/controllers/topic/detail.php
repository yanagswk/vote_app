<?php 

namespace controller\topic\detail;

use Throwable;
use db\TopicQuery;
use db\CommentQuery;
use db\DataSource;
use lib\Msg;
use lib\Auth;
use model\CommentModel;
use model\TopicModel;
use model\UserModel;

function get () {

    $topic = new TopicModel;
    // パラメータ取得して格納
    $topic->id = get_param('topic_id', null, false);

    // トピック情報取得
    $fetchedTopic = TopicQuery::fetchById($topic);
    // コメント情報取得
    $comments = CommentQuery::fetchByTopicId($topic);

    // トピックが見つからない場合
    if (!$fetchedTopic) {
        Msg::push(Msg::ERROR, 'トピックが見つかりません。');
        redirect('404');
    }


    \view\topic\detail\index($fetchedTopic, $comments);

}


function post() {

    // ログインチェック
    Auth::requireLogin();

    $comment = new CommentModel;
    $comment->topic_id = get_param('topic_id', null);    // トピックID
    $comment->body = get_param('body', null);    // コメント
    $comment->agree = get_param('judge', null);    // 賛成か反対か
    
    // セッションからユーザー情報取得
    $user = UserModel::getSession();
    $comment->user_id = $user->id;


    try {
        $db = new DataSource;
        // トランザクション開始
        $db->begin();

        // DB更新 (賛成か反対かの数値)
        $is_success = TopicQuery::incrementLikesOrDislikes($comment);
        if ($is_success && !empty($comment->body)) {
            // DB更新 (コメント追加)
            $is_success = CommentQuery::insert($comment);
        }

    } catch(Throwable $e) {
        Msg::push(Msg::DEBUG, $e->getMessage());
        $is_success = false;
        
    } finally {
        if ($is_success) {
            // SQLコミット　　　　　　　
            $db->commit();
            Msg::push(Msg::INFO, コメントの登録に成功しました。);
        } else {
            // SQLロールバック
            $db->rollback();
            Msg::push(Msg::ERROR, コメントの登録に失敗しました。);
        }
    }

    // redirect('topic/detail?topic_id=' . $comment->topic_id);

    /**
     * 自作
     */
    // // DBにコメント追加
    // $comment_result = CommentQuery::commentAdd($comment);

    // $topic = new TopicModel;
    // $topic->id = $topic_id;
    
    // if ($agree === "1") {
    //     $topic->likes = $agree;
    // } else {
    //     $topic->dislikes = $agree;
    // }

    // // DBに賛成か反対かの数値をプラス
    // $topic_result = TopicQuery::topicUpdateJudgement($topic);

    // 一つ前のURLにリダイレクト(同じページ)
    redirect(GO_REFERER);

}

?>