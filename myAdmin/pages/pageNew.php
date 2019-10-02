<?php
ob_start();

require_once("classes/pages.class.php");
global $dbF;

$pages  =   new pages();

//$dbF->prnt($_POST);
//$dbF->prnt($_FILES);
//exit;
$pages->PageEditSubmit();
$pages->newPageAdd();
?>
<h2 class="sub_heading"><?php echo _uc($_e['Manage Pages']); ?></h2>


<?php $pages->pageNew();  ?>

<script>
    $(function(){
        tableHoverClasses();
    });

    function deletePage(ths){
        btn=$(ths);
        if(secure_delete()){
            btn.addClass('disabled');
            btn.children('.trash').hide();
            btn.children('.waiting').show();

            id=btn.attr('data-id');
            $.ajax({
                type: 'POST',
                url: 'pages/page_ajax.php?page=deletePage',
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


</script>
<?php return ob_get_clean(); ?>