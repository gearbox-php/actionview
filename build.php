<?

Gearbox\Engine::addGear([
	"name" => "Gearbox::ActionView",
	"loader" => function($class_name){
		if(Gearbox\Engine\Loader::classicGearLoader($class_name, 'ActionView', 'actionview/lib/action_view/')){
    	return true;
		}
		return false;
	}
]);
