<?php 

namespace controller\topic\detail;

use model\TopicModel;
use db\TopicQuery;
use db\CommentQuery;
use lib\Msg;

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



?>