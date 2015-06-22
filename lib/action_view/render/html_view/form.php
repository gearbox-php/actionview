<?

namespace Gearbox\ActionView\Render\HtmlView;

function text_field_tag($field, $value = null, $options = []){
  return "<input type='text' name='$field' value='$value' />";
}
