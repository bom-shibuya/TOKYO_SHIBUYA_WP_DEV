<?php
  $big = 999999999999999999;
  $pagination_setting = array(
    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    'format' => '?page=%#%',
    'current' => max(1, get_query_var('paged')),
    'total' => $wp_query->max_num_pages,
    'type' => 'list'
  );
  echo paginate_links($pagination_setting);
?>