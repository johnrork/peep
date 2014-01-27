<?
include('models.php');

class Home extends Controller {
	function index(){
		$this->render_template('index.php',
								$data = array('message' =>'Welcome to peep'));
	}
}

class Users extends Controller{
    function index(){
        $users = new User();

        if (!$users->get(1)){
            $u1 = new User(array(
                           'johnrork',
                           'john',
                           'rork',
                           'jnrork@gmail.com'));
            $u1->create();
        }

        $this->render_template('userlist.php',
                               $data = array('users' => $users->all() ));

    }
}
?>
