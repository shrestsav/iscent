<?php

require_once (__DIR__."/../../global.php"); //connection setting db

class support extends object_class{

public $productF;

public $imageName;

public $script_js;

public function __construct(){

parent::__construct('3');



# saving script

$this->script_js = array();





/**

* MultiLanguage keys Use where echo;

* define this class words and where this class will call

* and define words of file where this class will called

**/

global $_e;

global $adminPanelLanguage;

$_w=array();//homePage.php



//homePageEdit.php

$_w['SAVE'] = '' ;

$_w['Close'] = '' ;

$_w['Delete Fail Please Try Again.'] = '' ;

$_w['Daily Book Entry'] = '' ;

$_w['Added'] = '' ;

$_w['ACTION'] = '' ;

$_w['PAYMENT'] = '' ;



$_w['Date'] = '' ;

$_w['Select Agent'] = '' ;

$_w['Title'] = '' ;

$_w['Payment'] = '' ;

$_w['Add New'] = '' ;

$_w['View Entries'] = '' ;

$_w['Daily Book'] = '' ;

$_w['File Save Successfully'] = '' ;

$_w['Daily Book Entry'] = '' ;

$_w['Daily Book Entry Save Successfully'] = '' ;

$_w['Daily Book Entry Save Failed,Please Enter Correct Values, And unique slug'] = '' ;

$_w['SNO'] = '' ;

$_w['DATE'] = '' ;

$_w['TITLE'] = '' ;

$_w['Support'] = '' ;

$_w['Messages'] = '' ;

$_w['New Message'] = '' ;

$_w['All Messages'] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;

$_w[''] = '' ;



$_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin File Management');



}





public function allMessagesView($userFor=false){



$sql  = "SELECT * FROM `user_messages` ORDER BY `date` DESC ";

$data =  $this->dbF->getRows($sql);

$this->printViewTable($data);

}



private function printViewTable($data){

global $_e;

echo '<div class="table-responsive">

<table class="table table-hover dTable1 tableIBMS">

<thead>

<th>'. _u($_e['SNO']) .'</th>

<th>'. _u($_e['DATE']) .'</th>

<th>'. _u($_e['TITLE']) .'</th>

<th>'. _u($_e['AGENT']) .'</th>

<th>'. _u($_e['PAYMENT']) .'</th>

<th>'. _u($_e['ACTION']) .'</th>

</thead>

<tfoot>

            <tr>

                <th colspan="4" style="text-align:right">Total:</th>

                <th></th>

            </tr>

        </tfoot>

<tbody>';

$i = 0;

$defaultLang = $this->functions->AdminDefaultLanguage();

foreach($data as $val){

$i++;

$id = $val['id'];

$date = $val['date'];

$title = $val['title'];

$agent = $val['agent'];

$payment = $val['payment'];



echo "<tr>

<td>$i</td>

<td>$date</td>

<td>$title</td>

<td>$agent</td>

<td>$payment</td>

<td>

    <div class='btn-group btn-group-sm'>

        <a data-id='$id' onclick='deleteDaily(this);' class='btn'>

            <i class='glyphicon glyphicon-trash trash'></i>

            <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>

        </a>

    </div>

</td>



</tr>";

}

echo '</tbody>

</table>

</div> <!-- .table-responsive End -->';



?>

<script>

	$(document).ready(function() {

    $('.dTable1').DataTable( {

        "footerCallback": function ( row, data, start, end, display ) {

            var api = this.api(), data;

 

            // Remove the formatting to get integer data for summation

            var intVal = function ( i ) {

                return typeof i === 'string' ?

                    i.replace(/[\$,]/g, '')*1 :

                    typeof i === 'number' ?

                        i : 0;

            };

 

            // Total over all pages

            total = api

                .column( 4 )

                .data()

                .reduce( function (a, b) {

                    return intVal(a) + intVal(b);

                }, 0 );

 

            // Total over this page

            pageTotal = api

                .column( 4, { page: 'current'} )

                .data()

                .reduce( function (a, b) {

                    return intVal(a) + intVal(b);

                }, 0 );

 

            // Update footer

            $( api.column( 4 ).footer() ).html(

                'PKR '+pageTotal +' ( PKR '+ total +' total)'

            );

        }

    } );

} );

</script>

<?php

}





public function newMessageSend(){

global $_e;

if(isset($_POST['agent']) && isset($_POST['submit'])){

if(!$this->functions->getFormToken('newMessage')){return false;}



// echo "<pre>"; print_r($_POST); echo "</pre>";

// exit;

$date       = empty($_POST['date'])        ? ""    : $_POST['date'];

$agent    	= empty($_POST['agent']) 	   ? ""    : $_POST['agent'];

$title     	= empty($_POST['title'])  	   ? ""    : $_POST['title'];

$payment    = empty($_POST['payment'])     ? "0"   : $_POST['payment'];



$agent_name = $this->getAgentName($agent);



try{

$this->db->beginTransaction();



$sql  =  "INSERT INTO `daily_book`(`date`,`agent`,`title`,`payment`) VALUES (?,?,?,?)";



$array   = array($date,$agent,$title,$payment);



$this->dbF->setRow($sql,$array,false);

$lastId  =   $this->dbF->rowLastId;





$this->db->commit();

if($this->dbF->rowCount>0){

$this->functions->notificationError(_js(_uc($_e['Daily Book'])),_js(_uc($_e['Daily Book Entry Save Successfully'])),'btn-success');

$this->functions->setlog(_js(_uc($_e['Daily Book'])),_js(_uc($_e['Daily Book Entry'])),$lastId,'PKR'.$payment.' Payment Made For '.$title.' To agent: '.$agent_name);

}else{

$this->functions->notificationError(_js(_uc($_e['Daily Book'])),_js(_uc($_e['Daily Book Entry Save Failed,Please Enter Correct Values, And unique slug'])),'btn-danger');

}

}catch (Exception $e){

$this->db->rollBack();

$this->dbF->error_submit($e);

$this->functions->notificationError(_js(_uc($_e['Daily Book'])),_js(_uc($_e['Daily Book Entry Save Failed,Please Enter Correct Values, And unique slug'])),'btn-danger');

}

} //If end

}



public function newMessage(){

$this->newMessageEdit(true);

return '';

}



public function newMessageEdit($new = false){

global $_e;



$sql1="SELECT `acc_name`,`acc_id` FROM `accounts` WHERE `acc_role` = 7";

$data1 = $this->dbF->getRows($sql1);


$sql_user = "SELECT DISTINCT(`user_id`) FROM `user_messages`";
$res_users = $this->dbF->getRows($sql_user);


$token       = $this->functions->setFormToken('newMessage',false);

//No need to remove any thing,, go in developer setting table and set 0

echo $token.

'<!-- Tab panes -->'; 
$dummy_img = WEB_URL.'/webImages/dummy.png';

echo '<div class="people-list" id="people-list" style="display: table;">
          <ul class="list" style="display: table-cell; vertical-align: top; padding-right: 10px; width: 350px; list-style: none;">';

          foreach ($res_users as $key => $value) {
                $user_id = $value['user_id'];
                $sql_name = "SELECT `acc_name` FROM `accounts_user` WHERE `acc_id` = ?";
                $res_name = $this->dbF->getRow($sql_name, array($user_id));

                $user_name = $res_name['acc_name'];

                echo '<li class="clearfix" data-id="'.$user_id.'" onclick="getMessageUser(this)" style="margin-bottom: 10px;">
                        <img src="'.$dummy_img.'" class="avatar" class="avatar" alt="avatar" style="width:40px; display: inline-block; vertical-align: middle;"/>
                        <div class="about" style="display:inline-block; vertical-align: middle; margin-left: 10px;">
                            <div class="name">'.$user_name.'</div>
                        </div>
                    </li>';
            }

        echo '</ul>
    ';

    echo '<div class="container clearfix" style="display: table-cell; vertical-align: top;">
            <div id="message_div" style="height:80px;"></div>

            <form action="" method="post" class="form-horizontal" id="message_form" >
                <input type="hidden" name="message_cUser" id="message_cUser">

                <div class="form-group">
                    <div class="container-fluid" style="position: relative;">

                        <div class="error_msg" style="margin-top: 40px; margin-left: auto; margin-right: auto; display:block; text-align: center; "></div>

                       <textarea class="form-control" name="message_text" id="message_text" placeholder="Message Text"></textarea>

                        <br>

                        <input type="button" class="form-control btn btn-primary" id="messageSendButton" name="messageSendButton" value="SUBMIT" disabled/>

                    </div>
                </div>

            </form>
            
          </div></div>';

?>
   <!-- end container -->

<?php



} //function end



public function getAgentName($id){

	$sql = "SELECT `acc_name` FROM `accounts` WHERE `acc_id` = ?";

	$result = $this->dbF->getRow($sql,array($id),false);

	return $result['acc_name'];

}



}

?>