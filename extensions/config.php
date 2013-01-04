<?php

class Config {

  public static $root_folder = './';
  public static $app_folder = './app';
  public static $content_folder = './content';
  public static $templates_folder = './templates';
  public static $cache_folder = './app/_cache';
  public static $extensions_folder = './extensions';
  public static $languages = array(
    'default' => 'en',
    'available' => array(
      'en' => 'English',
      'el' => 'Ελληνικά',
    ),
  );

}

?>
