<?php



class p_category extends object_class{



    public function get_all_category($view="array"){

        $data = $this->get_category_data();

        $data = $this->category_translate($data);

        return $data;

    }



    private function category_translate($data_single_array,$lang=false){

        if($lang==false && $this->functions->isAdminLink()) {

            $lang = $this->functions->AdminDefaultLanguage();

        }else{

            $lang = currentWebLanguage();

        }



        $_w = array();

        foreach($data_single_array as $val){

            $_w[$val['name']] = '';

        }



        $_t = $this->dbF->hardWordsMulti($_w,$lang,'product category');

        foreach($data_single_array as $key=>$val){

            $data_single_array[$key]['name'] = $_t[$val['name']];

        }

        return $data_single_array;

    }



    public function categoryArraySearch($data,$under){

        $temp = array();

        foreach($data as $val){

            if($val['under']==$under){

                $val['has-sub'] = '0'; // 2nd and 3rd array initital value

                $temp[$val['name']] = $val;

            }

        }

        return $temp;

    }



    public function get_category_data($level=false,$parentId=false){

        $parent = '';

        if($parentId!=false){

            $parent = " AND under = '$parentId'";

        }

        if($level===false){

            $level = "";

        }else{

            $level = " AND lvl = '$level'";

        }

        $sql = "SELECT * FROM `categories` WHERE `id` != '1' $parent ORDER BY sort ASC";

        // $sql    =   "SELECT * FROM tree_struct JOIN tree_data

        //                 ON  tree_struct.id = tree_data.id

        //                  WHERE tree_data.id != '1' $level $parent ORDER BY pos ASC";

        $data   =   $this->dbF->getRows($sql);

        return      $data;

    }



    /**

     * Grid View or List view from setting or from url

     * @param bool|false $cat_id

     * @return string

     */

    public function get_view_option($cat_id = false){

        $views      = $this->functions->ibms_setting("grid_view");

        //$session_view = getUserSession("grid_view");

        $view       = "grid";

        if(!empty($views)){

            $views  = unserialize($views);

            if(!empty($cat_id) && !empty($views["cat_$cat_id"])){

                $view = $views["cat_$cat_id"];

            }elseif(!empty($views["default"])){

                $view = $views["default"];

            }

        }

        $view = isset($_SESSION['viewType']) ? $_SESSION['viewType'] : $view;
        //$view = 'List';

        return $view;

    }

}