<?php
/**
 * Plugin Name: WCDT Snippet Manager
 * Description: Προσθέτει δυνατότητα διαχείρισης Snippets για χρήση στον Monaco Editor.
 * Author: ELVIAL Digital
 * Version: 1.0
 */

if (!defined('ABSPATH')) exit;

// Add submenu under Settings
add_action('admin_menu', function () {
    add_submenu_page(
        'options-general.php',
        'Snippets',
        'Snippets',
        'manage_options',
        'wcdt-snippet-manager',
        'wcdt_render_snippet_manager'
    );
});

// Handle save
add_action('admin_post_wcdt_save_snippets', function () {
    if (!current_user_can('manage_options')) return;

    $triggers     = $_POST['trigger'] ?? [];
    $descriptions = $_POST['description'] ?? [];
    $expansions   = $_POST['expansion'] ?? [];

    $snippets = [];

    for ($i = 0; $i < count($triggers); $i++) {
        if (trim($triggers[$i]) !== '') {
            $snippets[] = [
                'trigger'     => sanitize_text_field($triggers[$i]),
                'description' => sanitize_text_field($descriptions[$i]),
                'expansion'   => wp_kses_post($expansions[$i])
            ];
        }
    }

    update_option('wcdt_snippets', $snippets);
    wp_redirect(admin_url('options-general.php?page=wcdt-snippet-manager&updated=true'));
    exit;
});

// Render the snippet manager UI
function wcdt_render_snippet_manager()
{
    $snippets = get_option('wcdt_snippets', []);
    ?>
    <div class="wrap">
        <h1>WCDT Snippet Manager</h1>

        <?php if (isset($_GET['updated'])): ?>
            <div class="notice notice-success"><p>Snippets ενημερώθηκαν με επιτυχία.</p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="wcdt_save_snippets" />
            <table class="widefat fixed" style="max-width:1000px">
                <thead>
                    <tr>
                        <th width="15%">Trigger</th>
                        <th width="25%">Description</th>
                        <th>Expansion (HTML)</th>
                    </tr>
                </thead>
                <tbody id="snippet-table-body">
                    <?php foreach ($snippets as $i => $s): ?>
                        <tr>
                            <td><input type="text" name="trigger[]" value="<?php echo esc_attr($s['trigger']); ?>" style="width: 100%;" /></td>
                            <td><input type="text" name="description[]" value="<?php echo esc_attr($s['description']); ?>" style="width: 100%;" /></td>
                            <td><textarea name="expansion[]" rows="3" style="width: 100%;"><?php echo esc_textarea($s['expansion']); ?></textarea></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td><input type="text" name="trigger[]" style="width: 100%;" /></td>
                        <td><input type="text" name="description[]" style="width: 100%;" /></td>
                        <td><textarea name="expansion[]" rows="3" style="width: 100%;"></textarea></td>
                    </tr>
                </tbody>
            </table>
            <p><button type="submit" class="button-primary">Αποθήκευση Snippets</button></p>
        </form>
        <p>Μπορείς να χρησιμοποιείς τα snippets στο editor γράφοντας τον trigger και πατώντας <strong>Ctrl + Space</strong>.</p>
    </div>
    <?php
}
