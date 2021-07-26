<?php 
require_once 'config.php';

// Library
require_once SOURCE_BASE . 'libs/helper.php';
require_once SOURCE_BASE . 'libs/auth.php';
require_once SOURCE_BASE . 'libs/router.php';

// Model
require_once SOURCE_BASE . 'models/abstract.model.php';
require_once SOURCE_BASE . 'models/user.model.php';
require_once SOURCE_BASE . 'models/topic.model.php';
require_once SOURCE_BASE . 'models/comment.model.php';

// Message
require_once SOURCE_BASE . 'libs/message.php';  // message.phpの中でabstract.model.phpを使っているため、ここに記述。

// DB
require_once SOURCE_BASE . 'db/datasource.php';
require_once SOURCE_BASE . 'db/user.query.php';
require_once SOURCE_BASE . 'db/topic.query.php';
require_once SOURCE_BASE . 'db/comment.query.php';

// Partials
require_once SOURCE_BASE . 'partials/topic-list-item.php';
require_once SOURCE_BASE . 'partials/topic-header-item.php';
require_once SOURCE_BASE . 'partials/header.php';
require_once SOURCE_BASE . 'partials/footer.php';

// View
require_once SOURCE_BASE . 'views/home.php';
require_once SOURCE_BASE . 'views/login.php';
require_once SOURCE_BASE . 'views/register.php';
require_once SOURCE_BASE . 'views/topic/archive.php';
require_once SOURCE_BASE . 'views/topic/detail.php';

use function lib\route;

session_start();

try {
    // ヘッダー呼び出し
    \partials\header();

    // ディレクトリとファイルを分ける
    $url = parse_url(CURRENT_URI);

    $rpath = str_replace(BASE_CONTEXT_PATH, '', $url['path']);
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    
    // 表示画面呼び出し
    route($rpath, $method);

    // フッダー呼び出し
    \partials\footer();

} catch(Throwable $e) {
    echo $e->getMessage();
    die('<h1>何かがすごくおかしいようです。</h1>');
    require_once SOURCE_BASE . "views/404.php";
}


?>
