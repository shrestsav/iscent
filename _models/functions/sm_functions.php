<?php
//small functions, are use in client side.. and need to call any where...
//functions with out in class

function translateFromSerialize($serializeData,$serialize=true){
    return getTextFromSerializeArray($serializeData,$serialize);
}

function getTextFromSerializeArray($serializeData,$serialize=true){
    $webLang        = currentWebLanguage();
    $defaultLang    = defaultWebLanguage();

    if($serialize==true) {
        @$tempA = unserialize($serializeData);
    }else{
        @$tempA = $serializeData;
    }
    //var_dump($tempA);
    if($tempA===false){
        return $serializeData;
    }

    @$temp      = $tempA[$webLang];
    if($temp===false || empty($temp)){
        //var_dump($temp);
        @$temp  = $tempA[$defaultLang];

        if(($temp===false || empty($temp)  && ($webLang == 'default' || $defaultLang =='default'))){
            //if still key not found then first key of array return in case of default... else blank return
            $temp = $tempA[key($tempA)];
        }
    }

    return $temp;
}

function textArea($text){
    //echo text same as textarea,, replace \n to <br>
    return nl2br($text);
}

function adminLoginCheckStatus(){
    //simple login check use for when need to check in website, and no traits there
    //2 step checking.,
    if(isset($_SESSION['_uid']) && $_SESSION['_uid']>0){
        switch ($_SESSION["_role"]):
            case "super_admin":
            case "admin":
            case "manager":
                return true;
                break;
        endswitch;
    }
    return false;

}

function currentWebLanguage(){
    global $functions;
    //Work For website Language
    $lang = '';
    if(isset($_SESSION['webUser']['webLang'])){
        $lang   =  $_SESSION['webUser']['webLang'];
    }
    else{
        $defaultWebLanguage = $functions->WebDefaultLanguage();
        $lang       =   $defaultWebLanguage;
    }

    $_SESSION['webUser']['webLang']  =  $lang;
    return $lang;
}

function defaultWebLanguage(){
    global $functions;
    //Work For website Language
    if(isset($_SESSION['webUser']['defaultLang'])){
        $lang   =  $_SESSION['webUser']['defaultLang'];
    }else {
        $defaultWebLanguage = $functions->WebDefaultLanguage();
        $lang = $defaultWebLanguage;
    }
    $_SESSION['webUser']['defaultLang'] = $lang;
    return $lang;
}

function setRememberMe($name,$value,$days){
    $hour = time() + (3600*24*$days);
    $value = serialize($value);
    setcookie("$name", $value, $hour);
    $_COOKIE["$name"] = '';
    $_COOKIE["$name"] = $value;
}

function getRememberMe($name){
    if(!empty($_COOKIE[$name])){
        return unserialize($_COOKIE["$name"]);
    }
    return false;
}

function resetCookie(){
    // Destroy all cookies.
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time()-1000);
            setcookie($name, '', time()-1000, '/');
        }
        @$_SERVER['HTTP_COOKIE'] = '';
    }
}

function loginRememberMe(){
    if(getRememberMe("webUser")!=false){
        $array = getRememberMe("webUser");
        if(isset($array['remember']) && $array['remember'] =='1') {
            $_SESSION['webUser'] = $array;
        }
    }
}

function webUserSession(){
    global $functions;
    //Set Session if New User.
    if(!isset($_SESSION['webUser']['login']) && !isset($_SESSION['webUser']['tempId'])){
        $_SESSION['webUser']['login']   =   '0';
        $_SESSION['webUser']['id']      =   '0';
        $_SESSION['webUser']['tempId']  =   uniqid()."_".uniqid();
        //tempId blank if user login, transfer tempId in old temp id on login,
        $_SESSION['webUser']['oldTempId'] =   '';
        $_SESSION['webUser']['name']    =   '';
        //loginRememberMe();
    }
    return $_SESSION['webUser'];
}

function setUserSession($name,$val='1'){
    $_SESSION['webUser'][$name]    =  $val;
}
function getUserSession($name){
    if(isset($_SESSION['webUser'][$name])){
        return $_SESSION['webUser'][$name];
    }else{
        return false;
    }
}

function webUserName(){
    $userData   = webUserSession();
    return $userData['name'];
}

function webUserId(){
    $userData   = webUserSession();
    return $userData['id'];
}

function webTempUserId(){
    $userData   = webUserSession();
    return $userData['tempId'];
}

function webUserOldTempId(){
    $userData   = webUserSession();
    return $userData['oldTempId'];
}

function clientId(){
    //tell id if not then temp id
    $id = webUserId();
    if($id=='0'){
        $id = webTempUserId();
    }

    return $id;
}

function userLoginCheck(){
    $userData   = webUserSession();
    if($userData['id']=='0')
        return false;
    return true;
}

function pageLink($addParameterSeprator=true){
    global $db;
    $linkPage   =   $db->defaultHttp."".$_SERVER['HTTP_HOST']."".urldecode($_SERVER['REQUEST_URI']);
    if(isset($_GET) && $addParameterSeprator){
        $linkPage .= "&";
    }elseif($addParameterSeprator){
        $linkPage .= "?";
    }
    return $linkPage;
}

function array_delete($array, $value) {
    foreach($array as $key=>$val){
        if($val['prodet_id'] == $value){
            unset($array[$key]);
        }
    }
    return $array;
}

function removeSpace($string){
    //remove \n new line and extra space
    $string = trim(preg_replace('/\s\s+/', ' ', $string));
    return $string;
}

//work on latin content type
function specialChar_to_english_letters($text){
    return preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($text));
}

