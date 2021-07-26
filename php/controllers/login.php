<?php

namespace controller\login;

use lib\Auth;
use lib\Msg;
use model\UserModel;

// login.phpをgetで取得した場合の処理
function get() {
    // require_once SOURCE_BASE . 'views/login.php';

    // 関数でlogin.phpを呼ぶ。
    \view\login\index();
}


// login.phpをpostで取得した場合の処理
function post() {
    // postでログインidとパスワードが取得できなかった場合は空文字。
    $id = get_param('id', '');
    $pwd = get_param('pwd', '');

    if (Auth::login($id, $pwd)) {
        // echo '認証成功';  ここにechoをしてもリダイレクトさせる為、表示されない。

        $user = UserModel::getSession();
        // メッセージをセッションに格納
        Msg::push(Msg::INFO, "{$user->nickname}さんようこそ。");
        redirect(GO_HOME);
    } else {
        // echo '認証失敗';   ここにechoをしてもリダイレクトしてるため、表示されない。
        
        // メッセージをセッションに格納
        // Msg::push(Msg::ERROR, '認証失敗');
        // HTTP_REFERERを使って一つ前のリクエストをであるlogin.phpを返す。
        redirect(GO_REFERER);
    }
}
