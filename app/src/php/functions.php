<?php

  /**
   * define contant
   */
  define('PATH_IMG', get_stylesheet_directory_uri().'/assets/img/');

  /**
   * remove extra head descriptor
   */
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'feed_links', 2);
  remove_action('wp_head', 'feed_links_extra', 3);
  remove_action('wp_head', 'index_rel_link');

  // emoji
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles' );
  remove_action('admin_print_styles', 'print_emoji_styles');

  // rest
  remove_action('wp_head','rest_output_link_wp_head');
  remove_action('template_redirect', 'rest_output_link_header', 11 );

  // Embed
  remove_action('wp_head','wp_oembed_add_discovery_links');
  remove_action('wp_head','wp_oembed_add_host_js');

  /**
   * remove <p></p> from content && excerpt
   */
  remove_filter('the_content', 'wpautop');
  remove_filter('the_excerpt', 'wpautop');

  /**
   * load my Javascript && stylesheet
   */
  function load_my_assets(){
    //css
    wp_enqueue_style('main-css', get_stylesheet_directory_uri().'/assets/css/main.css');

    //javscript
    wp_enqueue_script('jquery');
    wp_enqueue_script('script-js', get_stylesheet_directory_uri().'/assets/js/script.js', array('jquery'),'',true);
  }
  add_action('wp_enqueue_scripts', 'load_my_assets');

  // type="text/javascript" to defer
  function replace_script_type_with_defer($tag) {
    return str_replace("type='text/javascript'", 'defer', $tag);
  }
  // add_filter('script_loader_tag', 'replace_script_type_with_defer');

  // custom editor setting
  function wpdocs_theme_add_editor_styles() {
    add_editor_style('style-editor.css');
  }
  add_action('admin_init', 'wpdocs_theme_add_editor_styles');

  // editor paragraph => only [p, h1, h2, h3]
  function custom_editor_settings($settings) {
    $settings['block_formats'] = '段落=p;見出し1=h1;見出し2=h2;見出し3=h3;';
    return $settings;
  }
  add_filter('tiny_mce_before_init','custom_editor_settings');

  // use eyecatch
  add_theme_support('post-thumbnails');