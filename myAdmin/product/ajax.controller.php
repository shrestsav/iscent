<?php

class product_ajaxController{

    private $db;
    private $post;
    private $exe;

    function __construct(){

        if (isset($GLOBALS['db'])) {
            $this->db = $GLOBALS['db'];
        }

        if( isset($_POST["ajx"]) && $_POST["ajx"]["auth"]==md5(session_id()) ){
            $this->post = $_POST;
            $this->exe = true;
        } else {
            $this->post = false;
            $this->exe = false;

            return false;
        }
    }

    public function goGreen(){
        return $this->exe;
    }

    public function test(){
        echo "haha this is controled";
    }


    public function control_execution(){
        if($this->exe) exit();
    }
}




$aj = new product_ajaxController();
if($aj->goGreen()){
    $aj->test();
    $aj->control_execution();
}


?>