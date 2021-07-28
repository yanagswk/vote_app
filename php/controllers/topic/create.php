<?php
/**
 * 編集画面
 */

namespace controller\topic\create;

use lib\Auth;
use model\TopicModel;
use model\UserModel;
use db\TopicQuery;
use lib\Msg;

/**
 * createがgetで呼ばれた場合
 */
function get() {
    // ログインチェック
    Auth::requireLogin();

    // セッションから値を取得(エラーが出た時、に前の値を残しておくようにするため。)
    $topic = TopicModel::getSessionAndFlush();
    // セッションから取得できなかった場合は、初期化する。
    if (empty($topic)) {
        $topic = new TopicModel;
        // 仮データ
        $topic->id = -1;
        $topic->title = '';
        $topic->published = -1;
    }

    // 画面メソッド実行
    \view\topic\edit\index($topic, false);

}


/**
 * createがpostで呼ばれた場合
 */
function post() {
    // ログインチェック
    Auth::requireLogin();

    $topic = new TopicModel;

    // postで値を取得 \view\topic\edit\index()からpostで飛んできた値
    $topic->id = get_param('topic_id', null);
    $topic->title = get_param('title', null);
    $topic->published = get_param('published', null);

    
    try {
        // ユーザー情報取得
        $user = UserModel::getSession();
        // postで取得した値でDB更新
        $is_success = TopicQuery::insert($topic, $user);
    } catch(Throwable $e) {
        Msg::push(Msg::DEBUG, $e->getMessage());
        $is_success = false;
    }


    if ($is_success) {
        Msg::push(Msg::INFO, 'トピックの登録に成功しました。');
        redirect('topic/archive');
    } else {
        Msg::push(Msg::ERROR, 'トピックの登録に失敗しました。');

        // エラー時に前のデータが画面に残るようにするために、セッションに保存
        TopicModel::setSession($topic);
        redirect('GO_REFERER');
    }

}

?>