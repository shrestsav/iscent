<?php
class menu extends object_class{
    public function __construct(){
        parent::__construct('3');

        global $_e;
        $_w = array();
        $_w['More'] = '';
        $_e = $this->dbF->hardWordsMulti($_w,currentWebLanguage(),'WebMenu');
    }

    public function mobiflexMyMenule_menu($mailUlId='',$mainUlClass='',$liClass='',$aClass='',$ulSubMenuClass='',$liSubMenuClass='',$aSubMenuClass=''){
        //usage not found
        //
        $sql = "SELECT * FROM webmenu WHERE under = '0' ORDER BY sort ASC";
        $data  = $this->dbF->getRows($sql);

        if(!$this->dbF->rowCount){return false;}

        echo "<ul id='$mailUlId'>";
        $webLang = currentWebLanguage();
        foreach($data as $val){
            $id = $val['id'];
            $heading = htmlspecialchars(translateFromSerialize($val['name']));

            $link = $val['link'];
            if(preg_match('@http://@i',$link) || preg_match('@https://@i',$link)){
            }else{
                $link = WEB_URL.$link;
            }

            //get SubMenu
            $getSubMenu = $this->main_menuFindSubMenu($id,$ulSubMenuClass,$liSubMenuClass,$aSubMenuClass);
            echo "<li class='$liClass' id='$id'><a href='$link' class='$aClass'>$heading</a>
                         $getSubMenu
                  </li>";
        }
        echo "</ul>";
        $temp = <<<HTML
        <script>
            $(document).ready(function(){
                $('#$mailUlId').slicknav();
            });
        </script>

HTML;
        echo $temp;
    }


    /**
     * Format <ul><li><a>Menu</a></li</ul>
     * @return string
     */

    private $mainParentActive  = false;
    private $subParentActive = false;
    private $subMenuActive  = false;
    private $sub2MenuActive = false;
    public function main_menu($type='main',$responsive = false,$mainUlId='',$mainUlClass='',$liClass='',$aClass='',$ulSubMenuClass='',$liSubMenuClass='',$aSubMenuClass=''){
        global $_e;
        $sql = "SELECT * FROM webmenu WHERE under = '0' AND type='$type' ORDER BY sort ASC";
        $data  = $this->dbF->getRows($sql);

        if(!$this->dbF->rowCount){return false;}

        //finding icon allow or not
        $types  = $this->functions->developer_setting('main_menu_type');
        $types   = explode("/",$types);
        foreach($types as $val){
            $val = explode(",",$val);
            $value = $val[0];
            if($value==$type){
                $iconAllow = $val[1];
                break;
            }
        }

        $IBMSWebMenuClass = '';
        if($type == 'main'){
            $IBMSWebMenuClass = 'IBMSWebMenu flex';
        }

        echo "<ul class='$IBMSWebMenuClass $mainUlClass' id='$mainUlId'>";
        $webLang = currentWebLanguage();
        $defaultLang = defaultWebLanguage();
        $main_menu_icon = $iconAllow;
        foreach($data as $val){
            $id = $val['id'];
            $heading = htmlspecialchars(translateFromSerialize($val['name']));

            $link = $val['link'];
            $link = $this->functions->addWebUrlInLink($link);

            $active  = '';
            $activeLink = pageLink(false);
            if($activeLink == $link){
                $active = 'mainActive';
            }

            //get SubMenu
            $getSubMenu = $this->main_menuFindSubMenu($id,$ulSubMenuClass,$liSubMenuClass,$aSubMenuClass,$heading,$main_menu_icon);
            $hasSubMenu = ''; //Use Class Name
            $dropCaret = '';
            if($getSubMenu==false){$getSubMenu='';}else{
                if($this->subMenuActive){
                    $active = 'mainActive';
                }
                $hasSubMenu = 'hasSubMenu';
                $dropCaret = "
                                <span class='fa fa-caret-down menuDropDownCaret'></span>
                                <span class='fa fa-caret-left menuDropDownCaretMore'></span>
                                ";
            }


            $icon = '';
            if($main_menu_icon=='1'){
                $icon = $this->functions->addWebUrlInLink($val['icon']);
                if($icon==""){
                    $icon = WEB_URL."/images/blank.png";
                    $icon = 'style="background-image:url(' . "'$icon'" . ')"';
                }else{
                    $icon = 'style="background-image:url(' . "'$icon'" . ')"';
                }
            }

            echo "<li class='$liClass $active $hasSubMenu menuFirstLi' id='$id'>
                        <a href='$link' class='$aClass' $icon><span>$heading</span> $dropCaret</a>
                         $getSubMenu

                      </li>";
        }
        echo "</ul>";
        $temp = "
            <script>
               $(document).ready(function(){
                    setTimeout(function(){
                        $('ul.flex').flexMenu({
                            linkText:'<span>"._u($_e['More'])." </span>'
                        });
                    },500);
                });
            </script> ";

        if($responsive && $type='main'){
            $temp .= "
            <script>
                $(document).ready(function(){
                    $('.IBMSWebMenu').slicknav();
                });
            </script>";
        }
        echo $temp;
    }


