<?

namespace Gearbox\ActionView;

use Gearbox\ActionView\Render\HtmlView;
use Gearbox\ActionView\Render\Layout;
use Gearbox\ActionPatk as AcP;
use Gearbox\Engine;

class Render{

    static $echo = false;
    static $layout = false;
    static $layout_none =  'none';


    static function setReturn($echo){
      self::$echo = $echo;
    }
    static function renderReturn($return){
      if(self::$echo){
        echo $return;
        return null;
      } else{
        return $return;
      }
    }

    static function action($actionPatk, $options = []){
      $options['layout'] = isset($options['layout']) ? $options['layout'] : 'none';
      return AcP::newAction($actionPatk, $options);
    }

    static function view($view = null, $options = []){
      $view = empty($view) ? AcP::get('action_name') : $view;
      $format = AcP::get('format');
      $view_path = AcP::getView($view);
      $layout = isset($options['layout']) ? $options['layout'] : null;
      $vars = isset($options['vars']) ? $options['vars'] : [];

      $response = (new HtmlView($view_path, $vars))->render();

      return self::layout($response, $layout);
    }

    static function partial($partial, $vars = []){
      $partial = "_$partial";
      $partial_path = AcP::getPartial($partial);

      return self::renderReturn((new HtmlView($partial_path, $vars))->render());
    }

    static function layout($content, $layout = null){
      $format = AcP::get('format');
      if(empty($layout)){
          $layout = self::$layout ? self::$layout : AcP::get('layout_default');
      }

      if($layout == self::$layout_none){
        return $content;
      }

      $layout_path = Engine::baseDir()."/app/views/layouts/$layout.{$format}.php";
      if(file_exists($layout_path)){
        return self::renderReturn((new Layout($layout_path, $content))->render());
      } else {
        throw new \Exception("View não encontrada: {$layout}.{$format}.php");
      }
    }
}
