<?php
  /**
    * pagination for archive. example
    * << prev 1 2 3 4 5 next >>
    * << prev 1 2 3 4 ... 7 next >>
    * << prev 1 ... 3 4 5 ... 7 next >>
    * << prev 1 ... 4 5 6 7 next >>
    * 
    * @param int $block_num => chunk size.
    * @param int $prev_$block_num => prev chunk size.
    * @param int $next_$block_num => next chunk size.
    * @return void this function is echo pager element.
    */
  function number_pagenations ($block_num = 4, $prev_block_num = 1, $next_block_num = 1) {
    // current page number
    global $paged;
    $current_page_num = $paged === 0 ? 1 : $paged;
    // get max page number
    global $wp_query;
    $max_page_num = $wp_query->max_num_pages;
    if ((int)$max_page_num === 1) {
      return;
    }

    $prev_num = $current_page_num - 1;
    $next_num = $current_page_num + 1;

    // for element;
    $pagenation_elements;

    function get_class_name ($num, $current_num) {
      $num_class_name = $current_num === (int)$num ? "num state-current" : "num";
      return $num_class_name;
    }

    function get_pager_element ($num, $current_num) {
      $pager_element = '<li><a class="'.get_class_name($num, $current_num).'" href="'.get_pagenum_link($num).'">'.$num.'</a></li>';
      return $pager_element;
    }

    function get_pager_omission () {
      return '<li>...</li>';
    }

    if ($block_num + 1 >= $max_page_num) {
      // 1 2 3 4 5
      for ($i = 1; $i <= $max_page_num; $i++) {
        $pagenation_elements .= get_pager_element($i, $current_page_num);
      }
    } else if ($current_page_num <= $block_num - 1) {
      // 1 2 3 4 ... 7
      for ($i = 1; $i <= $block_num; $i++) {
        $pagenation_elements .= get_pager_element($i, $current_page_num);
      }
      $pagenation_elements .= get_pager_omission();
      $pagenation_elements .= get_pager_element($max_page_num, $current_page_num);
    } else if ($current_page_num >= $max_page_num - $block_num + 1) {
      // 1 ... 4 5 6 7
      $pagenation_elements .= get_pager_element(1, $current_page_num);
      $pagenation_elements .= get_pager_omission();
      for ($i = $max_page_num - $block_num + 1; $i <= $max_page_num ; $i++) {
        $pagenation_elements .= get_pager_element($i, $current_page_num);
      }
    } else {
      // 1 ... 3 4 5 ... 7
      $pagenation_elements .= get_pager_element(1, $current_page_num);
      $pagenation_elements .= get_pager_omission();
      for ($i = $prev_block_num; $i > 0; $i--) {
        $pagenation_elements .= get_pager_element($current_page_num - $i, $current_page_num);
      }
      $pagenation_elements .= get_pager_element($current_page_num, $current_page_num);
      for ($i = 1; $i <= $next_block_num; $i++) {
        $pagenation_elements .= get_pager_element($current_page_num + $i, $current_page_num);
      }
      $pagenation_elements .= get_pager_omission();
      $pagenation_elements .= get_pager_element($max_page_num, $current_page_num);
    }

    if ($current_page_num !== 1) {
      $pagenation_elements = '<li class="prev"><a href="'.get_pagenum_link($prev_num).'">prev</a></li>'.$pagenation_elements;
    }
    if ($current_page_num !== (int)$max_page_num) {
      $pagenation_elements .= '<li class="next"><a href="'.get_pagenum_link($next_num).'">next</a></li>';
    }

    $pagenation_elements = '<ul class="m-pager">'.$pagenation_elements.'</ul>';
    echo $pagenation_elements;
  }

  // function get_pagination () {
  //   $big = 999999999999999999;
  //   $pagination_setting = array(
  //     'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
  //     'format' => '?page=%#%',
  //     'current' => max(1, get_query_var('paged')),
  //     'total' => $wp_query->max_num_pages,
  //     'type' => 'list'
  //   );
  //   echo paginate_links($pagination_setting);
  // }

