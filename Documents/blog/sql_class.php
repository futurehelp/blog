<?
class MySQL {
    var $conId; // connection identifier
    var $host; // MySQL host
    var $user; // MySQL username
    var $password; // MySQL password
    var $database; // MySQL database
    // constructor
    function MySQL($options=array()){
        // validate incoming parameters
        if(count($options)>0){
            foreach($options as $parameter=>$value){
                if(empty($value)){
                    trigger_error('Invalid parameter'.$parameter,E_USER_ERROR);
                }
                $this->{$parameter}=$value;
            }
            // connect to MySQL
            $this->connectDB();
        }
        else {
            trigger_error('No connection parameters were provided',E_USER_ERROR);
        }
    }
    // connect to MYSQL server and select database
    function connectDB(){
        if(!$this->conId=mysql_connect($this->host,$this->user,$this->password)){
            trigger_error('Error connecting to the server',E_USER_ERROR);
        }
        if(!mysql_select_db($this->database,$this->conId)){
            trigger_error('Error selecting database',E_USER_ERROR);
        }
    }
    // perform query
    function query($query){
        if(!$this->result=mysql_query($query,$this->conId)){
            trigger_error('Error performing query'.$query,E_USER_ERROR);
        }
        // return new Result object
        return new Result($this,$this->result); 
    }

}

class Result {
    var $mysql; // instance of MySQL object
    var $result; // result set
    function Result(&$mysql,$result){
        $this->mysql=&$mysql;
        $this->result=$result;
    }
    // fetch row
	
    function fetchRow(){
        return mysql_fetch_array($this->result,MYSQL_ASSOC);
    }

    // count rows
    function countRows(){
        if(!$rows=mysql_num_rows($this->result)){
            return false;
        }
        return $rows;
    }
    // count affected rows
    function countAffectedRows(){
        if(!$rows=mysql_affected_rows($this->mysql->conId)){
            trigger_error('Error counting affected rows',E_USER_ERROR);
        }
        return $rows;
    }
    // get ID from last inserted row
    function getInsertID(){
        if(!$id=mysql_insert_id($this->mysql->conId)){
            trigger_error('Error getting ID',E_USER_ERROR);
        }
        return $id;
    }
    // seek row
    function seekRow($row=0){
        if(!mysql_data_seek($this->result,$row)){
            trigger_error('Error seeking data',E_USER_ERROR);
        }
    }
    function getQueryResource(){
        return $this->result;
    }
}
?>