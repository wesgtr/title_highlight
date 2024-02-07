<?php

class Highlight_Title_Plugin {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_custom_metabox'));
        add_action('save_post', array($this, 'save_meta'));
        add_filter('the_title', array($this, 'modify_title'), 10, 2);
    }

    public function add_custom_metabox() {
        $screens = ['post', 'page'];
        foreach ($screens as $screen) {
            add_meta_box(
                'highlight_title_metabox',
                __('Highlight Title', 'text-domain'),
                array($this, 'metabox_callback'),
                $screen,
                'side'
            );
        }
    }

    public function metabox_callback($post) {
        $highlight_title = get_post_meta($post->ID, '_highlight_title', true);
        wp_nonce_field('save_highlight_title', 'highlight_title_nonce');
        echo '<label for="highlight_title">' . __('Word or Phrase to be Highlighted:', 'text-domain') . '</label>';
        echo '<input type="text" id="highlight_title" name="highlight_title" value="' . esc_attr($highlight_title) . '">';
    }

    public function save_meta($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (wp_is_post_revision($post_id)) return;
        if (isset($_POST['post_type']) && 'post' == $_POST['post_type'] && !current_user_can('edit_post', $post_id)) return;
        if (!isset($_POST['highlight_title_nonce']) || !wp_verify_nonce($_POST['highlight_title_nonce'], 'save_highlight_title')) return;

        if (isset($_POST['highlight_title'])) {
            update_post_meta($post_id, '_highlight_title', sanitize_text_field($_POST['highlight_title']));
        }
    }

    public function modify_title($title, $id = null) {
        if (is_admin() || empty($id)) {
            return $title;
        }

        $highlight_title = get_post_meta($id, '_highlight_title', true);
        if (!empty($highlight_title)) {
            $title = str_replace($highlight_title, '<span class="highlight">' . esc_html($highlight_title) . '</span>', $title);
        }
        return $title;
    }
}

new Highlight_Title_Plugin();