//some time specialChar_to_english_letters not work, due to utf8 or latin content-type
//work on uft-8 content type
function specialChar_to_english_letters2($txt) {
    $transliterationTable = array('á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', '?' => 'a', '?' => 'A', 'â' => 'a', 'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', '?' => 'a', '?' => 'A', '?' => 'a', '?' => 'A', 'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', '?' => 'b', '?' => 'B', '?' => 'c', '?' => 'C', '?' => 'c', '?' => 'C', '?' => 'c', '?' => 'C', '?' => 'c', '?' => 'C', 'ç' => 'c', 'Ç' => 'C', '?' => 'd', '?' => 'D', '?' => 'd', '?' => 'D', '?' => 'd', '?' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', '?' => 'e', '?' => 'E', 'ê' => 'e', 'Ê' => 'E', '?' => 'e', '?' => 'E', 'ë' => 'e', 'Ë' => 'E', '?' => 'e', '?' => 'E', '?' => 'e', '?' => 'E', '?' => 'e', '?' => 'E', '?' => 'f', '?' => 'F', 'ƒ' => 'f', '?' => 'F', '?' => 'g', '?' => 'G', '?' => 'g', '?' => 'G', '?' => 'g', '?' => 'G', '?' => 'g', '?' => 'G', '?' => 'h', '?' => 'H', '?' => 'h', '?' => 'H', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', '?' => 'i', '?' => 'I', '?' => 'i', '?' => 'I', '?' => 'i', '?' => 'I', '?' => 'j', '?' => 'J', '?' => 'k', '?' => 'K', '?' => 'l', '?' => 'L', '?' => 'l', '?' => 'L', '?' => 'l', '?' => 'L', '?' => 'l', '?' => 'L', '?' => 'm', '?' => 'M', '?' => 'n', '?' => 'N', '?' => 'n', '?' => 'N', 'ñ' => 'n', 'Ñ' => 'N', '?' => 'n', '?' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o', 'Ô' => 'O', '?' => 'o', '?' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', '?' => 'o', '?' => 'O', '?' => 'o', '?' => 'O', 'ö' => 'oe', 'Ö' => 'OE', '?' => 'p', '?' => 'P', '?' => 'r', '?' => 'R', '?' => 'r', '?' => 'R', '?' => 'r', '?' => 'R', '?' => 's', '?' => 'S', '?' => 's', '?' => 'S', 'š' => 's', 'Š' => 'S', '?' => 's', '?' => 'S', '?' => 's', '?' => 'S', '?' => 's', '?' => 'S', 'ß' => 'SS', '?' => 't', '?' => 'T', '?' => 't', '?' => 'T', '?' => 't', '?' => 'T', '?' => 't', '?' => 'T', '?' => 't', '?' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', '?' => 'u', '?' => 'U', 'û' => 'u', 'Û' => 'U', '?' => 'u', '?' => 'U', '?' => 'u', '?' => 'U', '?' => 'u', '?' => 'U', '?' => 'u', '?' => 'U', '?' => 'u', '?' => 'U', '?' => 'u', '?' => 'U', 'ü' => 'ue', 'Ü' => 'UE', '?' => 'w', '?' => 'W', '?' => 'w', '?' => 'W', '?' => 'w', '?' => 'W', '?' => 'w', '?' => 'W', 'ý' => 'y', 'Ý' => 'Y', '?' => 'y', '?' => 'Y', '?' => 'y', '?' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y', '?' => 'z', '?' => 'Z', 'ž' => 'z', 'Ž' => 'Z', '?' => 'z', '?' => 'Z', 'þ' => 'th', 'Þ' => 'Th', 'µ' => 'u', '?' => 'a', '?' => 'a', '?' => 'b', '?' => 'b', '?' => 'v', '?' => 'v', '?' => 'g', '?' => 'g', '?' => 'd', '?' => 'd', '?' => 'e', '?' => 'E', '?' => 'e', '?' => 'E', '?' => 'zh', '?' => 'zh', '?' => 'z', '?' => 'z', '?' => 'i', '?' => 'i', '?' => 'j', '?' => 'j', '?' => 'k', '?' => 'k', '?' => 'l', '?' => 'l', '?' => 'm', '?' => 'm', '?' => 'n', '?' => 'n', '?' => 'o', '?' => 'o', '?' => 'p', '?' => 'p', '?' => 'r', '?' => 'r', '?' => 's', '?' => 's', '?' => 't', '?' => 't', '?' => 'u', '?' => 'u', '?' => 'f', '?' => 'f', '?' => 'h', '?' => 'h', '?' => 'c', '?' => 'c', '?' => 'ch', '?' => 'ch', '?' => 'sh', '?' => 'sh', '?' => 'sch', '?' => 'sch', '?' => '', '?' => '', '?' => 'y', '?' => 'y', '?' => '', '?' => '', '?' => 'e', '?' => 'e', '?' => 'ju', '?' => 'ju', '?' => 'ja', '?' => 'ja');
    return str_replace(array_keys($transliterationTable), array_values($transliterationTable), $txt);
}


function sanitize_file_name($string, $anal = false) {
    $string = specialChar_to_english_letters($string);
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")",/* "_",*/ "=", "+", "[", "{", "]",
        "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
        "â€”", "â€“", ",", "<", /*".",*/ ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean;

    return strtolower($clean);
}

function sanitize_slug($text, $lowercase = true){
    if(empty($text)) return "";
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);// replace non letter or digits by -
    $text = trim($text, '-'); // trim
    $text = specialChar_to_english_letters($text);// transliterate
    if ($lowercase) {
        $text = strtolower($text);  // lowercase
    }
    $text = preg_replace('~[^-\w]+~', '', $text);// remove unwanted characters
    return $text;
}

?>