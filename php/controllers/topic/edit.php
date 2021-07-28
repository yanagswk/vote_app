<?php
/**
 * 編集画面
 */

namespace controller\topic\edit;

use lib\Auth;
use model\TopicModel;
use model\UserModel;
use db\TopicQuery;
use lib\Msg;

/**
 * editがgetで呼ばれた場合
 */
function get() {
    // ログインチェック
    Auth::requireLogin();

    // セッションから値を取得(エラーが出た時、前の値を残しておくようにするため。)
    $topic = TopicModel::getSessionAndFlush();
    // セッションから取得できた場合
    if (!empty($topic)) {
        \view\topic\edit\index($topic, true);
        return;
    }

    $topic = new TopicModel;
    // getでtopic情報を取得
    $topic->id = get_param('topic_id', null, false);

    // ユーザー情報取得
    $user = UserModel::getSession();
    // 投稿したユーザーかチェック(投稿を編集できるかの権限確認)
    Auth::requirePermission($topic->id, $user);

    // idに対するトピック情報を取得
    $fetchedTopic = TopicQuery::fetchById($topic);

    // 画面メソッド実行
    \view\topic\edit\index($fetchedTopic, true);

}


/**
 * editがpostで呼ばれた場合
 */
function post() {
    // ログインチェック
    Auth::requireLogin();

    $topic = new TopicModel;

    // postで値を取得 \view\topic\edit\index()からpostで飛んできた値
    $topic->id = get_param('topic_id', null);
    $topic->title = get_param('title', null);
    $topic->published = get_param('published', null);

    // ユーザー情報取得
    $user = UserModel::getSession();
    // 投稿したユーザーかチェック(投稿を編集できるかの権限確認)
    Auth::requirePermission($topic->id, $user);

    try {
        // postで取得した値でDB更新
        $is_success = TopicQuery::update($topic);
    } catch(Throwable $e) {
        Msg::push(Msg::DEBUG, $e->getMessage());
        $is_success = false;
    }


    if ($is_success) {
        Msg::push(Msg::INFO, 'トピックの更新に成功しました。');
        redirect('topic/archive');
    } else {
        Msg::push(Msg::ERROR, 'トピックの更新に失敗しました。');

        // エラー時に前のデータが画面に残るようにするために、セッションに保存
        TopicModel::setSession($topic);
        redirect('GO_REFERER');
    }

}

?>