<?php
ob_start();

require_once("classes/category.class.php");
global $dbF;

$menuC  =   new WebMenu();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$menuC->menuEditSubmit();
$menuC->newMenuAdd();
?>
<h2 class="sub_heading <?php if(!isset($_GET['type'])){ echo "borderIfNotabs"; }?> "><?php echo _uc($_e['Manage Product Categories']); ?></h2>


<?php

if(!isset($_GET['type'])){
    echo "<br>";
    $menuC->menuTypes();
}else {

?>


    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs_arrow" role="tablist" id="MenuTab">
        <li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc($_e['Categories']); ?></a></li>
        <li id="NewMenu"><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc($_e['Add New Category']); ?></a></li>
    </ul>


    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active container-fluid" id="home">
            <h2  class="tab_heading"><?php echo _uc($_e['Categories']); ?></h2>
            <?php
                if(isset($_GET['type'])){
                    $menuC->menuView($_GET['type']);
                }else {
                    $menuC->menuTypes();
                }
            ?>
        </div>

        <div class="tab-pane fade in container-fluid" id="newPage">
            <h2  class="tab_heading"><?php echo _uc($_e['Add New Category']); ?></h2>
            <?php $menuC->menuNew();  ?>
        </div>
    </div>




<?php $menuC->menuWidgetLinks(); ?>

<?php } ?>

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

    function deleteMenu(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'categories/category_ajax.php?page=deleteMenu',
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

        $( ".accordion,.accordion2,.accordion3" ).sortable({
            handle: '.menuSortDiv',
            containment: "parent",
            update : function () {
                serial = $(this).sortable('serialize');
                $.ajax({
                    url: 'categories/category_ajax.php?page=menuSort',
                    type: "post",
                    data: serial,
                    error: function(){
                        jAlertifyAlert("<?php echo ($_e['There is an error, Please Refresh Page and Try Again']); ?>");
                    }
                });
            }
        });

        $(".menuType").change(function(){
            menuTypes();
        });
        menuTypes();

    });

    function menuTypes(){
        icon = $(".menuType option:selected").attr("data-icon");
        if(icon=='1'){
            $(".icon-div").show(500);
        }else{
            $(".icon-div").hide(500);
        }
        console.log(icon)
    }

</script>
<?php return ob_get_clean(); ?>