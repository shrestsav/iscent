<?php
//don't encrypt this file
function _traitFileInclude($fileName){
    $webFile  =   __DIR__.'/../traits/'.$fileName;
    include_once($webFile);
}
_traitFileInclude("multi_lang.php");
//require_once(__DIR__.'/../traits/multi_lang.php');
//Handel Multi_admin language or webLanguage special words






class dbFunction{
    public $rowCount='';
    public $rowLastId='';
    public $rowException ='';
    public $hasException = false;
    private $db;

    use multi_lang;
    //Handel Multi_admin language or webLanguage special words

    public function __construct(){
        if (isset($GLOBALS['db'])) $this->db = $GLOBALS['db'];
        else  $this->db = new Database();

        $this->error_reportingIBMS($this->db->showErrorOnLocal,$this->db->showErrorOnLive);
        header('Content-Type: text/html; charset=utf-8');
        header("X-Frame-Options: SAMEORIGIN");

    }

    /**
     * @param bool $localhost
     * @param bool $live
     * Error reportng work on localhost hide on live.
     */
    public function error_reportingIBMS($localhost=true,$live=false){
        if($_SERVER['HTTP_HOST']=='localhost'){
            if($localhost){
                error_reporting(-1);
            }else{
                error_reporting(0);
            }
        }else{
            if($live){
                error_reporting(-1);
            }else{
                error_reporting(0);
            }
        }
    }

// simple print_r function

