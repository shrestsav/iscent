<?php
ob_start();

require_once("classes/menu.class.php");
global $dbF;

$menuC  =   new WebMenu();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$menuC->footerMenuEditSubmit();
$menuC->footerNewMenuAdd();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage WebSite Footer Menu']); ?></h2>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist" id="MenuTab">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Footer Menu']); ?></a></li>
        <li id="NewMenu"><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New Footer Menu']); ?></a></li>
    </ul>


    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Footer Menu']); ?></h2>
            <?php $menuC->footerMenuView();  ?>
        </div>

        <div class="tab-pane fade container-fluid" id="newPage">
            <h2  class="tab_heading"><?php echo _uc($_e['Add New Footer Menu']); ?></h2>
            <?php $menuC->footerMenuNew();  ?>
        </div>
    </div>

<?php $menuC->menuWidgetLinks(); ?>
<script>
    $(function(){
        tableHoverClasses();
        dateJqueryUi();
    });

    function addNewMenu(id){
        $('#MenuTab>li').removeClass('active');
        $('#MenuTab #NewMenu').addClass('active');

        $('#home').removeClass('in active');
        $('#newPage').addClass('in active');

        $('.underMenu').val(id).change();
    }

    function deleteFooterMenu(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'menu/menu_ajax.php?page=deleteFooterMenu',
                data: { id:id }
            }).done(function(data)
                {
                    ift =true;
                    if(data=='1' || data=='11' || data=='111'){
                        ift = false;
                        btn.closest('.relative').hide(1000,function(){$(this).remove()});
                    }
                    else if(data=='0'){
                        jAlertifyAlert('<?php echo ($_e['Delete Fail Please Try Again.']); ?>');
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

    $(document).ready(function() {

        $( ".accordion,.accordion2" ).sortable({
            handle: '.menuSortDiv',
            containment: "parent",
            update : function () {
                serial = $(this).sortable('serialize');
                $.ajax({
                    url: 'menu/menu_ajax.php?page=footerMenuSort',
                    type: "post",
                    data: serial,
                    error: function(){
                        jAlertifyAlert("<?php echo ($_e['There is an error, Please Refresh Page and Try Again']); ?>");
                    }
                });
            }
        });
    });


</script>
<?php return ob_get_clean(); ?>