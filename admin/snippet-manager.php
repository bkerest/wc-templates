<?php
add_action('init', function () {
    register_post_type('wcdt_snippet', [
        'labels' => [
            'name' => 'WCDT Snippets',
            'singular_name' => 'WCDT Snippet'
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'options-general.php',
        'supports' => ['title'],
    ]);
});

add_action('add_meta_boxes', function () {
    add_meta_box('wcdt_snippet_meta', 'Snippet Details', function ($post) {
        $insert = get_post_meta($post->ID, '_insert_text', true);
        $doc = get_post_meta($post->ID, '_doc_text', true);
        echo '<label>Insert Text:</label><br>';
        echo '<textarea name="insert_text" style="width:100%;height:80px;">' . esc_textarea($insert) . '</textarea><br><br>';
        echo '<label>Documentation:</label><br>';
        echo '<textarea name="doc_text" style="width:100%;height:80px;">' . esc_textarea($doc) . '</textarea>';
    }, 'wcdt_snippet', 'normal', 'default');
});

add_action('save_post_wcdt_snippet', function ($post_id) {
    if (isset($_POST['insert_text'])) {
        update_post_meta($post_id, '_insert_text', sanitize_text_field($_POST['insert_text']));
    }
    if (isset($_POST['doc_text'])) {
        update_post_meta($post_id, '_doc_text', sanitize_text_field($_POST['doc_text']));
    }
});