    public function prnt($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

// echo div when task perform succesfully

    public function success($txt,$echo=true){
       if($echo)
       {
           echo "<div
                style='text-align: center;
                padding:10px 0;
                border: 1px solid #26b3f7;
                background: #0972a5;
                font-weight: normal;
                color: #fff;
                border-radius:10px;
           '>$txt</div>";
       }else{
           return "<div
                style='text-align: center;
                padding:10px 0;
                border: 1px solid #26b3f7;
                background: #0972a5;
                font-weight: normal;
                color: #fff;
                border-radius:10px;
           '>$txt</div>";
       }
    }

// echo div when task perform fail

    public function fail($txt,$echo=true){
        if($echo)
         {
            echo "<div
            style='text-align: center;
            padding:10px 0;
            border: 1px solid #26b3f7;
            background: rgb(248,108,24);
            font-weight: normal;
            color: #fff;
            border-radius:10px;
            '>$txt</div>";
        }else{
            return "<div
            style='text-align: center;
            padding:10px 0;
            border: 1px solid #26b3f7;
            background: rgb(248,108,24);
            font-weight: normal;
            color: #fff;
            border-radius:10px;
            '>$txt</div>";
        }
    }

 // Error show
    public function warning($txt,$echo=true){
        if($echo)
        {
            echo "<div
            style='text-align: center;
            padding:10px 0;
            border: 1px solid #26b3f7;
            background:#FF000E ;
            font-weight: normal;
            color: #fff;
            border-radius:10px;
            '>$txt</div>";
        }else{
            return "<div
            style='text-align: center;
            padding:10px 0;
            border: 1px solid #26b3f7;
            background:#FF000E ;
            font-weight: normal;
            color: #fff;
            border-radius:10px;
            '>$txt</div>";
        }
    }


    /* required query with ? mark in where clause value.. and array of value to set in where clause
       example
        $qry="INSERT INTO staff(id,name) values(?,?)";
        $ary=array($id,$name);
        setRow($qry,ary);

        if ary is not set, and just full query is there, its ok, it will work fine.
    */
    private $editPermission = true;
    private function setEditPermissions($queryCheckForDelete=false){
        //For admin panel edit permissions
        //check before delete/edit/update query
        //need to check admin or webuser? if webuser then send true. else check permission

        global $functions;
        $editPer = $functions->pageEditPermission($queryCheckForDelete);
        //var_dump($editPer);
        if($editPer!=='' && $editPer!==true && $this->editPermission==true){
            echo $editPer;
            $this->editPermission = false;
            return false;
        }
        if($this->editPermission===false){
            return false;
        }
        return true;
    }

    public function setRow($query,$arr=null,$tryCatch=true){
        //Working For edit permissions
        //edit permission working in as functions
        //delete in edit not stop
        //Delete only stop from ajax request
            if ($this->setEditPermissions($query) == false) {
                $this->rowCount = 0;
                return false;
            };
        //Working For Admin edit permissions End

       try{
            if($arr==false){
                $tryCatch=false;
            }

            if($this->db->inTransaction()) {
                //if previous Transaction already start
               $tryCatch = false; // make it false to stop more transaction in this function
            }

            if($tryCatch)
                    $this->db->beginTransaction();

            $sth = $this->db->prepare($query); // use like,   WHERE name < ? AND colour = ?');
            $i=0;

            for($i=0;$i<sizeof($arr);$i++){
                $index=$i+1;
                $sth->bindParam($index, $arr[$i]);
            }
            $sth->execute();
            $this->rowCount=$sth->rowCount();

            $this->rowLastId=$this->db->lastInsertId();

            if($tryCatch)
                $this->db->commit();

           $this->error_submit(false);

           //return $sth->rowCount();
           return $this->rowLastId;

       }catch (PDOException $e)
        {
           // echo $query;
            if($tryCatch)
                $this->db->rollBack();
            $this->error_submit($e,$query);
            return $e;

        }

    }


// required query with ? mark in where clause value.. and array of value to set in where clause
    /* example
    $qry="SELECT * FROM staff where id=? AND name=?";
    $ary={$id,$name};
    getRow($qry,ary);

    if array is not set, and just full query is there, its ok, it will work fine.
    */

    public function getRow($query,$arr=null,$tryCatch=true, $array_with_key = false){
        try{
            if($arr==false){
                $tryCatch=false;
            }
            if($this->db->inTransaction()) {
                //if previous Transaction not start
                $tryCatch = false;
                    //global $_e;
                    //$_e = array();
            }

            if($tryCatch)
                $this->db->beginTransaction();

            if(stristr($query,' LIMIT ') == false){
                $query.=" LIMIT 1";
            }
            $sth = $this->db->prepare($query); //    WHERE name < ? AND colour = ?');
            $i=0;

            if($array_with_key)
                foreach($arr as $key=>$val){
                    $i++;
                    $sth->bindValue($i, $val, PDO::PARAM_STR);
                }
            else
                for ($i=0;$i<sizeof($arr);$i++) {
                    $index=$i+1;
                    $sth->bindValue($index, $arr[$i], PDO::PARAM_STR);
                }


            $sth->execute();
            $this->rowCount=$sth->rowCount();

            if($tryCatch)
                $this->db->commit();

            $this->error_submit(false);
            return $sth->fetch();
        }catch (PDOException $e) {
            if($tryCatch)
                $this->db->rollBack();
            $this->error_submit($e,$query);
        }
    }




    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param $query
     * @param null $arr
     * @param bool $tryCatch
     * @param bool $assoc
     * @return array
     *
     * required sql string
     * required query with ? mark in where clause value.. and array of value to set in wherelause
     * example
     * $qry="SELECT * FROM staff where id=? AND name=?";
     * $arry={$id,$name};
     * getRowBind($qry,arry);
     *
     * if arry is not set, and just full query is there, its ok, it will work fine.
     *
     */
    public function getRows($query,$arr=null,$tryCatch=true,$assoc  = true,$array_with_key = false){
        try{
            if($arr==false){
                $tryCatch=false;
            }

            if($this->db->inTransaction()) {
                //if previous Transaction already start
                $tryCatch = false; // make it false to stop more transaction in this function
            }

            if($tryCatch)
            $this->db->beginTransaction();

            $sth = $this->db->prepare($query); //    WHERE calories < ? AND colour = ?');
            $i=0;

            if($array_with_key)
                foreach($arr as $key=>$val){
                    $i++;
                    $sth->bindValue($i, $val, PDO::PARAM_STR);
                }
            else
                for ($i=0;$i<sizeof($arr);$i++) {
                    $index=$i+1;
                    $sth->bindValue($index, $arr[$i], PDO::PARAM_STR);
                }

            $sth->execute();
            $this->rowCount=$sth->rowCount();

            if($tryCatch)
                $this->db->commit();

            $this->error_submit(false);
            if($assoc == false){
                return $sth->fetchAll();
            }
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            if($tryCatch)
                $this->db->rollBack();
            $this->error_submit($e,$query);
            echo "<br>";
        }
    }

/*
 * MutiTable insert query ..
 * $sql="INSERT INTO table('name','val') VALUES (?,?)";
 * $arr=array($name,$val);
 *
 * $sql2="INSERT INTO child_table('table_ID','val','val2') VALUES";
 * $valFormat='?,?'; // table id not include,
 * $arr2=array(array('1','11'),array('2','22'));
 *
 * setRowMultiTable();
 *
 *
 *
 */
     public function setRowMultiTable($query,$arry,$query2=false,$valFormat,$arry2,$tryCatch=true){

        try{
            if($this->db->inTransaction()) {
                //if previous Transaction not start
                $tryCatch = false;
            }

            if($tryCatch)
                $this->db->beginTransaction();

            //First Parent table
            $this->setRow($query,$arry,false);
            $lastId=$this->rowLastId;

            //Child related table
            if($query2!=false)
            {
                for($i=0;$i<sizeof($arry2);$i++)
                {
                    $query2.="('$lastId',$valFormat),";
                }
                $query2= trim($query2,",");

                $sth=$this->db->prepare($query2);
                $sth->execute($arry2);
            }

            if($tryCatch)
                $this->db->commit();

            $this->error_submit(false);
            return $this->rowCount; // Check Parent row count result

        } catch (PDOException $e) {
            if($tryCatch)
                $this->db->rollBack();
            bs_alert::warning("Some required fields are empty!");
            $this->error_submit($e);
        }

     }


    // submit error to Imedia... PENDING
    private $errorNo = 1;
    public function error_submit($e,$query=''){
        $exec  =    false;
        if($e === false){
            $this->hasException = false;
            $this->rowException = "";
        }else{
            $this->hasException = true;
            $this->rowException = $e->errorInfo[2];

            if($_SERVER['HTTP_HOST']=='localhost' && $this->db->showErrorOnLocal){
                $exec = true;
            }else if($this->db->showErrorOnLive){
                $exec = true;
            }

            if($exec){
                if($this->errorNo<=4){
                    $errorCookie = "Exce_".$this->errorNo;
                    $errorDetailLink = WEB_URL."/error.php?errorId=$errorCookie";
                    echo $error="<pre>Asad Manual Execption From Function class. : ".$e->getMessage()
                        ."<br>For Detail :  <a href='$errorDetailLink' target='_blank'>$errorCookie</a></pre>";
                    $error .="<br>Query : $query <br>";

                    $error_detail = $e->getTrace(); //error throw from where?

                    $error = $error.print_r($error_detail,true);
                    $_SESSION['error'][$errorCookie]= $error;
                    //use of session becase,, cooking show error, or size limit
                    $this->errorNo++;

                }
            }

        }


    }







}

?>