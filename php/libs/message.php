<?php
/**
 * message用のクラス
 * DEBUG定数がfalseの場合は、DEBUGタイプのメッセージは表示しない。
 * (開発環境では表示して、本番では表示しない時に使う。)
 * 
 * AbstractModelを継承して、セッションを扱えるようにする。
 */


namespace lib;

use model\AbstractModel;
use Throwable;


class Msg extends AbstractModel {

    protected static $SESSION_NAME = '_msg';

    // メッセージのタイプを3つに分ける。
    public const ERROR = 'error';
    public const INFO = 'info';
    public const DEBUG = 'debug';   


    /**
     * 指定されたtypeのメッセージを入れるためのメソッド
     * 
     * @param string $type ERROR or INFO or DEBUG
     * @param string $msg メッセージ
     */
    public static function push($type, $msg) {
        // 配列が取れてこなかった場合は、セッションにメッセージ用の配列を初期化する。
        if (!is_array(static::getSession())) {
            static::init();
        }
        // メッセージセッション取得して、$typeのメッセージを連想配列に格納
        $msgs = static::getSession();
        $msgs[$type][] = $msg;

        // 連想配列をセッションに格納
        static::setSession($msgs);
    }

    /**
     * セッションに格納されているメッセージを取得
     */
    public static function flush() {
        try {
            // セッション取得
            $msgs_with_type = static::getSessionAndFlush() ?? [];
            foreach ($msgs_with_type as $type=>$msgs) {
                                                // デバック定数
                if ($type === static::DEBUG && !DEBUG) {
                    continue;
                }
    
                foreach ($msgs as $msg) {
                    echo "<div>{$type}:{$msg}</div>";
                }
            }

        } catch(Throwable $e) {
            Msg::push(Msg::DEBUG, $e->getMessage());
            Msg::push(Msg::DEBUG, 'Msg::flushで例外が発生しました。');
        }
    }


    /**
     * メッセージ用セッションの初期化メソッド
     */
    private static function init() {
        // 配列として、３つのメッセージを初期化する
        static::setSession([
            static::ERROR => [],
            static::INFO => [],
            static::DEBUG => []
        ]);
    }
}

?>