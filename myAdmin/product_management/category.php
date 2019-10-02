<?php
require_once(__DIR__. '/category/class.db.php');
require_once(__DIR__. '/category/class.tree.php');
if(isset($_GET['operation'])) {

    //For Multi Language
    /**
     * MultiLanguage keys Use where echo;
     * define this class words and where this class will call
     * and define words of file where this class will called
     **/
    global $_e;
    global $adminPanelLanguage;
    $_w=array();

    //FOr Mult Language End, Continue in loop

    $fs = new tree(db::get('mysqli://'.DB_USER.'@'.DB_HOST.'/'.DB_NAME.''),array('structure_table' => 'tree_struct', 'data_table' => 'tree_data', 'data' => array('nm')));
    try {
        $rslt = null;
        switch($_GET['operation']) {
            case 'get_node':
                $node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
                $temp = $fs->get_children($node);
                $rslt = array();

                //For Multi Language , create loop for create $_w array
                foreach($temp as $v) {
                    $_w[$v['nm']]= '';
                }
                $_e    =   $dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Product Category');
                //For Multi Language , get array in $_e;

                foreach($temp as $v) {
                    $temp = $_e[$v['nm']];
                    $rslt[] = array('id' => $v['id'], 'text' => $temp, 'children' => ($v['rgt'] - $v['lft'] > 1));
                }
                break;
            case "get_content":
                $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : 0;
                $node = explode(':', $node);
                if(count($node) > 1) {
                    $rslt = array('content' => 'Multiple selected');
                }
                else {
                    $temp = $fs->get_node((int)$node[0], array('with_path' => true));
                    $rslt = array('content' => 'Selected: /' . implode('/',array_map(function ($v) { return $v['nm']; }, $temp['path'])). '/'.$temp['nm']);
                }
                break;
            case 'create_node':
                $node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
                $temp = $fs->mk($node, isset($_GET['position']) ? (int)$_GET['position'] : 0, array('nm' => isset($_GET['text']) ? $_GET['text'] : 'New node'));
                $rslt = array('id' => $temp);
                break;
            case 'rename_node':
                $node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
                $rslt = $fs->rn($node, array('nm' => isset($_GET['text']) ? $_GET['text'] : 'Renamed node'));
                break;
            case 'delete_node':
                $node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
                $rslt = $fs->rm($node);
                break;
            case 'move_node':
                $node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
                $parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? (int)$_GET['parent'] : 0;
                $rslt = $fs->mv($node, $parn, isset($_GET['position']) ? (int)$_GET['position'] : 0);
                break;
            case 'copy_node':
                $node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
                $parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? (int)$_GET['parent'] : 0;
                $rslt = $fs->cp($node, $parn, isset($_GET['position']) ? (int)$_GET['position'] : 0);
                break;
            default:
                throw new Exception('Unsupported operation: ' . $_GET['operation']);
                break;
        }
        header('Content-Type: application/json; charset=utf8');
        echo json_encode($rslt);
    }
    catch (Exception $e) {
        header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
        header('Status:  500 Server Error');
        echo $e->getMessage();
    }
    die();
}

ob_start();

    /**
     * MultiLanguage keys Use where echo;
     * define this class words and where this class will call
     * and define words of file where this class will called
     **/
    global $_e;
    global $adminPanelLanguage;
    $_w=array();
    $_w['Manage Category'] = '' ;
    $_w['Please Enter English Words And then Translate From Translator, Special Character directly not Support'] = '' ;
    $_e    =   $dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Product Category');
?>

    <h4 class="sub_heading"><?php echo _uc($_e['Manage Category']); ?>
        <br>
        <small><?php echo _uc($_e['Please Enter English Words And then Translate From Translator, Special Character directly not Support']); ?></small>
    </h4>
<div class="borderIfNotabs"></div>

<?php
?>

    <br/>
    <br/>


    <style>
        a ins.jstree-icon {
            display: none;
        }

        .treeLiBtns {
            display: none;
            margin-left: 10px;
        }

    </style>


    <script type="text/javascript">

        $(function () {



          $("#tree").jstree({
                'core' : {
                    'data' : {
                        'url' : '?operation=get_node',
                        'data' : function (node) {
                            return { 'id' : node.id };
                        }
                    },
                    'check_callback' : true,
                    'themes' : {
                        'responsive' : false
                    }
                },
                "plugins": [ "contextmenu","wholerow" ,"dnd","unique" ]
            })
            .on('delete_node.jstree', function (e, data) {
                $.get('?operation=delete_node', { 'id' : data.node.id })
                    .fail(function () {
                        data.instance.refresh();
                    });
            })
            .on('create_node.jstree', function (e, data) {
                $.get('?operation=create_node', { 'id' : data.node.parent, 'position' : data.position, 'text' : data.node.text })
                    .done(function (d) {
                        data.instance.set_id(data.node, d.id);
                    })
                    .fail(function () {
                        data.instance.refresh();
                    });
            })
            .on('rename_node.jstree', function (e, data) {
                $.get('?operation=rename_node', { 'id' : data.node.id, 'text' : data.text })
                    .fail(function () {
                        data.instance.refresh();
                    });
            })
            .on('move_node.jstree', function (e, data) {
                $.get('?operation=move_node', { 'id' : data.node.id, 'parent' : data.parent, 'position' : data.position })
                    .fail(function () {
                        data.instance.refresh();
                    });
            })
            .on('copy_node.jstree', function (e, data) {
                $.get('?operation=copy_node', { 'id' : data.original.id, 'parent' : data.parent, 'position' : data.position })
                    .always(function () {
                        data.instance.refresh();
                    });
            })
            .on('changed.jstree', function (e, data) {
                if(data && data.selected && data.selected.length) {
                    $('.category_make_root').attr('data-id',data.selected);
                    $.get('?operation=get_content&id=' + data.selected.join(':'), function (d) {
                });
                }else{
                    $('.category_make_root').attr('data-id','0');
                }
            }).on('loaded.jstree', function() {
                  $("#tree").jstree('open_all');
              });


        });
    </script>


        <div id="tree"></div>


<?php return ob_get_clean(); ?>