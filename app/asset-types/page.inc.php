<?php

Class Page {

  var $url_path;
  var $file_path;
  var $template_name;
  var $template_file;
  var $template_type;
  var $data;
  var $all_pages;

  function __construct($url, $content = false) {
    # store url and converted file path
    $this->file_path = Helpers::url_to_file_path($url);
    $this->url_path = $url;

    $this->template_name = self::template_name($this->file_path);
    $this->template_file = self::template_file($this->template_name);
    $this->template_type = self::template_type($this->template_file);

    # create/set all content variables
    PageData::create($this, $content);
  }

  function clean_json($data) {
    # strip any trailing commas
    # (run it twice to get partial matches)
    $data = preg_replace('/([}\]"][\s\n]*),([\s\n]*[}\]])/', '$1$2', $data);
    $data = preg_replace('/([}\]"][\s\n]*),([\s\n]*[}\]])/', '$1$2', $data);
    # strip newline characters
    $data = preg_replace('/\n/', '', $data);
    # minfy it
    $data = JSMin::minify($data);
    return $data;
  }

  function parse_template() {
    $data = TemplateParser::parse($this->data, $this->template_file);
    # post-parse JSON
    if (strtolower($this->template_type) == 'json') {
      $data = $this->clean_json($data);
    }
    return $data;
  }

  # magic variable assignment
  function __set($name, $value) {
    $this->data[strtolower($name)] = $value;
  }

  static function template_type($template_file) {
    preg_match('/\.([\w\d]+?)$/', $template_file, $ext);
    return isset($ext[1]) ? $ext[1] : false;
  }

  static function template_name($file_path) {
    # $pattern = '/\.(yml|txt)/';
    $language = Stacey::$language ? Stacey::$language : Config::$languages['default'];
    # find any language-specific text files
    $available = array_keys(Config::$languages['available']);
    $pattern = '/\.('.implode('|', $available).')\.(yml|txt)/';
    $txts = array_keys(Helpers::list_files($file_path, $pattern));
    if (!empty($txts)) {
      foreach($txts as $txt) {
        if (preg_match('/\.'.$language.'\.(yml|txt)/', $txt)) {
          return preg_replace('/\.'.$language.'\.(yml|txt)/', '', $txt);
        }
      }
    }
    $pattern = '/\.(yml|txt)/';
    $txts = array_keys(Helpers::list_files($file_path, $pattern));
    # return first matched .yml file
    return (!empty($txts)) ? preg_replace($pattern, '', $txts[0]) : false;
  }

  static function template_file($template_name) {
    $template_name = preg_replace('/([^.]*\.)?([^.]*)$/', '\\2', $template_name);
    $template_file = glob(Config::$templates_folder.'/'.$template_name.'.*');
    # return template if one exists
    return isset($template_file[0]) ? $template_file[0] : Config::$templates_folder.'/default.html';
  }

}

?>
