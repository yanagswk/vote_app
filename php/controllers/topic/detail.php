<?php 

namespace controller\topic\detail;

use model\TopicModel;
use model\CommentModel;
use db\TopicQuery;
use db\CommentQuery;
use lib\Msg;
use lib\Auth;

function get() {

    $topic = new TopicModel;
    // パラメータ取得して格納
    $topic->id = get_param('topic_id', null, false);

    // viewカウントを増やす
    TopicQuery::incrementViewCount($topic);

    // トピック情報取得
    $fetchedTopic = TopicQuery::fetchById($topic);
    // コメント情報取得
    $comments = CommentQuery::fetchByTopicId($topic);

    // トピックが見つからない場合
    if (empty($fetchedTopic) || !$fetchedTopic->published) {
        Msg::push(Msg::ERROR, 'トピックが見つかりません。');
        redirect('404');
    }

    \view\topic\detail\index($fetchedTopic, $comments);

}


function post() {

    Auth::requireLogin();


}



?>