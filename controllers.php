<?

class Home extends Controller {
	function index(){
		$this->render_template('index.php', 
								$data = array('users'=>$users));
	}
}

?>