<?php 

namespace controller\topic\archive;

use lib\Auth;
use db\TopicQuery;
use model\UserModel;

function get () {
    
    Auth::requireLogin();
    
    // ユーザー情報取得
    $user = UserModel::getSession();

    // トピック情報取得
    $topics = TopicQuery::fetchByUserId($user);

    // トピック情報がうまく取得できない場合は、再度ログインさせる
    if ($topics === false) {
        Msg::push(Msg::ERROR, 'ログインしてください。');
        redirect('login');
    }

    // 情報が取得できたとき
    if (count($topics) > 0) {
        // トピック画面呼び出し
        \view\topic\archive\index($topics);
    } else {
        echo '<div class="alert alert-primary">トピックを投稿してみよう</div>';
    }
}



?>