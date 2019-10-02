<?php
ob_start();

require_once("classes/email.class.php");
global $dbF;

$email  =   new email();
$email->letterEditSubmit();

$email->letterSend();
$email->letterComplete();
if(isset($_GET['runningJob'])){
    $email->cronJobRunning();
}


echo '<h4 class="sub_heading borderIfNotabs">'. $_e['Email Content'] .'</h4>';

if(isset($_GET['editId']) && $_GET['editId'] != ''){
    echo '<a href="-email?page=emailContent" class="btn btn-primary">'. _u($_e['GO BACK']) .'</a><br><br>';
    $email->letterNew();
}else{ ?>

<?php $email->emailContentView();  ?>

<?php } ?>

    <script>
        $(function(){
            dateJqueryUi();
            tableHoverClasses();
        });
    </script>
<?php return ob_get_clean(); ?>