    public function main_menuFindSubMenu($id,$ulSubMenuClass,$liSubMenuClass,$aSubMenuClass,$heading='',$main_menu_icon=''){
        $temp = '';
        $sql = "SELECT * FROM webmenu WHERE under = '$id' ORDER BY sort ASC";
        $data  = $this->dbF->getRows($sql);

        if(!$this->dbF->rowCount){return false;}

        $temp .= "<ul class='firstSubUl menu-sub $ulSubMenuClass'>";
        $webLang = currentWebLanguage();
        $defaultLang = defaultWebLanguage();
        $this->subMenuActive       = false;
        foreach($data as $val){
            $id = $val['id'];
            $heading = htmlspecialchars(translateFromSerialize($val['name']));

            $link = $val['link'];
            $link = $this->functions->addWebUrlInLink($link);


            $active  = '';

            $activeLink = pageLink(false);
            if($activeLink == $link){
                $active = 'subActive';
                $this->subMenuActive   = true;
            }

            //get SubMenu
            $getSubMenu2 = $this->main_menuFindSubMenu2($id,$ulSubMenuClass,$liSubMenuClass,$aSubMenuClass);
            $hasSubMenu2 = ''; //Use Class Name

            $dropCaret = '';
            if($getSubMenu2==false){$getSubMenu2='';}else{
                //var_dump($this->sub2MenuActive);
                if($this->sub2MenuActive){
                    $active = 'subActive';
                    $this->subMenuActive   = true;
                }
                $hasSubMenu2 = 'hasSubMenu2';
                $dropCaret = "
                                <span class='fa fa-caret-right menuDropDownCaretRight'></span>
                                 <span class='fa fa-caret-left menuDropDownCaretRightMore'></span>
                                ";
            }

            $temp .= "<li class='$liSubMenuClass $active $hasSubMenu2' id='$id'><a href='$link'  class='$aSubMenuClass'>$heading $dropCaret</a>
                            $getSubMenu2
                          </li>";
        }
        $temp .= "</ul>";
        return $temp;
    }

    public function main_menuFindSubMenu2($id,$ulSubMenuClass,$liSubMenuClass,$aSubMenuClass){
        $temp = '';
        $sql = "SELECT * FROM webmenu WHERE under = '$id' ORDER BY sort ASC";
        $data  = $this->dbF->getRows($sql);

        if(!$this->dbF->rowCount){return false;}

        $temp .= "<ul class='secondSubUl $ulSubMenuClass'>";
        $webLang = currentWebLanguage();

        $defaultLang = defaultWebLanguage();
        $this->sub2MenuActive       = false;
        foreach($data as $val){
            $id = $val['id'];
            $heading = htmlspecialchars(translateFromSerialize($val['name']));

            $link = $val['link'];
            $link = $this->functions->addWebUrlInLink($link);


            $active  = '';

            $activeLink = pageLink(false);
            if($activeLink == $link){
                $active = 'sub3Active';
                $this->sub2MenuActive       = true;
            }

            $temp .= "<li class='$liSubMenuClass $active' id='$id'><a href='$link' class='$aSubMenuClass'>$heading</a></li>";

        }
        $temp .= "</ul>";
        return $temp;
    }


    /**
     * @param string $type main|top
     * @param string $under Parent Id
     * @return array
     *
    $infoMenu = $menuClass->menuTypeSingle('info');
    $infoMenu = empty($infoMenu) ? array() : $infoMenu;//stop exception
    foreach ($infoMenu as $val) {
    $text   = _u($val['name']);
    $menuId = $val['id'];
    $link   = $val['link'];
    $menuIcon = $val['icon'];

    $infoMenu2 = $menuClass->menuTypeSingle('info',$menuId);
    $infoMenu2 = empty($infoMenu2) ? array() : $infoMenu2; //stop exception
    foreach ($infoMenu2 as $val2) {
    $text   = _u($val2['name']);
    $menuId = $val2['id'];
    $link   = $val2['link'];
    $menuIcon = $val2['icon'];
    echo '<li><a href="' . $link . '" class="">' . $text . '</a></li>';
    }
    }
     */
    public function menuTypeSingle($type='main',$under='0',$url_function=false){
        global $_e;
        $sql = "SELECT * FROM webmenu WHERE under = '$under' AND type='$type' ORDER BY sort ASC";
        $data  = $this->dbF->getRows($sql);

        if(!$this->dbF->rowCount){return false;}
        foreach($data as $val){
            $id = $val['id'];
            $heading = htmlspecialchars(translateFromSerialize($val['name']));

            $link = $val['link'];
            if ($url_function) {
                $link = $this->functions->addCatRegexWebUrlInLink($link);
            } else {
                $link = $this->functions->addWebUrlInLink($link);
            }

            $icon = $val['icon'];
            $icon = $this->functions->addWebUrlInLink($icon);

            $array["$id"]['name']   = $heading;
            $array["$id"]['link']   = $link;
            $array["$id"]['icon']   = $icon;
            $array["$id"]['id']     = $id;
            $array["$id"]['active']= '';
            $activeLink = pageLink(false);
            if( ($activeLink) == ($link)){
                $array["$id"]['active']= '1';
            }
        }

        return $array;
    }


