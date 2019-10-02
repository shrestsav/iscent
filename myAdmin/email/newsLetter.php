<?php
ob_start();

require_once("classes/email.class.php");
global $dbF;

$email  =   new email();

$bounceEmail = '';
if(isset($_GET['deleteBounce'])){
    $bounceEmail = $email->emailBounceDelete();
}

$email->newLetterAdd();
$email->letterEditSubmit();

$email->letterSend();
$email->letterComplete();
if(isset($_GET['runningJob'])){
    $email->cronJobRunning();
}
$pageLink = WEB_ADMIN_URL."/-".$functions->getLinkFolder(false)."&runningJob";
$runningLink =  "<a href='$pageLink' class='btn btn-danger btn-xs' title='Specialy For Developer'>Running cron Job</a>";
if(isset($_GET['editId']) && $_GET['editId'] != ''){
    echo '<h4 class="sub_heading borderIfNotabs">'. _uc($_e['News Letter']) .'</h4>';
    echo '<a href="-'.$functions->getLinkFolder().'?page=newsLetter" class="btn btn-primary">'. _u($_e['GO BACK']) .'</a><br><br>';
    $email->letterNew();
}else{ ?>
    <h2 class="sub_heading"><?php echo _uc($_e['News Letters']); ?></h2>


    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['News Letters']); ?></a></li>
        <li class=""><a href="#email" role="tab" data-toggle="tab"><?php echo _uc($_e['Email Stats']); ?></a></li>
        <?php $hasBounce = $functions->developer_setting('bounceEmail');
        if($hasBounce == '1'){ ?>
        <li class=""><a href="#bounce" role="tab" data-toggle="tab"><?php echo _uc($_e['Bounce Email']); ?></a></li>
        <?php } ?>
        <li class=""><a href="#letter" role="tab" data-toggle="tab"><?php echo _uc($_e['New News Letter']); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">


        <div class="tab-pane fade in active  container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['News Letters']); ?></h2>
            <?php $email->letterView();  ?>
        </div>

        <div class="tab-pane fade  container-fluid" id="email">
            <h2  class="tab_heading"><?php echo _uc($_e['Email Stats']); ?></h2>

            <?php
            echo $runningLink;
            $email->emailStats();  ?>
        </div>
        <?php if($hasBounce == '1'){ ?>
            <div class="tab-pane fade  container-fluid" id="bounce">
                <h2  class="tab_heading"><?php echo _uc($_e['Bounce Email']); ?></h2>
                <a href="-email?page=newsLetter&deleteBounce" class="btn btn-danger btn-xs"><?php echo _uc($_e['Delete Bounce Emails']); ?></a>
                <?php
                echo $bounceEmail;
                $email->emailBounce();  ?>
            </div>
        <?php } ?>
        <div class="tab-pane fade in container-fluid" id="letter">
            <h2  class="tab_heading"><?php echo _uc($_e['New News Letter']); ?></h2>
            <?php $email->letterNew();  ?>
        </div>
    </div>

<?php } ?>

    <script>
        $(function(){
            dateJqueryUi();
            tableHoverClasses();
        });

        function sendLetter(ths,id){
            btn=$(ths);
            grp =   btn.closest('tr').find('.emailGrp').val();

            if(grp=='' || grp===undefined){
                jAlertifyAlert("<?php echo _uc($_e['Please select group before send email letter']); ?>");
                return false;
            }

            if(secure_delete("<?php echo _replace("{{grp}}",'"+grp+"',$_e['Are you sure you want to send email to {{grp}} Group?']); ?>")){
                location.replace('-email?page=newsLetter&sendletter='+id+'&grp='+grp+"#email");
                return true;
            }

            return false;
        }

        function deleteLetter(ths){
            btn=$(ths);
            if(secure_delete()){
                btn.addClass('disabled');
                btn.children('.trash').hide();
                btn.children('.waiting').show();

                id=btn.attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: 'email/email_ajax.php?page=deleteLetter',
                    data: { id:id }
                }).done(function(data)
                    {
                        ift =true;
                        if(data=='1'){
                            ift = false;
                            btn.closest('tr').hide(1000,function(){$(this).remove()});
                        }
                        else if(data=='0'){
                            jAlertifyAlert('<?php echo $_e['Delete Fail Please Try Again.']; ?>');
                        }

                        if(ift){
                            btn.removeClass('disabled');
                            btn.children('.trash').show();
                            btn.children('.waiting').hide();
                        }

                    });
            }

        }

            function deleteQueue(ths){
                btn=$(ths);
                if(secure_delete()){
                    btn.addClass('disabled');
                    btn.children('.trash').hide();
                    btn.children('.waiting').show();

                    id=btn.attr('data-id');
                    $.ajax({
                        type: 'POST',
                        url: 'email/email_ajax.php?page=deleteQueue',
                        data: { id:id }
                    }).done(function(data)
                        {
                            ift =true;
                            if(data=='1'){
                                ift = false;
                                btn.closest('tr').hide(1000,function(){$(this).remove()});
                            }
                            else if(data=='0'){
                                jAlertifyAlert('<?php echo $_e['Delete Fail Please Try Again.']; ?>');
                            }

                            if(ift){
                                btn.removeClass('disabled');
                                btn.children('.trash').show();
                                btn.children('.waiting').hide();
                            }

                        });
                }
        }

        function startQueue(ths){
            btn=$(ths);
            val=btn.attr('data-val');
            if(val=='1'){
                start = 'start';
            }else{
                start = 'pause';
            }
            if(secure_delete("<?php echo _replace("{{state}}",'"+start+"',$_e["Are you sure you want to {{state}} email queue?"]); ?>")){
                btn.addClass('disabled');
                btn.children('.trash').hide();
                btn.children('.waiting').show();

                id=btn.attr('data-id');

                $.ajax({
                    type: 'POST',
                    url: 'email/email_ajax.php?page=startQueue',
                    data: { id:id,val:val }
                }).done(function(data)
                    {
                        ift =true;
                        if(data=='1'){
                            if(val=='0'){
                                //location.replace('-email?page=newsLetter');
                                btn.attr('data-val','1');
                                btn.children('.trash').removeClass('glyphicon-pause');
                                btn.children('.trash').addClass('glyphicon-play glyphicon-start');
                            }else{
                                btn.attr('data-val','0');
                                btn.children('.trash').addClass('glyphicon-pause');
                                btn.children('.trash').removeClass('glyphicon-start');
                            }

                        }
                        else if(data=='0'){
                            jAlertifyAlert('<?php echo $_e['Update Fail Please Try Again.']; ?>');
                        }

                            btn.removeClass('disabled');
                            btn.children('.trash').show();
                            btn.children('.waiting').hide();

                    });
            }
        }
    </script>
<?php return ob_get_clean(); ?>