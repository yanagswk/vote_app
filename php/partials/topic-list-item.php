<?php
/**
 * トピック画面表示
 */

namespace partials;

/**
 * トピック表示
 * 
 * @param object $topic ユーザー情報
 * @param string $title_url 詳細画面へのurl
 * @param bool $with_status ラベル表示(公開,非公開) trueで公開、falseで非公開
 */
function topic_list_item($topic, $title_url, $with_status) {

    // publishedが1(true)で公開、0(false)で非公開
    $published_label = $topic->published ? '公開' : '非公開';
    $published_cls = $topic->published ? 'badge-primary' : 'badge-danger';
?>

    <li class="topic row bg-white shadow-sm mb-3 rounded p-3">
        <div class="col-md d-flex align-items-center">
            <h2 class="mb-2 mb-md-0">

                <?php if ($with_status) :  ?>
                    <!-- ラベル表示(公開,非公開) -->
                    <span class="badge mr-1 align-bottom <?php echo $published_cls; ?>"><?php echo $published_label; ?></span>
                <?php endif; ?>

                <a class="text-body" href="<?php echo $title_url; ?>"><?php echo $topic->title ?></a>
            </h2>
        </div>
        <div class="col-auto mx-auto">
            <div class="text-center row">
                <div class="view col-auto min-w-100">
                    <div class="h1 mb-0"><?php echo $topic->views ?></div>
                    <div class="mb-0">Views</div>
                </div>
                <div class="likes-green col-auto min-w-100">
                    <div class="h1 mb-0"><?php echo $topic->likes ?></div>
                    <div class="mb-0">賛成</div>
                </div>
                <div class="dislikes-red col-auto min-w-100">
                    <div class="h1 mb-0"><?php echo $topic->dislikes ?></div>
                    <div class="mb-0">反対</div>
                </div>
            </div>
        </div>
    </li>

<?php 
}
?>