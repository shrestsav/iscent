<?php
ob_start();

require_once("classes/support.class.php");

global $dbF;

$support  =   new support();

$support->newMessageSend();

?>



<h2 class="sub_heading"><?php //echo _uc($_e['Manage Files']); ?></h2>

    <!-- Nav tabs -->

    <ul class="nav nav-tabs tabs_arrow" role="tablist">

        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Messages']); ?></a></li>

        <li><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['New Message']); ?></a></li>

    </ul>

    <!-- Tab panes -->



    <div class="tab-content">

        <div class="tab-pane fade in active container-fluid" id="home">

            <h2  class="tab_heading"><?php echo _uc($_e['All Messages']); ?></h2>

            <?php //$support->allMessagesView();  ?>
            <?php $support->newMessage();  ?>

        </div>

        <div class="tab-pane fade in container-fluid" id="newPage">

            <h2  class="tab_heading"><?php echo _uc($_e['New Message']); ?></h2>

            <?php $support->newMessage();  ?>

        </div>

    </div>

<script>

    $(function(){

        tableHoverClasses();

        dateJqueryUi();

    });

    function deleteDaily(ths){

        btn=$(ths);

        if(secure_delete()){

            btn.addClass('disabled');

            btn.children('.trash').hide();

            btn.children('.waiting').show();

            id=btn.attr('data-id');

            $.ajax({

                type: 'POST',
                url: 'daily_book/dailyBook_ajax.php?page=deleteDaily',
                data: { id:id }

            }).done(function(data)

                {

                    ift =true;

                    if(data=='1'){

                        ift = false;

                        btn.closest('tr').hide(1000,function(){$(this).remove()});

                    }



                    else if(data=='0'){



                        jAlertifyAlert('<?php echo _js($_e['Delete Fail Please Try Again.']); ?>');



                    }



                    else{



                        btn.append(data);



                    }



                    if(ift){



                        btn.removeClass('disabled');



                        btn.children('.trash').show();



                        btn.children('.waiting').hide();



                    }







                });



        }



    }


    function getMessageUser(ths){
        usr_id = $(ths).data('id');
        $('#message_cUser').val(usr_id);
        $('#messageSendButton').removeAttr("disabled");  

        $.ajax({
            url: 'support/support_ajax.php?page=getUserMessages',
            type: 'post',
            data: {user_id : usr_id}
        }).done(function(res){
            // console.log(res);
            $('#message_div').html(res);
        });
    }

    $(document).ready(function(){
        $('#messageSendButton').on('click', function(){
            form = $('#message_form').serialize();

            $.ajax({
                url: 'support/support_ajax.php?page=sendUserMessage',
                type: 'post',
                data: form
            }).done(function(res){
                console.log(res);
                result = JSON.parse(res);
                if(result.ret == ''){
                    $('.error_msg').html('<span class="alert alert-danger">Something Went Wrong! Please Try Again.</span>');
                }else{
                    $('#message_div').append(result.ret);
                    $('.error_msg').html('<span class="alert alert-success">Message Sent Successfullly.</span>');
                }
            });
        });
    });











</script>

<style>
#status_infoModalLabel{
    text-align: center;
}

.highlighed:hover td {
    background-color: #7cbe35 !important;
}

#loader {
    position: fixed;
    top: 0;
    width: 100%;
    height: 100%;
    z-index: 999999999999999999999999999;
    background: url(images/loader.gif) center center no-repeat rgba(0,0,0,0.8);
    display: none;
}

#dropbox .progress {
    background-image: none;
}

.container {
    border: 2px solid #dedede;
    background-color: #f1f1f1;
    border-radius: 5px;
    padding: 10px;
    margin: 10px 0;
}

.darker {
    border-color: #ccc;
    background-color: #ddd;
    text-align: right;
}

.container::after {
    content: "";
    clear: both;
    display: table;
}

.container img {
    float: left;
    max-width: 60px;
    width: 100%;
    margin-right: 20px;
    border-radius: 50%;
}

.container img.right {
    float: right;
    margin-left: 20px;
    margin-right:0;
}

.time-right {
    float: right;
    color: #aaa;
}

.time-left {
    /*float: left;*/
    color: #999;
}
</style>



<?php return ob_get_clean(); ?>