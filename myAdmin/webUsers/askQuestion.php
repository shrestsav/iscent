<?php
ob_start();

require_once("classes/reviews.class.php");
global $dbF,$_e;

$reviews  =   new reviews();

?>
<h2 class="sub_heading <?php if(isset($_GET['edit'])){ echo "borderIfNotabs"; } ?> "><?php echo $_e['Manage Questions'];?></h2>

<?php
$reviews->reviewEditSubmit();
if(isset($_GET['edit'])){
    $reviews->reviewEdit();
}else{
    ?>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo $_e['Verify Questions'];?></a></li>
        <li class=""><a href="#notVerify" role="tab" data-toggle="tab"><?php echo $_e['Not Verify'];?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo $_e['Verify Questions'];?></h2>
            <?php $reviews->webQuestionsView(); ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="notVerify">
            <h2  class="tab_heading"><?php echo $_e['UnVerify Questions'];?></h2>
            <?php $reviews->webQuestionsPending();  ?>
        </div>
    </div>

<?php } ?>


<script>
      $(function(){
        tableHoverClasses();
        dateJqueryUi();
      });

    function deleteReview(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'webUsers/reviews_ajax.php?page=deleteReview',
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


      function activeReview(ths){
          btn=$(ths);
          if(secure_delete('<?php echo $_e['Are You Sure You Want TO Update?']; ?>')){
              btn.addClass('disabled');
              btn.children('.trash').hide();
              btn.children('.waiting').show();

              id=btn.attr('data-id');
              val =btn.attr('data-val');
              $.ajax({
                  type: 'POST',
                  url: 'webUsers/reviews_ajax.php?page=activeReview',
                  data: { id:id,val:val }
              }).done(function(data)
                  {
                      ift =true;
                      if(data=='1'){
                          ift = false;
                          btn.closest('tr').hide(1000,function(){$(this).remove()});
                      }
                      else if(data=='0'){
                          jAlertifyAlert('<?php echo $_e['Update Fail Please Try Again.']; ?>');
                      }
                      if(ift){
                          btn.removeClass('disabled');
                          btn.children('.trash').show();
                          btn.children('.waiting').hide();
                      }

                  });
          }
      }


</script>
<?php return ob_get_clean(); ?>