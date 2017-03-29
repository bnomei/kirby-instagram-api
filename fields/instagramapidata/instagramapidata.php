<?php
class InstagramapidataField extends BaseField {

  static public $fieldname = 'instagramapidata';
  static public $assets = array(
    'js' => array(
      'script.js',
    ),
    'css' => array(
      'style.css',
    )
  );

  public function input() {
    
    // Load template with arguments
    $html = tpl::load( __DIR__ . DS . 'template.php', $data = array(
      'field' => $this,
      'fieldname' => self::$fieldname,
    ));
  
    return $html;
  }

  // Connecting PHP to Javascript - https://forum.getkirby.com/t/panel-field-javascript-click-does-not-work-after-save/3474/7
  public function element() {
    $element = parent::element();
    $element->data('field', self::$fieldname);
    return $element;
  }

  // Default value fallback
  public function val() {
    if($this->value() == "" && $this->default() !== "") {
      $value = $this->default();
    } elseif($this->value() == "" && $this->default() == "") {
      $value = "";
    } else {
      $value = $this->value();
    }
    return $value;
  }
}
