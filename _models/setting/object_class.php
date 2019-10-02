<?php
// useless file,, create just for PHP storm , it is Help guide when type any functions name.
class object_class
{
    public $db;
    public $dbF;
    public $functions;

    function __construct($number = '3')
    {
        $this->db = $GLOBALS['db'];
        $this->db = new Database();

        $this->dbF = $GLOBALS['dbF'];
        $this->dbF=new dbFunction();

        $this->functions = $GLOBALS['functions'];
        $this->functions=new admin_functions();
    }

}

class Database extends PDO{
    use global_setting;
}