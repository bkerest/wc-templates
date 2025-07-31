<?php
add_action('admin_menu', function () {
    add_submenu_page('options-general.php', 'WCDT AI Settings', 'WCDT AI Settings', 'manage_options', 'wcdt-ai-settings', function () {
        echo '<div class="wrap"><h1>WCDT AI Settings</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields('wcdt_ai');
        do_settings_sections('wcdt_ai');
        submit_button();
        echo '</form></div>';
    });
});

add_action('admin_init', function () {
    register_setting('wcdt_ai', 'wcdt_openai_key');
    add_settings_section('wcdt_ai_section', 'API Key', null, 'wcdt_ai');
    add_settings_field('wcdt_openai_key', 'OpenAI API Key', function () {
        $key = get_option('wcdt_openai_key', '');
        echo '<input type="text" name="wcdt_openai_key" value="' . esc_attr($key) . '" style="width:400px;" />';
    }, 'wcdt_ai', 'wcdt_ai_section');
});
