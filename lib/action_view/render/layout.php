<?

namespace Gearbox\ActionView\Render;
use Gearbox\ActionPatk as AcP;
use Gearbox\ActiveSupport as AcS;

class Layout{

  static $content = null;
  static $path = null;
  static $response = null;

  function __construct($path, $content){
      self::$content = $content;
      self::$path = $path;
  }

  function show_content(){
    return self::$content;
  }

  function __set($field, $value){
    AcP::setAttributes($field, $value);
  }

  function __get($field){
    return AcP::getAttributes($field);
  }


  function __call($method, $args){
    return AcP::callHelperMethod($method, $args);
  }

  function render(){

    ob_start();

    $text_controller = file_get_contents(AcP::contollerPath());
    preg_match_all("/(use[\sA-Za-z0-9\\\\]*;)/",$text_controller, $groups);

    $script = file_get_contents(self::$path);
    eval(
      "namespace Gearbox\ActionView\Render\HtmlView;"
      .implode('', $groups[0])
      ."?>$script<?"
    );

		self::$response = ob_get_contents();
		ob_end_clean();

    return self::$response;
  }


}


require_once __DIR__.'/html_view/form.php';