    public function menuTypeSingle1($type='main',$under='0',$url_function=false){
        global $_e;
        $sql = "SELECT * FROM categories WHERE under = '$under' AND type='$type' ORDER BY sort ASC";
        $data  = $this->dbF->getRows($sql);

        if(!$this->dbF->rowCount){return false;}
        foreach($data as $val){
            $id = $val['id'];
            $heading = htmlspecialchars(translateFromSerialize($val['name']));

            $link = $val['link'];
            if ($url_function) {
                $link = $this->functions->addCatRegexWebUrlInLink($link);
            } else {
                $link = $this->functions->addWebUrlInLink($link);
            }

            $icon = $val['icon'];
            $icon = $this->functions->addWebUrlInLink($icon);

            $array["$id"]['name']   = $heading;
            $array["$id"]['link']   = $link;
            $array["$id"]['icon']   = $icon;
            $array["$id"]['id']     = $id;
            $array["$id"]['active']= '';
            $activeLink = pageLink(false);
            if( ($activeLink) == ($link)){
                $array["$id"]['active']= '1';
            }
        }

        return $array;
    }

    /**
     * @param $id
     * @param string $where
     * @param string $type
     * @return mixed
     *
     * Find parent menu by Child menu Id
     */
    public function get_root_menu($id,$where="id",$type="main"){
        global $_e;

        $sql    = "SELECT * FROM webmenu WHERE $where = '$id' AND type='$type'";
        $data   = $this->dbF->getRow($sql);

        if(@intval($data['under']) > 0){
            return $this->get_root_menu($data['under'],"id",$type);
        }

        return $data;

    }

    /**
     * @param bool $limit
     * @param int $limitFrom
     * @param int $limitTo
     * @return array
     */
    public function footerMainSingleMenu($limit = false,$limitFrom=0,$limitTo = 10){
        if($limit==false){
            $limit = '';
        }else{
            $limit = " LIMIT $limitFrom,$limitTo";
        }
        $sql = "SELECT * FROM webfootermenu WHERE under  = '0' ORDER BY sort ASC $limit";
        $data = $this->dbF->getRows($sql);
        $webLang = currentWebLanguage();
        $defaultLang = defaultWebLanguage();
        $array = array();
        foreach($data as $val){
            $id = $val['id'];
            $heading = htmlspecialchars(translateFromSerialize($val['name']));

            $link = $val['link'];
            if(preg_match('@http://@i',$link) || preg_match('@https://@i',$link)){
            }else{
                $link = WEB_URL.$link;
            }

            $array["$id"]['menu']   = $heading;
            $array["$id"]['link']   = $link;
            $array["$id"]['id']     = $id;
        }

        return $array;
    }


    public function footerInnerMenu($parentId,$limit = false,$limitFrom=0,$limitTo = 10){
        if($limit==false){
            $limit = '';
        }else{
            $limit = " LIMIT $limitFrom,$limitTo";
        }
        $sql = "SELECT * FROM webfootermenu WHERE under  = '$parentId' ORDER BY sort ASC $limit";
        $data = $this->dbF->getRows($sql);
        if(!$this->dbF->rowCount){return false;}
        $webLang = currentWebLanguage();
        $defaultLang = defaultWebLanguage();
        $array = array();
        foreach($data as $val){
            $id = $val['id'];
            $heading = htmlspecialchars(translateFromSerialize($val['name']));

            $link = $val['link'];
            if(preg_match('@http://@i',$link) || preg_match('@https://@i',$link)){
            }else{
                $link = WEB_URL.$link;
            }

            $array["$id"]['menu']   = $heading;
            $array["$id"]['link']   = $link;
            $array["$id"]['id']     = $id;
        }

        return $array;

    }


    public function footerAllMenu()
    {
        $footerMenu = $this->footerMainSingleMenu();
        if(empty($footerMenu)){
            return false;
        }
        foreach ($footerMenu as $val) {
            $text = _u($val['menu']);
            $footerMenuId = $val['id'];
            $FooterLink = $val['link'];
            echo ' <div class="f_links">
                    <h1>' . $text . '</h1>
                    <ul>';
            $footerMenu2 = $this->footerInnerMenu($footerMenuId);
            if(empty($footerMenu2)){
                echo '</ul>
                        </div>
                        ';
               continue;
            }
            foreach ($footerMenu2 as $val2) {
                $text = _u($val2['menu']);
                $footerMenuId = $val2['id'];
                $FooterLink = $val2['link'];
                echo '<li><a href="' . $FooterLink . '" class="">' . $text . '</a></li>';
            }

            echo '</ul>
            </div>
            ';
        }
    }

} ?>