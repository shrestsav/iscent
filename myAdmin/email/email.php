<?php
ob_start();

require_once("classes/email.class.php");
global $dbF;

$emailC     =   new email();
@$page      =   $_GET['page'];
if(isset($_GET['pageT']) && $_GET['pageT'] =='submit' && isset($_POST['emailImport'])){

}else{
    $_GET['pageT'] ='new';
}

ob_start();
 include_once('excel/excel.php');
$importEmail = ob_get_clean();

?>
<h2 class="sub_heading"><?php echo $_e['Manage Emails']; ?></h2>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo $_e['Verify Emails']; ?></a></li>
        <li class=""><a href="#notVerify" role="tab" data-toggle="tab"><?php echo $_e['UnVerify Emails']; ?></a></li>
        <li class=""><a href="#importEmail" role="tab" data-toggle="tab"><?php echo $_e['Import Emails']; ?></a></li>
        <li class=""><a href="#deleteByGroup" role="tab" data-toggle="tab"><?php echo $_e['Delete Group']; ?></a></li>
    </ul>


    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo $_e['Verify Emails']; ?></h2>
            <?php $emailC->emailView();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="notVerify">
            <h2  class="tab_heading"><?php echo $_e['UnVerify Emails']; ?></h2>
            <?php $emailC->emailPending();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="deleteByGroup">
            <h2  class="tab_heading"><?php echo $_e['Delete Group']; ?></h2>
            <?php $emailC->emailGroup();  ?>
        </div>


        <div class="tab-pane fade in container-fluid" id="importEmail">
            <h2  class="tab_heading"><?php echo $_e['Import Emails']; ?></h2>
            <?php
            echo $importEmail;
            ?>
        </div>

    </div>

<script>
      $(function(){
        tableHoverClasses();
        dateJqueryUi();
    });

    function deleteEmail(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'email/email_ajax.php?page=deleteEmail',
                data: { id:id }
            }).done(function(data)
                {
                    ift =true;
                    if(data=='1'){
                        ift = false;
                        btn.closest('tr').hide(1000,function(){$(this).remove()});
                    }
                    else if(data=='0'){
                        jAlertifyAlert('<?php echo _uc($_e['Delete Fail Please Try Again.']); ?>');
                    }

                    if(ift){
                        btn.removeClass('disabled');
                        btn.children('.trash').show();
                        btn.children('.waiting').hide();
                    }

                });
        }
    }

      function deleteGroup(ths){
          btn=$(ths);
          if(secure_delete()){
              btn.addClass('disabled');
              btn.children('.trash').hide();
              btn.children('.waiting').show();

              id=btn.attr('data-id');
              $.ajax({
                  type: 'POST',
                  url: 'email/email_ajax.php?page=deleteGroup',
                  data: { id:id }
              }).done(function(data)
              {
                  ift =true;
                  if(data=='1'){
                      ift = false;
                      btn.closest('tr').hide(1000,function(){$(this).remove()});
                  }
                  else if(data=='0'){
                      jAlertifyAlert('<?php echo _uc($_e['Delete Fail Please Try Again.']); ?>');
                  }

                  if(ift){
                      btn.removeClass('disabled');
                      btn.children('.trash').show();
                      btn.children('.waiting').hide();
                  }

              });
          }
      }


      function activeEmail(ths){
          btn=$(ths);
          if(secure_delete('<?php echo _uc($_e['Are You Sure You Want To Update?']); ?>')){
              btn.addClass('disabled');
              btn.children('.trash').hide();
              btn.children('.waiting').show();

              id=btn.attr('data-id');
              val =btn.attr('data-val');
              $.ajax({
                  type: 'POST',
                  url: 'email/email_ajax.php?page=activeEmail',
                  data: { id:id,val:val }
              }).done(function(data)
                  {
                      ift =true;
                      if(data=='1'){
                          ift = false;
                          btn.closest('tr').hide(1000,function(){$(this).remove()});
                      }
                      else if(data=='0'){
                          jAlertifyAlert('<?php echo _uc($_e['Update Fail Please Try Again.']); ?>');
                      }
                      if(ift){
                          btn.removeClass('disabled');
                          btn.children('.trash').show();
                          btn.children('.waiting').hide();
                      }
                  });
          }
      }


    function emailGroup(ths) {
            val = $(ths).val();
            if (val == 'other') {
                $(ths).parent('div').find('.emailOtherGrp').show(500);
            }else {
                $(ths).parent('div').find('.emailOtherGrp').hide(500);
                emailGrp(ths, val);
            }
    }


      function emailOtherGroup(ths) {
            val  =   $(ths).closest('.grpDiv').find('.emailOtherInput').val();
            emailGrp(ths,val);
      }



    function emailGrp(ths,option){

        if(secure_delete('<?php echo _uc($_e['Are You Sure You Want To Update?']); ?>')){
            btn=    $(ths).closest('.grpDiv');

            $(ths).closest('.grpDiv').find('.emailOtherGrp').hide(500);
            btn.find('.waiting').show();

            id  =   btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'email/email_ajax.php?page=emailGrp',
                data: { id:id,val:option }
            }).done(function(data)
                {
                    if(data=='1'){

                    }
                    else if(data=='0'){
                        jAlertifyAlert('<?php echo _uc($_e['Update Fail Please Try Again.']); ?>');
                    }
                        btn.find('.waiting').hide();
                });
        }
    }


</script>

<?php return ob_get_clean(); ?>