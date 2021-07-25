<?php 
namespace controller\register;

use lib\Auth;
use lib\Msg;
use model\UserModel;

function get() {
    require_once SOURCE_BASE . 'views/register.php';
}

// register.phpをpostで取得した場合の処理
function post() {
    $user = new UserModel;
    $user->id = get_param('id', '');
    $user->pwd = get_param('pwd', '');
    $user->nickname = get_param('nickname', '');

    // 認証処理
    if (Auth::regist($user)) {
        Msg::push(Msg::INFO, "{$user->nickname}さんようこそ。");
        // 認証が成功したらhome.phpへリダイレクトする。
        redirect(GO_HOME);
    } else {
        redirect(GO_REFERER);
    }
}
