<?php
require_once("postgres_heroku_db.php");
class User{
    // database connection and table name
    protected $dbObj;
    public $table_name = "users";
  
    // object properties
    protected $entry_id = '';
    protected $name = '';
    protected $password = '';
    protected $publicdesc = '';
    protected $privatedesc = '';
  
    public function __construct(){
        $this->dbObj = new postgres_heroku_DBI();
    }

    //Sets the internal variable values to those defined in the data array.
    //also hashes passwords.
    function setValues($dataarray){
        if(isset($dataarray['password'])){
            $dataarray['password'] = $this->_passhash($dataarray['password']);
        }
        foreach($dataarray as $key => $value){
            if(isset($this->$key)){
                $this->$key = $value;
            }
        }
        if(empty($this->name) || empty($this->password)){
            return false;
        } else{
            return true;
        }
    }

    //function addUser
    //adds the user based on the internal values.
    //name and password must not be null.
    //returns true on success or false otherwise.
    function addUser(){
        if(empty($this->name) || empty($this->password)){
            return false;
        }
        //create data array to implode
        $user = array(
            'name'=>$this->name,
            'password'=>$this->password,
        );
        if(!empty($this->publicdesc)){
            $user['publicdesc'] = $this->publicdesc;
        }
        if(!empty($this->privatedesc)){
            $user['privatedesc'] = $this->privatedesc;
        }
        //create sql strings and execute
        $vals = "VALUES ('".implode("', '",$user)."')";
        $user = array_keys($user);
        $userquery = "INSERT INTO ".$this->dbObj->table_prefix.'.'.$this->table_name." (".implode(', ',$user).') ';
        return(
            array(
                'result'=>($this->dbObj->query($userquery . $vals) !== false),
                'sqlstring'=>$userquery . $vals,
            )
        );
    }

    //function editUser
    //edits an existing user based on internal values
    //name, and id must not be null.
    //password will be unchanged if left blank
    //returns true on success or false otherwise.
    function editUser(){
        if(empty($this->name) || empty($this->entry_id)){
            return false;
        }
        //create data array to implode
        $user = array(
            'name'=>$this->name,
        );
        if(!empty($this->publicdesc)){
            $user['publicdesc'] = $this->publicdesc;
        }
        if(!empty($this->privatedesc)){
            $user['privatedesc'] = $this->privatedesc;
        }
        if(!empty($this->password)){
            $user['password'] = $this->password;
        }
        //create sql strings and execute
        $sqlstring = "UPDATE ".$this->dbObj->table_prefix.'.'.$this->table_name. " SET ";
        foreach($user as $column => $value){
            $sqlstring .= " $column = '$value',";
        }
        $sqlstring = substr($sqlstring, 0, -1);
        $sqlstring .= " WHERE entry_id = '$this->entry_id'";
        return(
            array(
                'result'=>($this->dbObj->query($sqlstring) !== false),
                'sqlstring' => $sqlstring,
            )
        );
    }

    //function createTable
    //Creates a new user table.
    //returns true on success, false on failure.
    function createTable(){
        $tablequery = "CREATE TABLE ".$this->dbObj->table_prefix.'.'.$this->table_name." (
            entry_id serial PRIMARY KEY,
            name VARCHAR ( 100 ) NOT NULL,
            password VARCHAR ( 64 ) NOT NULL,
            publicdesc VARCHAR ( 2000 ),
            privatedesc VARCHAR ( 2000 )
        );";
        return($this->dbObj->query($tablequery) !== false);
    }

    //function userSearch
    //runs a sql query to find users with names like the given name and returns them.
    function userSearch($name){
        $name = strtolower($name);
        $sqlstring = 'SELECT * FROM '.$this->dbObj->table_prefix.'.'.$this->table_name.' WHERE LOWER(name) LIKE ' . "'%$name%' ORDER BY name";
        return(
            array(
                'result'=>$this->dbObj->query($sqlstring),
                'sqlstring'=>$sqlstring,
            )
        );
    }

    //function getUserByID
    //runs a sql query to find the user with the given ID
    function getUserByID($id){
        $sqlstring = 'SELECT * FROM '.$this->dbObj->table_prefix.'.'.$this->table_name.' WHERE entry_id = ' . "'$id'";
        return($this->dbObj->query($sqlstring));
    }

    function getUserByNameAndPassword($name, $password){
        $sqlstring = 'SELECT * FROM '.$this->dbObj->table_prefix.'.'.$this->table_name.' WHERE name = ' . "'$name'".' AND password = ' . "'".$this->_passhash($password)."'";
        return array(
            'result'=>$this->dbObj->query($sqlstring),
            'sqlstring'=>$sqlstring,
        );
    }

    
    function _passhash($password) {
        return hash('sha256', $password);
    }
}
?>