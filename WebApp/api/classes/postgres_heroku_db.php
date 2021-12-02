<?php
    class postgres_heroku_DBI{
        public $table_prefix = 'main';

        protected $dbname= "d31a3bbhmd66q4";
        protected $dbuser = "fmowufsnbrohyn";
        protected $dbpassword = "918eba83c85790418ad9af9915e7270f403fb3df6cf26556da15e762ad1ca1d1";
        protected $dbhost = "ec2-54-211-159-145.compute-1.amazonaws.com";
        protected $dbport = "5432";

        protected $connectionstr;
        protected $dbconn;

        function __construct() {
            $this->connectionstr = "host=$this->dbhost port=$this->dbport dbname=$this->dbname user=$this->dbuser password=$this->dbpassword";
        }

        /*function __destruct() {
            $this->_disconnect();
        }*/
        
        function _connect(){
            $this->dbconn = pg_connect($this->connectionstr);
        }
        
        function _disconnect(){
            if(!empty($this->dbconn)){
                pg_close($this->dbconn);
            }
        }

        //basic funtion to run a sql command. Returns any results.
        function query($query){
            $this->_connect();
            $resource = pg_query($this->dbconn, $query);
            $results = pg_fetch_all($resource);
            $this->_disconnect();
            return $results;
        }

        //drops schemas in database and creates a new main schema.
        function resetDB(){
            //delete existing schemas
            $results = $this->query('SELECT * FROM information_schema.schemata');
            foreach($results as $schema) {
                $schema_name = $schema['schema_name'];
                if($schema_name != 'pg_catalog' && $schema_name != 'information_schema'){
                    $dropsql = "DROP SCHEMA IF EXISTS $schema_name CASCADE";
                    if($this->query($dropsql) === false){
                        return false;
                    };
                }
            }
    
            //create new schema
            return($this->query("CREATE SCHEMA ".$this->table_prefix) !== false);
        }
    }
?>