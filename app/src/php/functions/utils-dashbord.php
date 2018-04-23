<?php

  // ******* utils for dashbord *******

  // change menu list. change labels && remove item form menu.
  function change_menulist() {
    global $menu;
    global $submenu;
    // var_dump($menu);
    // var_dump($submenu);
    // $menu[5][0] = 'ニュース'; // 投稿をニュースに変更
    // $submenu['edit.php'][5][0] = 'ニュース一覧'; // 投稿一覧をニュース一覧に変更
    unset($menu[25]); // コメント
    unset($menu[75]); // ツール
  }
  add_action('admin_menu', 'change_menulist');

  // hide taxonomy from menu
  function hide_taxonomy_from_menu() {
    global $wp_taxonomies;

    // category
    // if (!empty($wp_taxonomies['category'] -> object_type)) {
    //   foreach ($wp_taxonomies['category'] -> object_type as $i => $object_type) {
    //     if ($object_type == 'post') {
    //       unset($wp_taxonomies['category'] -> object_type[$i]);
    //     }
    //   }
    // }

    // tag
    if (!empty($wp_taxonomies['post_tag'] -> object_type)) {
      foreach ($wp_taxonomies['post_tag'] -> object_type as $i => $object_type) {
        if ($object_type == 'post') {
          unset($wp_taxonomies['post_tag'] -> object_type[$i]);
        }
      }
    }
  }
  // add_action('init', 'hide_taxonomy_from_menu');

  function remove_dashbord_menu_for_not_admin () {
    global $wp_meta_boxes;
    // 現在の状況（概要）
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    // 最近のコメント
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    // 被リンク
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    // プラグイン
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    // クイック投稿
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    // 最近の下書き
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
    // WordPressブログ
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    // WordPressフォーラム
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    // yoast seo
    remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'side' );
  }

  function remove_admin_bar_menus( $wp_admin_bar ) {
    $wp_admin_bar->remove_node('wp-logo'); // WordPressマーク
    $wp_admin_bar->remove_node('view-site'); // サイトを表示
    $wp_admin_bar->remove_node('dashboard'); // ダッシュボード
    $wp_admin_bar->remove_node('comments'); // コメント
    $wp_admin_bar->remove_menu('updates'); // 更新
  }
  

  // customize not admin user display
  if (!is_super_admin()) {
    // remove version up infomation
    add_filter( 'pre_site_transient_update_core', '__return_zero' );
    // remove welcome panel
    remove_action( 'welcome_panel', 'wp_welcome_panel' );
    // remove dashbord boxes
    add_action('wp_dashboard_setup', 'remove_dashbord_menu_for_not_admin');
    // menu bar
    add_action( 'admin_bar_menu', 'remove_admin_bar_menus', 100 );
  }