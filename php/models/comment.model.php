<?php 

namespace model;

use lib\Msg;


class CommentModel extends AbstractModel{
    public int $id;
    public int $topic_id;
    public int $agree;
    public string $body;
    public string $user_id;
    public string $nickname;
    public int $del_flg;

    // 特定のメソッドを通じて値を取得するために_がついている
    protected static $SESSION_NAME = '_comment';

    // /**
    //  * ユーザーIDのバリデーションチェック
    //  */
    // public function isValidId() {
    //     return static::validateId($this->id);
    // }


    // /**
    //  * ログインページユーザーID、バリデーションチェックメソッド
    //  */
    // public static function validateId($val) {
    //     $res = true;

    //     // ユーザーIDが空のとき
    //     if (empty($val)) {
    //         Msg::push(Msg::ERROR, 'ユーザーIDを入力してください。');
    //         $res = false;
    //     } else {
    //         // ユーザーIDが10桁以上のとき
    //         if (strlen($val) > 10) {
    //             Msg::push(Msg::ERROR, 'ユーザーIDは10桁以下で入力してください。');               
    //             $res = false;
    //         }
    //         // 半角英数字か
    //         if (!is_alnum($val)) {
    //             Msg::push(Msg::ERROR, 'ユーザーID半角英数字で入力してください。');               
    //             $res = false;
    //         }
    //     }
    //     return $res;
    // }


    // public static function validatePwd($val){
    //     $res = true;
    //     if (empty($val)) {
    //         Msg::push(Msg::ERROR, 'パスワードを入力してください。');
    //         $res = false;
    //     } else {
    //         if(strlen($val) < 4) {
    //             Msg::push(Msg::ERROR, 'パスワードは４桁以上で入力してください。');
    //             $res = false;
    //         } 
    //         if(!is_alnum($val)) {
    //             Msg::push(Msg::ERROR, 'パスワードは半角英数字で入力してください。');
    //             $res = false;
    //         }
    //     }
    //     return $res;
    // }


    // public function isValidPwd(){
    //     return static::validatePwd($this->pwd);
    // }



    // public static function validateNickname($val){
    //     $res = true;
    //     if (empty($val)) {
    //         Msg::push(Msg::ERROR, 'ニックネームを入力してください。');
    //         $res = false;
    //     } else {
    //         if(mb_strlen($val) > 10) {
    //             Msg::push(Msg::ERROR, 'ニックネームは１０桁以下で入力してください。');
    //             $res = false;       
    //         } 
    //     }
    //     return $res;
    // }


    // public function isValidNickname(){
    //     return static::validateNickname($this->nickname);
    // }
}

?>
