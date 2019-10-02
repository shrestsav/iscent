<?php
ob_start();
require_once("classes/seo.class.php");
global $dbF;
$seo  =   new seo();
$seo->seoEditSubmit();
$seo->newSeoAdd();

?>
    <h2 class="sub_heading"><?php echo _uc($_e['Manage SEO']); ?></h2>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Active SEO']); ?></a></li>
        <li><a href="#draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Draft']); ?></a></li>
        <li><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New SEO']); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Active SEO']); ?></h2>
            <?php $seo->seoView();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="draft">
            <h2  class="tab_heading"><?php echo _uc($_e['Draft']); ?></h2>
            <?php $seo->seoDraft();  ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="newPage">
            <h2  class="tab_heading borderIfNotabs"><?php echo _uc($_e['Add New SEO']); ?></h2>
            <?php $seo->newSeo();  ?>
        </div>
    </div>

<?php

global $functions;
$functions->includeAdminFile("menu/classes/menu.class.php");
$menuC    =   new WebMenu();
$menuC->menuWidgetLinks();
?>

    <script>
        $(function(){
            tableHoverClasses();
            dateJqueryUi();
        });

        function deleteSeo(ths){
            btn=$(ths);
            if(secure_delete()){
                btn.addClass('disabled');
                btn.children('.trash').hide();
                btn.children('.waiting').show();

                id=btn.attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: 'seo/seo_ajax.php?page=deleteSeo',
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