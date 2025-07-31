<?php
// File: includes/editor-monaco.php

if (!defined('ABSPATH')) exit;

// Add admin menu item
add_action('admin_menu', function () {
    add_submenu_page(
        'options-general.php',
        'HTML Code Editor',
        'Template Editor',
        'manage_options',
        'html-code-editor',
        'wcdt_render_monaco_editor'
    );
});

// Render Monaco Editor
function wcdt_render_monaco_editor()
{
    $snippets = get_option('wcdt_snippets', []);

    echo '<div class="wrap"><h1>Template Code Editor</h1>';
    echo '<div id="monaco-editor" style="height: 700px; border: 1px solid #666;"></div>';
    echo '</div>';

    echo '<script src="https://unpkg.com/monaco-editor@latest/min/vs/loader.js"></script>';
    echo '<script>
        const customSnippets = ' . json_encode(array_values($snippets)) . ';
        
        require.config({ paths: { vs: "https://unpkg.com/monaco-editor@latest/min/vs" }});
        require(["vs/editor/editor.main"], function () {
            const editor = monaco.editor.create(document.getElementById("monaco-editor"), {
                value: "<!-- Γράψε HTML template εδώ -->\\n",
                language: "html",
                theme: "vs-dark",
                automaticLayout: true,
                fontSize: 14,
            });

            monaco.languages.registerCompletionItemProvider("html", {
                provideCompletionItems: () => {
                    const suggestions = customSnippets.map((s, i) => ({
                        label: s.trigger,
                        kind: monaco.languages.CompletionItemKind.Snippet,
                        insertText: s.expansion,
                        documentation: s.description,
                        insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                    }));
                    return { suggestions };
                }
            });
        });
    </script>';
}
