<?
class Controller {
	public $params;
	public $path;

	function __construct($path, $params){
		$this->params = $params;
		$this->path = $path;
		$this->route();
	}

	function route(){
		if (is_array($this->path) && !empty($this->path)){
			$match = false;
			$i = count($this->path);
			while ($i >= 0){
				$offset = $i > 0 ? $i-1 : 0;
				$method = $this->path[$offset];
				if (method_exists($this, $method)){
					$match = true;
					$this->path = array_splice($this->path, $i, 1);
					$this->$method();
					break;
				}
				$i--;
			}
			if (!$match)
				$this->index();
		}

		else
			$this->index();
	}

	function render_template($template, $data){
		global $VIEWPATH;

		extract($data);                     // makes array keys their own variables
		ob_start();                         // everything after this is written into memory
		include($VIEWPATH.$template);       // load the template file
		$this->contents = ob_get_clean();   // capture template file into varible
		echo $this->contents;
	}
}

class DefaultIndex {
	function __construct(){
		echo 'Hello, world. This is the default home page. You should write a Home controller to replace it.';
	}
}
?>
