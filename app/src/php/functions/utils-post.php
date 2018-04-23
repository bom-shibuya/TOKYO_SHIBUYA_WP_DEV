<?php

  // ******* utils for post setting *******

  /**
  * remove <p></p> from content && excerpt
  */
  remove_filter('the_content', 'wpautop');
  remove_filter('the_excerpt', 'wpautop');

  // prevent setting multibyte string to the slug
  function auto_post_slug($slug, $post_ID, $post_status, $post_type) {
    if (preg_match('/(%[0-9a-f]{2})+/', $slug)) {
      $slug = utf8_uri_encode($post_type).'-'.$post_ID;
    }
    return $slug;
  }
  add_filter('wp_unique_post_slug', 'auto_post_slug', 10, 4);

  // custom editor setting
  function wpdocs_theme_add_editor_styles() {
    add_editor_style('editor-style.css');
  }
  add_action('admin_init', 'wpdocs_theme_add_editor_styles');

  // editor paragraph => only [p, h1, h2, h3]
  function custom_editor_settings($settings) {
    $settings['block_formats'] = '段落=p;見出し1=h1;見出し2=h2;見出し3=h3;見出し4=h4;見出し5=h5;見出し6=h6;';
    return $settings;
  }
  add_filter('tiny_mce_before_init','custom_editor_settings');

  // use eyecatch
  add_theme_support('post-thumbnails');

  // get eyecatch url. if eyecatch is nothing, return null.
  function get_eyecatch_url($alt_img = null, $size = 'full') {
    $eyecatch_id = get_post_thumbnail_id();
    $image_source = wp_get_attachment_image_src($eyecatch_id, $size);
    if ($image_source) {
      return $image_source[0];
    } else if ($alt_img) {
      return $alt_img;
    } else {
      return null;
    }
  }

  // if using yoast SEO, get primary category.
  function get_primary_category() {
    $category;
    // use yoast SEO
    if (class_exists('WPSEO_Primary_Term')) {
      $wpseo_primary_term = new WPSEO_Primary_Term('category', get_the_id());
      $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
      $term = get_term($wpseo_primary_term);
      if (is_wp_error($term)) {
        // if we get error, use first category.
        $category = get_the_category();
        $category = $category[0];
      } else {
        $category = $term;
      }
    }

    return $category;
  }
