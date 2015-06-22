<?

namespace Gearbox\ActionView\Render;
use Gearbox\ActionPatk as AcP;
use Gearbox\ActiveSupport as AcS;

class HtmlView{

  static $vars = [];
  static $path = null;
  static $response = null;

  function __construct($path, $vars){
      self::$vars = $vars;
      self::$path = $path;
  }

  function render(){

    ob_start();

    if(!empty(self::$vars))
      extract(self::$vars);

    $text_controller = file_get_contents(AcP::contollerPath());
    preg_match_all("/(use[\sA-Za-z0-9\\\\]*;)/",$text_controller, $groups);

    $script = file_get_contents(self::$path);
    eval(
      "namespace Gearbox\ActionView\Render\HtmlView;"
      .implode('', $groups[0])
      ."use Helper\icon;"
      ."?>$script<?"
    );

    self::$response = ob_get_contents();
		ob_end_clean();

    return self::$response;
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

}


require_once __DIR__.'/html_view/form.php';
