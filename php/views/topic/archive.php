<?php
/**
 * トピックメイン画面表示
 */


namespace view\topic\archive;

function index($topics) {
    ?>
    
        <h1 class="h2 mb-3">過去の投稿</h1>
        <ul class="container">
            <?php 
            foreach($topics as $topic) {
                $url = get_url('topic/edit?topic_id=' . $topic->id);
                
                // 過去の投稿ではラベルは表示
                \partials\topic_list_item($topic, $url, true);
            } 
            ?>
        </ul>
    
    
    <?php
}

?>