<?php 
/**
 * session用の継承クラス
 * sessionを使う場合は、AbstractModelを継承させる。
 * 
 * abstractをつけて継承しないと使えないようにする。
 */

namespace model;

abstract class AbstractModel {
    // protected 継承先で使える変数
    protected static $SESSION_NAME = null;

    // セッションに値を入れる
    public static function setSession($val) {

        // $SESSION_NAME は継承先のクラスで指定する。(UserModelクラスなど)
        // 継承元で $SESSION_NAME を定義しないとエラー
        if (empty(static::$SESSION_NAME)) {
            // throw new Errorで意図的にエラーを発生させる。
            throw new Error('SESSION_NAMEを設定してください');
        }
        $_SESSION[static::$SESSION_NAME] = $val;
    }


    // セッション情報を返す
    public static function getSession() {
        return $_SESSION[static::$SESSION_NAME] ?? null;
    }
    
    
    public static function clearSession() {
        // nullで初期化
        static::setSession(null);
    }


    /**
     * セッションの値を取得して、その後にクリアする。
     * 
     * クリアしないと、メッセージが残り続ける。
     */
    public static function getSessionAndFlush() {
        try {
            return static::getSession();
          // 必ず呼ばれる。
        } finally {
            static::clearSession();
        }
    }
}

?>