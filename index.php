<?
$DIRNAME  = array_pop(explode('/', dirname(__FILE__)));
$VIEWPATH = 'views/';
$DB = 'sqlite:peep.db';

include('base/controllers.php');

if(file_exists('controllers.php'))
	include('controllers.php');



if (strpos($_SERVER['REQUEST_URI'], '?'))
	list($path, $args) = explode(
						'?',
					   	$_SERVER['REQUEST_URI'],
						2 );

else
	list($path, $args) = array($_SERVER['REQUEST_URI'], null);

if ($args){
	$args = explode('&', $args);
	$params = array();

	foreach ($args as $arg){
		list($k, $v) = explode('=', $arg);
		$params[ $k ] = $v;
	}
}

else
	$params = '';

$path = array_filter(                               # remove empty elements
			explode( '/',                           # split the url path into an array
				str_replace(		                # remove the framework directory from the path
							$DIRNAME,               # the directory name
							null, 	                # replace with nothing
							$path                   # the url
							) ) );

$controller =  array_shift($path);                  # get the first part of the url (controller class name)

if (!$controller){                                  # no path?
	$controller = 'home';
	if (!class_exists($controller))                 # try for the Index class
		$controller = 'DefaultIndex';               # otherwise default to base/controllers.php's DefaultIndex class
}

if (!class_exists($controller))                     # catch paths without controllers
	echo sprintf(
			"There is no controller written to
			handle the path <strong>/%s</strong>.",
		 	strtolower($controller) );

else{
	$object = new $controller($path, $params);               # instantiate controller
}
?>
