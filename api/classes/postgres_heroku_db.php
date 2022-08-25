<?php
    class postgres_heroku_DBI{
        public $table_prefix = 'main';

        protected $dbname= "webapp-sandbox-server";
        protected $dbuser = "server";
        protected $dbpassword = "z0n1ED4y0s1qL8h9MVslSSXt0Nf";
        protected $dbhost = "localhost";
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
            //pg_fetch_all returns an empty array when there are no results in php 8
            //but false in php 7. this will make everything work for either case
            if($results === false && pg_result_error($results) === false){
                $results = array();
            }
            $this->_disconnect();
            return $results;
        }

        //drops schemas in database and creates a new main schema.
        function resetDB(){
            //delete existing schemas
            $results = $this->query('SELECT * FROM information_schema.schemata');
            foreach($results as $schema) {
                $schema_name = $schema['schema_name'];
                if($schema_name != 'pg_catalog' && $schema_name != 'information_schema' && $schema_name != 'pg_toast'){
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