<?php
  // 全体の設定
  get_template_part('functions/utils');
  // meta情報の削除だったりassets類の読み込み
  get_template_part('functions/meta');
  // 投稿関係の設定
  get_template_part('functions/utils', 'post');
  // アーカイブ用ページネーションの設定
  get_template_part('functions/pager', 'archive');
  // 管理画面の設定
  get_template_part('functions/utils', 'dashbord');
  // プラグインの設定
  get_template_part('functions/utils', 'plugins');
