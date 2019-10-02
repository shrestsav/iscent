<?php
//don't encrypt this
/**
 * @param $WordInScriptAddSlashes
 * @return string
 * Use for Addslashes special for In js
 */
function _js($WordInScriptAddSlashes){
    //$WordInScriptAddSlashes = trim(preg_replace('/\s\s+/', '<br>', $WordInScriptAddSlashes));
    return addslashes($WordInScriptAddSlashes);
}

/**
 * @param $WordNormal
 * @return string
 * Use for Noraml output, Need if any in future, so change in this functions, instance of all project.
 */
function _n($WordNormal){
    return $WordNormal;
}

/**
 * @param $WordForLowerLetters
 * @return string
 * (PHP 4 >= 4.3.0, PHP 5)
 * use for lower out put, support test language:  swedish, chines, french, urdu english.
 */
function _l($WordForLowerLetters){
    return mb_strtolower($WordForLowerLetters,'UTF-8');
}
/**
 * @param $WordForUpperLetters
 * @return string
 * (PHP 4 >= 4.3.0, PHP 5)
 * use for Upper out put, support test language:  swedish, chines, french, urdu english.
 */
function _u($WordForUpperLetters){
    return mb_strtoupper($WordForUpperLetters,'UTF-8');
}
/**
 * @param $WordAllFirstLetterUpper
 * @return string
 * (PHP 4 >= 4.3.0, PHP 5)
 * use for First letter Upper out put, support test language :  swedish, chines, french, urdu english.
 */
function _uc($WordAllFirstLetterUpper){
    return mb_convert_case(_l($WordAllFirstLetterUpper), MB_CASE_TITLE, "UTF-8");
}

/**
 * @param $WordOnlyFirstLetterUpper
 * @return string
 * (PHP 4 >= 4.3.0, PHP 5)
 * use for First letter Upper out put, support test language :  swedish, chines, french, urdu english.
 */
function _fu($WordOnlyFirstLetterUpper){
    return _fc($WordOnlyFirstLetterUpper);
}
/**
 * @param $WordOnlyFirstLetterCapital
 * @return string
 * (PHP 4 >= 4.3.0, PHP 5)
 * use for First letter Upper out put, support test language :  swedish, chines, french, urdu english.
 */
function  _fc($WordOnlyFirstLetterCapital){
    $firstLetter    =   substr($WordOnlyFirstLetterCapital,0,1);
    $AllOtherLetter =   substr($WordOnlyFirstLetterCapital,1);
    return _u($firstLetter).''._l($AllOtherLetter);
}

/**
 * @param $replaceFrom
 * @param $replaceTo
 * @param $key
 * @return mixed
 *
 * Simple replace function ,, in futer want to change any thing, simple change here
 */
function _replace($replaceFrom,$replaceTo,$key){
    return str_ireplace($replaceFrom,$replaceTo,$key);
}

trait multi_lang
{


    public function hardWords($en,$echo = true){
        $weblang    =   currentWebLanguage();
        $language   =   $weblang;
        if($language == 'default' || $language == '' || $language === false){
            if($echo){
                echo $en;
            }
            else{
                return $en;
            }
            return;
        }
        $sql = "SELECT `lang` from hardwords Where en = '$en'";
        $data = $this->getRow($sql);
        if(!function_exists('currentWebLanguage')){
            if($echo){
                echo $en;
            }
            else{
                return $en;
            }
            return;
        }

        if($this->rowCount>0){
            $value = $data['lang'];

            @$langWord = unserialize($value);
            $serial =   true;
            if($langWord===false){
                $serial =   false;
            }

            if($serial){
                @$value = $langWord[$weblang];
            }

            if($echo){
                echo $value;
            }
            else{
                return $value;
            }
        }else{
            $this->hardWordInsert($en);
            if($echo){
                echo $en;
            }
            else{
                return $en;
            }
        }
    }


    private function hardWordInsert($word,$callingFrom='admin'){
        $sql    =   "INSERT INTO hardwords (`en`,`lang`,`place`) values(?,?,?)";
        $array  =   array($word,$word,$callingFrom);
        $this->setRow($sql,$array);
    }


