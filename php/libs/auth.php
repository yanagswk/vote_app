<?php

namespace lib;

use db\UserQuery;
use model\UserModel;

class Auth {

    /**
     * ログイン処理
     */
    public static function login($id, $pwd) {
        try {
            // ログイン項目のバリデーションチェック
            if (!(UserModel::validateId($id)
                * UserModel::validatePwd($pwd))) {
                return false;
            }

            $is_success = false;
            // dbに接続してユーザー情報を取得
            $user = UserQuery::fetchById($id);
            // del_flg=1の時は無効フラグ
            if (!empty($user) && $user->del_flg !== 1) {
                // 入力されたパスワードがdbから取得したハッシュ値に適合するか
                if (password_verify($pwd, $user->pwd)) {
                    $is_success = true;
                    // ログイン状態にするために、セッションに情報を入れる
                    UserModel::setSession($user);
                } else {
                    Msg::push(Msg::ERROR, 'パスワードが一致しません。');
                }
            } else {
                Msg::push(Msg::ERROR, 'ユーザーが見つかりません。');
            }

        } catch(Throwable $e) {
                $is_success = false;
                Msg::push(Msg::DEBUG, $e->getMessage());
                Msg::push(Msg::ERROR, 'ログイン処理でエラーが発生しました。');
        }
        return $is_success;
    }


    /**
     * 登録処理
     */
    public static function regist($user) {
        try {
            /* 登録項目のバリデーションチェック */
            // 「||」を使うと、途中でfalseだった場合はそれ以降が処理されずにエラーメッセージがでない。
            // if (!$user->isValidId()
            //     || !$user->isValidPwd()
            //     || !$user->isValidNickname()) {
            //     return false;
            // }
            // 全体を()で囲んで「*」演算子にすると、一つ一つがfalseは0、trueは1と計算されて、0が一つでもあると()のなかは0になる。
            // そのため、全て処理されることになるので、エラーメッセージは全て出力される。
            if (!($user->isValidId()
                * $user->isValidPwd()
                * $user->isValidNickname())) {
                return false;
            }

            $is_success = false;
            // dbに接続してユーザー情報を取得
            $exist_user = UserQuery::fetchById($user->id);
            // 取得できた場合は、同じユーザーが存在する
            if (!empty($exist_user)) {
                Msg::push(Msg::ERROR, '同じユーザー名の登録者が存在します。');

                return false;
            }
            // insert処理が成功すればtrueを返す。
            $is_success = UserQuery::insert($user);

            if ($is_success) {
                // ログイン状態にするために、セッションに情報を入れる
                // UserModelにsetSessionは定義していないので、継承元を見にいく(AbstractModelクラス)
                UserModel::setSession($user);
                // $_SESSION['user'] = $user;
            }
        } catch(Throwable $e) {
            $is_success = false;
            Msg::push(Msg::DEBUG, $e->getMessage());
            Msg::push(Msg::ERROR, '登録処理でエラーが発生しました。');
        }
        
        return $is_success;
    }


    /**
     * ログイン状態かを確認する処理
     */
    public static function isLogin() {
        try {
            $user = UserModel::getSession();
        } catch(Throwable $e) {
            UserModel::clearSession();
            Msg::push(Msg::DEBUG, $e->getMessage());
            Msg::push(Msg::ERROR, 'エラーが発生しました。再度ログインを行ってください。');
            return false;
        }
        
        if (isset($user)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    /**
     * ログアウト処理
     */
    public static function logout() {
        try {
            UserModel::clearSession();
        } catch (Throwable $e) {
            Msg::push(Msg::DEBUG, $e->getMessage());
            return false;
        }

        return true;
    }


    /**
     * ログインしていない状態でアクセスしたら、ログインページへリダイレクトさせる。
     */
    public static function requireLogin() {
        if (!static::isLogin()) {
            Msg::push(Msg::ERROR, 'ログインしてください。');
            redirect('login');
        }
    }
}

?>