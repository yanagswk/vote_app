<?php 

namespace partials;

use lib\Auth;


/**
 * 画面全体 呼び出し元
 * 
 * @param object $topic ユーザー情報
 */
function topic_header_item($topic, $from_top_page) {
    ?>
        <div class="row">
            <div class="col">
                <!-- 左側 -->
                <?php chart($topic); ?>
            </div>
            <div class="col my-5">
                <!-- 右側 -->
                <?php topic_main($topic, $from_top_page); ?>
                <?php comment_form($topic); ?>
            </div>
        </div>
    <?php
}


/**
 * 円グラフ表示
 * 
 * @param object $topic ユーザー情報
 */
function chart($topic) {
    ?>

    <canvas id="chart" width="400" height="400" data-likes="<?php echo $topic->likes; ?>" data-dislikes="<?php echo $topic->dislikes; ?>"></canvas>
        <style>
            #chart {
                background-color: gray;
            }
        </style>
    <?php
}



/**
 * メイン画面。
 * トップページから来た場合は、リンクで表示する。
 * 
 * @param object $topic ユーザー情報
 */
function topic_main($topic, $from_top_page) {
    ?>
        <div>
            <?php if ($from_top_page): ?>
                <h1 class="sr-only">みんなのアンケート</h1>
                <!-- トップページから来た場合、リンク表示 -->
                <h2 class="h1">
                    <a class="text-body" href="<?php the_url('topic/detail?topic_id=' . $topic->id); ?>">
                        <?php echo $topic->title; ?>
                    </a>
                </h2>
            <?php else: ?>
                <!-- 普通に文字を表示 -->
                <h1><?php echo $topic->title; ?></h1>
            <?php endif; ?>
            <span class="mr-1 h5">Posted by <?php echo $topic->nickname; ?></span>
            <span class="mr-1 h5">&bull;</span>
            <span class="h5"><?php echo $topic->views; ?> views</span>
        </div>
        <div class="container text-center my-4">
            <div class="row justify-content-around">
                <div class="likes-green col-auto">
                    <div class="display-1"><?php echo $topic->likes; ?></div>
                    <div class="h4 mb-0">賛成</div>
                </div>
                <div class="dislikes-red col-auto">
                    <div class="display-1"><?php echo $topic->dislikes; ?></div>
                    <div class="h4 mb-0">反対</div>
                </div>
            </div>
        </div>
    <?php
}


/**
 * コメントフォーム画面
 * 
 * @param object $topic ユーザー情報
 */
function comment_form($topic) {
    ?>
        <?php if (Auth::isLogin()): ?>
            <form action="<?php the_url('topic/detail'); ?>" method="POST">
                <span class="h4">あなたは賛成？それとも反対？</span>
                
                <!-- トピックID -->
                <input type="hidden" name="topic_id" value="<?php echo $topic->id; ?>">
                
                <div class="form-group">

                    <!-- コメントエリア -->
                    <textarea class="w-100 border-light" name="body" id="body" rows="5"></textarea>
                
                </div>

                <div class="container">
                    <div class="row h4 form-group">
                        <div class="col-auto d-flex align-items-center pl-0">
                            
                            
                            <div class="form-check-inline">
                                <!-- 賛成か反対か -->
                                <!-- <input class="form-check-input" type="radio" id="agree" name="agree" value="1" checked> -->
                                <input class="form-check-input" type="radio" id="agree" name="judge" value="1" checked>
                                <label for="agree" class="form-check-label">賛成</label>
                            </div>
                            <div class="form-check-inline">
                                <!-- <input class="form-check-input" type="radio" id="disagree" name="disagree" value="0"> -->
                                <input class="form-check-input" type="radio" id="disagree" name="judge" value="0">
                                <label for="disagree" class="form-check-label">反対</label>
                            </div>
                        </div>
                        

                        <input type="submit" value="送信" class="col btn btn-success shadow-sm">
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="text-center mt-5">
                <div class="mb-2">ログインしてアンケートに参加しよう！</div>
                <a href="<?php the_url("login"); ?>" class="btn btn-lg btn-success">ログインはこちら</a>
            </div>
        <?php endif; ?>
    <?php
}

?>