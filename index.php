<?php 
require_once 'config.php';

// Library
require_once SOURCE_BASE . 'libs/helper.php';
require_once SOURCE_BASE . 'libs/auth.php';
require_once SOURCE_BASE . 'libs/router.php';

// Model
require_once SOURCE_BASE . 'models/abstract.model.php';
require_once SOURCE_BASE . 'models/user.model.php';

// Messgae
require_once SOURCE_BASE . 'libs/message.php';  // message.phpの中でabstract.model.phpを使っているため、ここに記述。

// DB
require_once SOURCE_BASE . 'db/datasource.php';
require_once SOURCE_BASE . 'db/user.query.php';

use function lib\route;

session_start();

try {
    require_once SOURCE_BASE . 'partials/header.php';
    
    $rpath = str_replace(BASE_CONTEXT_PATH, '', CURRENT_URI);
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    
    route($rpath, $method);

    // if($_SERVER['REQUEST_URI'] === 'login') {
    //     require_once SOURCE_BASE . 'controllers/login.php';
    // } elseif($_SERVER['REQUEST_URI'] === '/poll/part1/end/register') {
    //     require_once SOURCE_BASE . 'controllers/register.php';
    // } elseif($_SERVER['REQUEST_URI'] === '/poll/part1/end/') {
    //     require_once SOURCE_BASE . 'controllers/home.php';
    // }

    require_once SOURCE_BASE . 'partials/footer.php';

} catch(Throwable $e) {
    echo $e->getMessage();
    die('<h1>何かがすごくおかしいようです。</h1>');
    require_once SOURCE_BASE . "views/404.php";
}


?>
