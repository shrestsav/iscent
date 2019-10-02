<?php
ob_start();

global $dbF;
global $functions;
$functions->require_once_custom('setting.class.php');
$setting    =  new setting();
$setting->hardWordsEditSubmit();
$setting->hardWordsSubmit();

    if(isset($_GET['editId']) && $_GET['editId'] != ''){
        echo '<h4 class="sub_heading borderIfNotabs">'. _uc($_e['WebSite Special Words']) .'</h4>';
        echo '<a href="-setting?page=hardWords" class="btn btn-primary">'. _uc($_e['GO BACK']) .'</a><br><br>';
        $setting->hardWordsEdit();
    }else{ ?>

        <h4 class="sub_heading "><?php echo _uc($_e['WebSite Special Words']); ?></h4>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs tabs_arrow" role="tablist">
            <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['List']); ?></a></li>
            <li><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New']); ?></a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade in active container-fluid" id="home">
                <h2  class="tab_heading"><?php echo _uc($_e['List']); ?></h2>
                <?php $setting->hardWordsList();  ?>
            </div>

            <div class="tab-pane fade in container-fluid" id="newPage">
                <h2  class="tab_heading"><?php echo _uc($_e['Add New']); ?></h2>
                <?php $setting->hardWordNew();  ?>
            </div>
        </div>
  <?php  }

?>


    <script>
        $(document).ready(function(){
            tableHoverClasses();
            dateJqueryUi();
        });

        function deleteHardWords(ths){
            btn=$(ths);
            if(secure_delete()){
                btn.addClass('disabled');
                btn.children('.trash').hide();
                btn.children('.waiting').show();

                id=btn.attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: 'setting/setting_ajax.php?page=deleteHardWord',
                    data: { id:id }
                }).done(function(data)
                    {
                        ift =true;
                        if(data=='1'){
                            ift = false;
                            btn.closest('tr').hide(1000,function(){$(this).remove()});
                        }
                        else if(data=='0'){
                            jAlertifyAlert('<?php echo _js(_uc($_e['Delete Fail Please Try Again.'])); ?>');
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
    </script>


<?php return ob_get_clean(); ?>