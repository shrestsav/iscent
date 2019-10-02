<?php

class colors extends object_class
{

    private $productF;
    public $var_del;
    public $var_edit;
    public $var_edit_fromName;



    function __construct()
    {
        parent::__construct('3');

        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            require_once(__DIR__."/../functions/product_function.php");
            $this->productF=new product_function();
        }


        $this->var_del = "deletecolorById";
        $this->var_edit = "editcolorById";
        $this->var_edit_fromName = "editcolorForm";


        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        //colors.php
        $_w['Color Management'] = '' ;
        $_w['List'] = '' ;
        $_w['Add New Color'] = '' ;
        $_w['View All Colors'] = '' ;
        $_w['Color Name'] = '' ;
        $_w['Add Slot'] = '' ;
        $_w['Add Color'] = '' ;
        $_w['SUBMIT'] = '' ;
        //This class
        $_w['New Color Add SuccessFully!'] = '' ;
        $_w['Some required fields are empty!'] = '' ;
        $_w['Color Fields'] = '' ;
        $_w['Action'] = '' ;
        $_w['There is an issue while inserting data, please try again!'] = '' ;

        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin Product Color');


    }

    public function colorList()
    {
        $data = $this->getDataSQL(); // Return muti array of color name and color or return false
        $this->listView($data); // return Name and colors that are in name category
    }

    public  function getDataSQL($nameId = false)
    {
        if ($nameId) {
            $nameId = intval($nameId);
            $sql = "SELECT * FROM `color_name` `a` WHERE `a`.`colorName_id` = '$nameId' ";
        } else {
            $sql = "SELECT * FROM `color_name` `a` ORDER BY `a`.`colorName_name` ASC ";
        }
        $qry = $this->db->query($sql);
        if ($qry->rowCount() > 0) {
            $data = "";
            while ($data_x = $qry->fetch()) {
                $x_color = "";
                $sql_2 = "SELECT * FROM `colors` WHERE `color_name_id` = $data_x[colorName_id] ";
                $qry_2 = $this->db->query($sql_2);
                if ($qry_2->rowCount() > 0) {
                    while ($d = $qry_2->fetch()) {
                        $x_color[] = $d;
                    }
                }
                $x_array["name"] = $data_x;
                $x_array["color"] = $x_color;
                $data[] = $x_array;
            }
            return $data; // Return muti array of color name and color
        } else {
            return false;
        }
    }


    public function processAddcolor($formField = false)
    {
        global $_e;
        if ($formField) {
            if (isset($_POST[$formField])) {
                if(!$this->functions->getFormToken('productColorAdd')){
                    return false;
                }
                $form = $_POST[$formField];
                $name = $form['name'];
                $colors_from = $form['color'];
                $colors = false;
                foreach ($colors_from as $key => $val) {
                    if (!empty($val)) {
                        $colors[] = $val;
                    }
                }
                if ($colors !== false && !empty($name)) {
                    $this->addcolorSQL($name, $colors);
                } else {
                        bs_alert::danger(_uc($_e["Some required fields are empty!"]));
                }
            }
        }
    }

    private function addcolorSQL($name, $colors)
    {
        global $_e;
        $query="INSERT INTO `color_name` (`colorName_name`) VALUES (?)";
        $arry=array($name);

        $sql = 'INSERT ' . 'INTO  `colors` (`color_name_id`,`color_name`)  VALUES ';
        $format='?';
        if($this->dbF->setRowMultiTable($query,$arry,$sql,$format,$colors)) {
            bs_alert::success(_uc($_e["New Color Add SuccessFully!"]));
        }
        else {
            bs_alert::danger(_uc($_e["There is an issue while inserting data, please try again!"]));
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
                    <th>". _u($_e['Color Name']) ."</th>
                    <th>". _u($_e['Color Fields']) ."</th>
                    <th>". _u($_e['Action']) ."</th>
                </tr>
            </thead>
        ";

        if(is_array($data)){
            foreach ($data as $val) {
                $name = $val['name'];
                echo "<tr>
                <td class='$name[colorName_id]_name'  width='35%'>
                $name[colorName_name] </td>
                <td class='$name[colorName_id]_color'  width='35%'>";

                if (is_array($val["color"])) {
                    foreach ($val["color"] as $color) {
                        echo "<div class='colorBox' style='background-color:#$color[color_name]' ></div>";
                    }
                }
                echo " </td>
            <td>
            <div class='btn-group btn-group-sm'>
            <a data-id='$name[colorName_id]'  data-target='#colorEditModal' onclick='AjaxEditScript(this);' class='btn _colorAjax_edit'><i class='glyphicon glyphicon-edit '></i></a>
            <a data-id='$name[colorName_id]' onclick='AjaxDelScript(this);' class='btn  secure_delete'>
                <i class='glyphicon glyphicon-trash trash'></i>
                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
            </a>
        </div>

            </td>
            </tr>";
            }
        }
        echo "</table>";

        $this->productF->AjaxEditScript('colorAjax_edit','color');
        $this->productF->AjaxDelScript('colorAjax_del','color');
        $this->productF->AjaxUpdateScript('AjaxUpdate_color','color');

    }




}

?>