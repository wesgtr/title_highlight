<?php

class Highlight_Title_Styles {

    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    public function enqueue_styles() {
        $plugin_url = plugin_dir_url(__DIR__);
        wp_enqueue_style(
            'highlight_title_styles',
            $plugin_url . 'css/highlight_title.css'
        );
    }
}

new Highlight_Title_Styles();
