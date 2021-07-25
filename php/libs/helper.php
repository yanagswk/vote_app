<?php
/**
 * 共通で行う処理を記述
 * 
 * $_POSTや$_GETなどのスーパーグロバールはいろんなところに記述するのではなく,
 * 一つの関数にまとめて、この関数から出ないとgetやpostにアクセスできないようにする。
 */


/**
 * getまたはpostのデータを返す
 * 
 * @param 
 */
function get_param($key, $default_val, $is_post=true) {
    $arry = $is_post ? $_POST : $_GET;
    return $arry[$key] ?? $default_val;
}

/**
 * リダイレクト処理関数
 * 
 * @param string $path パス
 */
function redirect($path) {
    if ($path === GO_HOME) {
        $path = get_url('');
    } elseif ($path === GO_REFERER) {
        // 一つ前のリクエストを返す
        $path = $_SERVER['HTTP_REFERER'];
    } else {
        $path = get_url($path);
    }

    // リダイレクトする。空文字の場合は、home.php
    header("Location: {$path}");
    die();
}


function get_url($path) {
    return BASE_CONTEXT_PATH . trim($path, '/');
}


// 半角英数字か正規表現を使ってチェックする
function is_alnum($val) {
    return preg_match("/^[a-zA-Z0-9]+$/", $val);
}

?>