    /**
     * @param $enArray words
     * @param $language in which want return
     * @param $callingFrom Write page or location to help in edit mode
     * @param $addSlash if some problem with special letters, you add \' before your words then write false, if you use "asad'raza"
     * then leave blank or true, function will handel your special letters
     * @return array
     * Usage :
     *  $_w = array();
     *  $_w['DASHBOARD']="";
    $_w['This is place where everything started']="";
    $_e    =   $dbF->hardWordsMulti($_w,$functions->AdminDefaultLanguage());
     *
     *
     * echo $_e['DASHBOARD']; OR echo $_e['0'];
     * Best if use $_e['word']; because $_e['0']; some time replace with other words
     *
     * //New echo $_e['0']; will not work more, code in comment due to large array
     *
     * //Or also replace number from admin
     *  $_e['{{number}} New Defect Product'] =''; // Define
     *  $number = '5';
    echo str_replace('{{number}}',$number,$_e['{{number}} New Defect Product']);
     * result : 5 new Defect Product
     *
     *  :-)
     *
     *
     * MultiLanguage keys Use where echo;
     * define this class words and where this class will call
     * and define words of file where this class will called
     *
     * IN Class construct
     * global $_e;
    global $defaultAdminLanguage;
     * then do same work
     *
     * In function
     * global $_e; // then echo words
     *
     * Where you want upper letter, and lower letter use functions
     *    echo _l($_e);
     * echo _u('AsadRaza');
     **/
    public function hardWordsMulti($enArray,$language,$callingFrom='admin',$addSlash=true){
        if($callingFrom=='' || $callingFrom===false || $callingFrom===true){
            $callingFrom = 'admin';
        }

        $defaultLang = false;
        if($language == 'default' || $language == '' || $language === false){
            $defaultLang = true;
        }

        if($defaultLang===false) {
                $en = ''; //English lang words,
                $repeat = array();
                foreach ($enArray as $key => $val) {
                    if ($addSlash === true) {
                        $key = addslashes($key);
                    }

                    if(isset($repeat[strtolower($key)])){
                       continue;
                    }
                    $repeat[strtolower($key)] = $val;
                    $en .= "'$key' ,";
                }
                $en = trim($en, ',');
                //var_dump($en);
                //$sql        = "SELECT `id`,`en`,`lang` from hardwords Where en in ($en) ORDER BY FIELD(en , $en)";
                $sql = "SELECT `id`,`en`,`lang` from hardwords Where en in ($en)"; //may be more fast when i dont want echo with index e.g: echo $_e['0'];

                $data = $this->getRows($sql, array());
                //$en in array not working
                //var_dump($data);
                $temp = array();

                $totalArray = $enArray;
                $i = 0;
                foreach ($data as $val) {
                    $value = $val['lang'];
                    @$langWord = unserialize($value);
                    $serial = true;
                    if ($langWord === false) {
                        $serial = false;
                    }

                    if ($serial) {
                        @$value = $langWord[$language];
                    }
                    //If language not found, Then may be its mean there is only written language word in lang field,
                    // so show lang in value, if value is blank, then key show. value blank mean, during getting lang work error show,

                    $key = $this->hardWordArrayFindKey($enArray, $val['en']);
                    if (!isset($value) || $value == '') {
                        $value = $key;
                    }

                    //Replace space to &nbsp;
                    $value = trim(str_replace('  ', '&nbsp; ', $value));
                    //Replace \n to <br>
                    $value = trim(preg_replace('/\s\s+/', '<br>', $value));

                    $temp["$key"] = $value;
                    $repeat[strtolower($key)] = $value;

                    //$temp[]     =   $value;
                    //$temp["$i"] =   $value;
                    $i++;
                    unset($totalArray[$key]);
                }

                foreach ($totalArray as $key => $val) {
                    //check is repeat word
                    if(isset($repeat[strtolower($key)]) && $repeat[strtolower($key)] != ''){
                        $temp["$key"] = $repeat[strtolower($key)];
                    }else {
                        $this->hardWordInsert($key, $callingFrom);
                        $temp["$key"] = $key;
                    }
                    //$temp["$i"]         =   $key;
                    $i++;
                }
        }else{
            foreach ($enArray as $key => $val) {
                $temp["$key"] = $key;
            }
        }
        //var_dump($temp);

        //Merge for multi time run this function,
        global $_e;
        //Once i see $_e is null, and error show, so check $_e is null
        if(!isset($_e) || empty($_e) || $_e =='' ){
            $_e =array();
        }

        //merge array problem was: it will replace all index from 0
        $_e     =   ($_e+$temp);
        //var_dump($_e);
        return $_e;
    }


    private function hardWordArrayFindKey($data,$key){
        //ind and sent key 100% same as received
        foreach($data as $keys=>$val){
            if(strtolower($key) == strtolower($keys)){
                return $keys;
            }
        }
        return $key;
    }


}

?>