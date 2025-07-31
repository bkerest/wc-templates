<?php
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_script('monaco-loader', 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js', [], null, true);

    // Get dynamic snippets from DB
    $snippets = get_posts([
        'post_type' => 'wcdt_snippet',
        'numberposts' => -1,
    ]);
    $js_snippets = [];

    foreach ($snippets as $snippet) {
        $insert = get_post_meta($snippet->ID, '_insert_text', true);
        $doc = get_post_meta($snippet->ID, '_doc_text', true);
        $js_snippets[] = [
            'label' => $snippet->post_title,
            'insertText' => $insert,
            'documentation' => $doc,
        ];
    }

    $encoded_snippets = json_encode($js_snippets);

    wp_add_inline_script('monaco-loader', "
        require.config({ paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' }});
        require(['vs/editor/editor.main'], function () {
            var textarea = document.getElementById('content');
            if (!textarea) return;
            textarea.style.display = 'none';

            var container = document.createElement('div');
            container.id = 'monaco-editor';
            container.style.width = '100%';
            container.style.height = '600px';
            container.style.resize = 'vertical';
            textarea.parentNode.insertBefore(container, textarea.nextSibling);

            var model = monaco.editor.createModel(textarea.value, 'html');

            monaco.languages.registerCompletionItemProvider('html', {
                provideCompletionItems: () => {
                    return {
                        suggestions: " . $encoded_snippets . ".map(s => ({
                            label: s.label,
                            kind: monaco.languages.CompletionItemKind.Snippet,
                            insertText: s.insertText,
                            insertTextRules: monaco.languages.CompletionItemInsertTextRule.InsertAsSnippet,
                            documentation: s.documentation
                        }))
                    };
                }
            });

            var editor = monaco.editor.create(container, {
                model: model,
                theme: 'vs-dark',
                automaticLayout: true,
                fontSize: 14,
                minimap: { enabled: false },
                wordWrap: 'on',
                language: 'html',
                autoClosingBrackets: 'always',
                autoIndent: 'full'
            });

            // Add fullscreen toggle
            var btn = document.createElement('button');
            btn.textContent = '🖵';
            btn.style.position = 'absolute';
            btn.style.top = '4px';
            btn.style.right = '4px';
            btn.style.zIndex = '1000';
            btn.onclick = function () {
                container.classList.toggle('fullscreen');
                container.style.height = container.classList.contains('fullscreen') ? '90vh' : '600px';
            };
            container.parentNode.insertBefore(btn, container);

            textarea.form.addEventListener('submit', function () {
                textarea.value = editor.getValue();
            });
        });
    ");
});
