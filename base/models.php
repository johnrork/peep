<?
try {
    $db = new PDO($DB);
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}

catch (PDOException $e) {
    die("Could not open database. " . $e->getMessage());
}


class Model {
    protected $hasPK = True;
    protected $table;
    protected $columns = array();
    protected $query;
    protected $db;
    protected $statement;
    protected $limit;
    protected $order;
    protected $offset;
    protected $params;

    function __construct($values=null){
        global $db;
        $this->db = $db;
        if (!$this->table)
            $this->table = get_class($this);

        if (empty($this->columns))
            $this->columns = array_keys(call_user_func('get_object_vars', $this));

        if($values){
            if(is_numeric(join(array_keys($values)) )){
                if (count($values) == count($this->columns))
                    $this->values = $values;
                else if (count($values) == count($this->columns) - 1){
                    array_unshift($values, '');
                    $this->values = $values;
                }
                else {
                    echo 'Not enough arguments supplied to model.';
                    throw new Exception("Not enough arguments supplied to model.");
                }

                foreach ($this->columns as $i => $col){
                    $this->$col = $values[$i];
                }
            }

            else{
                foreach ($values as $key => $value) {
                    if (in_array($key, $this->columns))
                        $this->$key = $value;
                }
            }

        }
    }

    function __toString(){
        $repr = "User object: ".join(', ', $this->fields_to_array());
        return $repr;
    }

    function fields_to_array(){
        $fields = array();
        $columns = $this->columns;
        if (!$this->id)
            array_shift($columns);

        foreach ($columns as $col){
            $fields[$col] = $this->$col;
        }
        return $fields;
    }

    function columns_to_string(){
        $columns = $this->columns;

        if (!$this->id)
            array_shift($columns);
        return join(', ', $columns);
    }

    function columns_to_placeholders(){
        $ph = array();
        $columns = $this->columns;
        if (!$this->id)
            array_shift($columns);

        foreach ($columns as $col) {
            array_push($ph, ":$col");
        }

        return join(', ', $ph);
    }

    function get($id=null){
        $this->query = "SELECT * FROM ".$this->table." where id = :id";
        $this->tryExecute(array($id));
        $results = $this->statement->fetch();

        if (is_array($results)){
            $this->__construct($results);
            return $this;
        }

        else
            return False;
    }

    function filter($query_array){
        $this->query = "SELECT * FROM ".$this->table." where ";

        $i = 0;
        foreach ($query_array as $key => $value) {
            if ($i > 0)
                $this->query .= ' and ';
            $this->query .= " $key = :$key ";
            $i++;
        }
        $this->params = $query_array;
        return $this;
    }

    function first(){
        $this->statement->setFetchMode(PDO::FETCH_INTO, $this);
        return $this->statement->fetch();
    }

    function all(){
        if (!$this->query){
            return $this->selectAll();
        }
        else{
            $this->tryExecute();
            $this->statement->setFetchMode(PDO::FETCH_INTO, $this);
            return $this->statement->fetchAll();
        }
    }

    function selectAll(){
        $this->query = "SELECT * from ".$this->table;
        return $this->all();
    }

    function create(){
        $this->query = "INSERT INTO ".$this->table." (".
                            $this->columns_to_string().
                        ") values (".
                            $this->columns_to_placeholders().
                        ")";

        if($this->tryExecute($this->fields_to_array()))
            return $this;

        else return false;
    }

    function update(){
        $this->query = "UPDATE ".$this->table." set ";

        foreach($this->fields_to_array() as $key => $value){
            $this->query .= $key . ' = ' . ":$key, ";
        }

        $this->query = rtrim($this->query, ', ');
        $this->query .= ' where id = :id';

        $this->tryExecute((array)$this->fields_to_array());
    }

    function delete(){
        $this->query = "delete from ".$this->table.
                        " where id = :id";
        if ($this->tryExecute(array($this->id))){
            return true;
        }
    }

    function limit($limit){
        $this->limit = $limit;
        return $this;
    }

    function offset($offset){
        $this->offset = $offset;
        return $this;
    }

    function order($order){
        if (is_array($order)){
            foreach ($order as $k => $v) {
                if (is_numeric($k))
                    $this->order .= "$v, ";
                else
                    $this->order .= "$k $v, ";
            }
            $this->order = rtrim($this->order, ', ');
        }
        else $this->order = $order;
        return $this;
    }

    function tryExecute($params=null){
        if (!$params && $this->params){
            $params = $this->params;
            // print_r($params);
            // echo $this->query;
        }
        try{
            if ($this->order)
                $this->query .= ' ORDER BY ' . $this->order;
            if ($this->limit)
                $this->query .= ' LIMIT '.$this->limit;
            if ($this->offset)
                $this->query .= ' OFFSET '.$this->offset;


            $this->statement = $this->db->prepare($this->query);
            $this->statement->execute($params);
            $this->query = null;
            return true;
        }

        catch (PDOException $e){
            echo $this->query."<br>";
            echo $e->getMessage();
        }
    }
}
?>
