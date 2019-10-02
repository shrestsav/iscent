<?php

class scales
{
    private $db;
    private $dbF;
    private $functions;
    private $productF;

    public $var_del;
    public $var_edit;
    public $var_edit_fromName;


    function __construct()
    {
        if (isset($GLOBALS['db'])) $this->db = $GLOBALS['db'];
        else  $this->db = new Database();

        if (isset($GLOBALS['dbF'])) $this->dbF = $GLOBALS['dbF'];
        else  $this->dbF=new dbFunction();

        if (isset($GLOBALS['functions'])) $this->functions = $GLOBALS['functions'];
        else $this->functions=new admin_functions();

        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            require_once(__DIR__."/../functions/product_function.php");
            $this->productF=new product_function();
        }

        $this->var_del = "deleteScaleById";
        $this->var_edit = "editScaleById";
        $this->var_edit_fromName = "editScaleForm";

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        //scales.php
        $_w['Add Slot'] = '' ;
        $_w['SUBMIT'] = '' ;
        $_w['Scale Name'] = '' ;
        $_w['Add New Scale'] = '' ;
        $_w['Add Scale'] = '' ;
        $_w['List'] = '' ;
        $_w['Scale Management'] = '' ;
        $_w['Scale Fields'] = '' ;
        $_w['ACTION'] = '' ;
        $_w['Add Slot'] = '' ;
        $_w['Some required fields are empty!'] = '' ;
        $_w['There is an issue while inserting data, please try again!'] = '' ;
        $_w['New Scale Add SuccessFully!'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Product Scale');

    }

    public function scaleList()
    {
        $data = $this->getDataSQL();
        $this->listView($data);

    }

    public function  getDataSQL($nameId = false)
    {
        if ($nameId) {
            $nameId = intval($nameId);
            $sql = "SELECT * FROM `scale_name` `a` WHERE `a`.`scaleName_id` = '$nameId' ";
        } else {
            $sql = "SELECT * FROM `scale_name` `a` ORDER BY `a`.`scaleName_name` ASC ";
        }
        $qry = $this->db->query($sql);
        if ($qry->rowCount() > 0) {
            $data = "";
            while ($data_x = $qry->fetch()) {
                $x_scale = "";
                $sql_2 = "SELECT * FROM `scales` WHERE `scale_name_id` = $data_x[scaleName_id] ";
                $qry_2 = $this->db->query($sql_2);
                if ($qry_2->rowCount() > 0) {
                    while ($d = $qry_2->fetch()) {
                        $x_scale[] = $d;
                    }
                }
                $x_array["name"] = $data_x;
                $x_array["scale"] = $x_scale;
                $data[] = $x_array;
            }
            return $data;
        } else {
            return false;
        }
    }


    public function processAddScale($formField = false)
    {
        global $_e;
        if ($formField) {
            if (isset($_POST[$formField])) {
                if(!$this->functions->getFormToken('productScaleAdd')){
                    return false;
                }

                $form = $_POST[$formField];
                $name = $form['name'];
                $scales_from = $form['scale'];
                $scales = false;
                foreach ($scales_from as $key => $val) {
                    if (!empty($val)) {
                        $scales[] = $val;
                    }
                }
                if ($scales !== false && !empty($name)) {
                    $this->addScaleSQL($name, $scales);
                } else {
                    bs_alert::danger(_uc($_e["Some required fields are empty!"]));
                }
            }
        }
    }

    private function addScaleSQL($name, $scales)
    {
        global $_e;
        if (is_array($scales)) {
            $query = "INSERT INTO `scale_name` (`scaleName_name`) VALUES (?)";
            $arry=array($name);

            $sql = 'INSERT ' . 'INTO  `scales` (`scale_name_id`,`scale_name`)  VALUES ';
            $format='?';
            if($this->dbF->setRowMultiTable($query,$arry,$sql,$format,$scales)){
                bs_alert::success(_uc($_e["New Scale Add SuccessFully!"]));
            } else {
                bs_alert::danger(_uc($_e["There is an issue while inserting data, please try again!"]));
            }

        } else {
            return false;
        }
    }


    private function listView($data)
    {
        global $_e;
        $this->functions->dTableT();
        echo "<table class='table table-hover dTableT tableIBMS table-responsive'>";
        echo "
            <thead>
                <tr>
                    <th>". _u($_e['Scale Name']) ."</th>
                    <th>". _u($_e['Scale Fields']) ."</th>
                    <th>". _u($_e['ACTION']) ."</th>
                </tr>
            </thead>
        ";
        if(is_array($data)){
            foreach ($data as $val) {
                $name = $val['name'];
                echo "<tr>
                <td class='$name[scaleName_id]_name' width='35%'>
                $name[scaleName_name] </td>
                <td class='$name[scaleName_id]_scale' width='40%'>";

                if (is_array($val["scale"])) {
                    $temp='';
                    foreach ($val["scale"] as $scale) {
                        $temp .= $scale['scale_name'] . ', ';
                    }
                    $temp= trim($temp);
                    echo trim($temp,',');
                }
                echo " </td>
            <td>
            <div class='btn-group btn-group-sm'>
            <a data-id='$name[scaleName_id]' data-target='#scaleEditModal' onclick='AjaxEditScript(this);' class='btn _scaleAjax_edit'><i class='glyphicon glyphicon-edit'></i></a>
            <a data-id='$name[scaleName_id]' onclick='AjaxDelScript(this);' class='btn scaleAjax_del secure_delete'>
                <i class='glyphicon glyphicon-trash trash'></i>
                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
            </a>
        </div>

            </td>
            </tr>";
            }
        }

        echo "</table>";


        $this->productF->AjaxEditScript('scaleAjax_edit','scale');
        $this->productF->AjaxDelScript('scaleAjax_del','scale');

        $this->productF->AjaxUpdateScript('AjaxUpdate_scale','scale');
    }

    private function createEditForm($id)
    {
        global $_e;
        $data = $this->getDataSQL($id);

        $name = $data[0]['name'];
        $scales = $data[0]['scale'];


        $i = 0;
        $trs = "";
        foreach ($scales as $scale) {
            $i++;
            $trs .= "
                <tr>
                    <td> $i) <input type='checkbox' name='$this->var_edit_fromName[scaleDel][]' value='$scale[scale_id]'  > </td>
                    <td> <input type='text' class='inp' name='$this->var_edit_fromName[scale][$scale[scale_id]]' value='$scale[scale_name]' > </td>
                </tr>
            ";
        }


        $mod_edit = new bootstrap_model();
        $mod_edit->title = "Edit Scale";
        $mod_edit->formOkBtnText = "Update";
        $mod_edit->body = '

        <input type="hidden" name="'. $this->var_edit_fromName['id'].'" value="'.$name['scaleName_id'].'">

        '. _uc($_e['Scale Name']) .' : <input type="text" autocomplete="off" class="inp" name="'. $this->var_edit_fromName['name'].'" value="'.$name['scaleName_name'].'">

        <br /><br />

       <table id="slot_table2" class="slot_table">
                        <tbody>
                        '.$trs.'
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary" onclick="addSlot2(); return false;">
                        <i class="icon_bs-plus"></i> '._uc($_e['Add Slot']).'
                    </button>

                    <button type="submit" class="btn btn-success"> '._uc($_e['Add Scale']).' </button>';

        echo $mod_edit->getModel();
        echo "
            <script type='text/javascript'>
                $('#$mod_edit->id').modal('show');
            </script> ";

    }


}

?>