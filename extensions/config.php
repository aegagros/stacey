<?php

class Config {

  public static $root_folder = './';
  public static $app_folder = './app';
  public static $content_folder = './content';
  public static $templates_folder = './templates';
  public static $cache_folder = './app/_cache';
  public static $extensions_folder = './extensions';
  /*
   * Add desired available languages here as follows
   *  'lang-code' => 'lang-name',
   *
   * 'lang-code' is used for path prefixes and text file lookup
   * 'lang-name' is used for language display name
   */
  public static $languages = array(
    'available' => array(
      'en' => 'English',
      'el' => 'Ελληνικά',
    ),
    # replace this with one of the above lang-codes to set default language, when none is specified in the url
    'default' => 'en',
  );

}

?>
