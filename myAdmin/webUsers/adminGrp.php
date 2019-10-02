<?php
ob_start();

require_once("classes/webUsers.class.php");
global $dbF;

$menuClass  =   new menu();
$MenuVisible = $menuClass->autoVisibleMenuArray();
//$menuLink   =   $menuClass->AutoVisibleMenuLink;
//var_dump($MenuVisible);

$webUsers  =   new webUsers();
$webUsers->adminGrpSubmit();
$webUsers->adminGrpEditSubmit();

//var_dump($_SESSION);
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Admin Groups']); ?></h2>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Groups']); ?></a></li>
        <li class=""><a href="#new" role="tab" data-toggle="tab"><?php echo _uc($_e['New Group']); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Groups']); ?></h2>
            <?php $webUsers->adminUsersGrpView(); ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="new">
            <h2  class="tab_heading"><?php echo _uc($_e['New Group']); ?></h2>
            <?php $webUsers->newAdminGrp();  ?>
        </div>
    </div>


<script src="webUsers/js/user.js"></script>

<script>
      $(function(){
        tableHoverClasses();
        dateJqueryUi();
      });

    function deleteAdminGrp(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'webUsers/webUsers_ajax.php?page=deleteAdminGrp',
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


      function activeAdminUser(ths){
          btn=$(ths);
          if(secure_delete('<?php echo _uc($_e['Are You Sure You Want TO Update?']); ?>')){
              btn.addClass('disabled');
              btn.children('.trash').hide();
              btn.children('.waiting').show();

              id=btn.attr('data-id');
              val =btn.attr('data-val');
              $.ajax({
                  type: 'POST',
                  url: 'webUsers/webUsers_ajax.php?page=activeAdminUser',
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


</script>
<?php return ob_get_clean(); ?>