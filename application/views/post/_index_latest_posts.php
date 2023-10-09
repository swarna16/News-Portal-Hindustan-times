<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$count = 1;
foreach ($latest_posts as $post):
    if ($count > $skip && $count <= $visible_posts_count):?>
        <?php $this->load->view("post/_post_item_horizontal", ["post" => $post, "show_label" => true]); ?>
    <?php
    endif;
    $count++;
endforeach; ?>
<?php if ($total_posts_count - $visible_posts_count < 1): ?>
    <style>
        .btn-load-more {
            display: none;
        }
    </style>
<?php endif; ?>

