<?php

  // ******* meta setting for view files *******

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

  // title_tag
  add_theme_support( 'title-tag' );