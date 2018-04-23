<?php

  // ******* utils *******

  /**
   * define contant
   */
  define('PATH_IMG', get_stylesheet_directory_uri().'/assets/img/');
  define('PATH_HOME', esc_url( home_url('/')));

  /**
   * utility function
   */
  function delete_newline ($str) {
    return str_replace(array("\r\n","\r","\n"), "", $str);
  }
