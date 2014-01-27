<?
include('base/models.php');

class User extends Model{
    public $columns = array('id', 'username', 'first_name', 'last_name', 'email');
}
?>
