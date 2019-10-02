<?php
//trait common_functions2.php

//don't encrypt this file
//Functions that have  path specify has here,,

//function with out class call in admin or web
function html2rgb($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
            $color[2].$color[3],
            $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0],
            $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}

function distancel2(array $color1, array $color2) {
    return sqrt(pow($color1[0] - $color2[0], 2) +
        pow($color1[1] - $color2[1], 2) +
        pow($color1[2] - $color2[2], 2));
}

function HexToColorName($value){
    $colors = array(
        "black"     => array(0, 0, 0),
        "green"     => array(0, 128, 0),
        "silver"    => array(192, 192, 192),
        "lime"      => array(0, 255, 0),
        "gray"      => array(128, 0, 128),
        "olive"     => array(128, 128, 0),
        "white"     => array(255, 255, 255),
        "yellow"    => array(255, 255, 0),
        "maroon"    => array(128, 0, 0),
        "navy"      => array(0, 0, 128),
        "red"       => array(255, 0, 0),
        "blue"      => array(0, 0, 255),
        "purple"    => array(128, 0, 128),
        "teal"      => array(0, 128, 128),
        "fuchsia"   => array(255, 0, 255),
        "aqua"      => array(0, 255, 255),
    );
    $distances = array();
    $val = html2rgb($value);
    if($val===false || isset($colors[$value])){ //when value is color name
        return $value;
    }
    foreach ($colors as $name => $c) {
        $distances[$name] = distancel2($c, $val);
    }

    $mincolor = "";
    $minval = pow(2, 30); /*big value*/
    foreach ($distances as $k => $v) {
        if ($v < $minval) {
            $minval = $v;
            $mincolor = $k;
        }
    }

    return $mincolor;
}

function data_compress($string,$base64=true){
    //If you wnat return string length in 500 letters,
    //normal english letter maximum limit is > 10,00,000
    //with Special letters maximum limit is <> 40,000

    //use gzcompress function if gzencode is not support, or gzinflate()
    //$compressed = (gzcompress($string));  alternative of gzencode();
    $compressed = (gzencode($string));
    if($base64) $compressed = base64_encode($compressed);
    //it is Good, because string comes in normal alpahbet
    $length1= strlen($compressed);
    if($length1 > 495){
        $compressed2 = "2=> ".gzencode($compressed);
        if($base64) $compressed2 = base64_encode($compressed2);

        if(strlen($compressed2) < $length1){
            $compressed = $compressed2;
        }
    }
    return $compressed;
}

function data_unCompress($string,$base64=true){
    //it is Good, because string comes in normal alpahbet
    $unCompress = $string;
    if($base64) $unCompress = base64_decode($unCompress);

    //$compressed = (gzuncompress($string)); alternative of gzdecode();
    if(preg_match('/^2=> /',$unCompress)) {
        $unCompress  = preg_replace('/^2=> /','',$unCompress);
        $unCompress = (gzdecode($unCompress));
        if($base64) $unCompress = base64_decode($unCompress);
    }
    $unCompress = (gzdecode($unCompress));
    return $unCompress;
}


//Functions End
trait common_function2
{

    private $uploadFiles = array(
        /*Image Type*/  "jpg","jpeg","bmp","gif","png","img",
        /*Files Type*/  "txt","pdf","psd","docx","doc","pptx","ppt","xlsx","xlr","xls","csv","pps",
                         "zip","gzip","rar","gz","tar","tar.gz","ios","max","dwg","eps","ai","torrent",
                         "html","css","js","xml","xhtml","rss",
        /*Media Type*/  "mp4","m4a","mp3","mpg3","3gp","flv","wmv","wav","mqv","mpeg4","swf","mov","mpg","avi","raw","wmv","rm","obj"
    ); // only these type file allow to upload, write in lower case

    /**
     * @param $image
     */
    public function deleteOldSingleImage($image){
        if($image==''){return false;}
        @unlink(__DIR__."/../../images/$image");
    }

    public function check_file_allow($filename){
        //check file extension, is allow to upload or not,
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!empty($ext) && in_array(strtolower($ext),$this->uploadFiles)) {
            return true;
        }
        return false;
    }

    public function check_document_file_ext($filename){
        //check file extension, is allow to upload or not,
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!empty($ext) && in_array(strtolower($ext),$this->document_type_extensions)) {
            return true;
        }
        return false;
    }

    /**
     * @param $data $_FILES['name']
     * @param string $folder
     * @param string $imgName
     * @return bool|string ImgName
     *
     * e.g:   $image = $this->functions->uploadSingleImage($_FILES['image'],'deal');
     */
    public function uploadSingleImage($data,$folder='mixed',$imgName='',$fileType='', $path = ''){

        if($this->check_file_allow($data["name"])==false) return false;

        # if path is empty string then use default path inside images folder
        if ( $path == '' ) {
            $path = __DIR__."/../../images/";
        } else {
            $path = __DIR__.$path;
        }

        if(substr($folder,-1,1)=='/'){}else{
            $folder = $folder.'/';
        }

        $path = $path.$folder;

        $year = date('Y').'/';
        $month = date('m').'/';
        $rand = rand(101,999);

        //create folder pages
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        //create folder year
        if (!file_exists($path.$year)) {
            mkdir($path.$year, 0777);
        }

        //create folder month
        if (!file_exists($path.$year.$month)) {
            mkdir($path.$year.$month, 0777);
        }

        if(($data["size"])>0)
        {

            #### Allowing document file type checking
            $document_file = false;
            if ( $fileType == 'document' ) {
                $document_file = $this->check_document_file_ext($data["name"]);
            }

            if (($data["type"] == "image/png") || ($data["type"] == "image/jpeg") ||
                ($data["type"] == "image/gif") || ($data["type"] == "image/jpg") || ($data["type"] == $fileType) || ('all' == $fileType) || $document_file == TRUE )
            {

                // # document file checking when used via uploadSingleFile function
                // if ( in_array(needle, haystack) ) {
                //     # code...
                // }


                if ($data["error"] > 0){
                    return false;
                }
                else {
                    $realName = $data["name"];
                    if($imgName!=''){
                        $imgName = $rand.'-'.$imgName.'-'.$realName;
                    }else{
                        $imgName = $rand.'-'.$realName;
                    }
                    $imgName = sanitize_file_name($imgName);
/*                    $imgName = str_replace(' ', '-', $imgName);
                    $imgName = str_replace('/', '-', $imgName);
                    $imgName = str_replace("'", '', $imgName);
                    $imgName = str_replace('"', '', $imgName);*/

                    $imgName    = $year.$month.$imgName;
                    $return     = $folder.$imgName;

                    if(move_uploaded_file($data["tmp_name"],$path.$imgName)){
                        return $return;
                    }
                }
            }
            else{
                return false;
            }
        }
        return  false;
    }

    public function uploadSingleFile($data,$folder='files',$fileName='',$fileType='document', $path = ''){
        // var_dump($data);
        //$fileType = 'application/pdf';
        // var_dump($this->check_file_allow($data["name"]));
        if($this->check_file_allow($data["name"])==false) return false;
        return $this->uploadSingleImage($data,$folder,$fileName,$fileType,$path);
    }

    public function uploadMultipleFiles($data,$folder='files',$fileName='',$fileType='all'){
        //$fileType = 'application/pdf';
        if($this->check_file_allow($data["name"])==false) return false;
        return $this->uploadMultiImages($data,$folder,$fileName,$fileType);
    }

    /**
     * @param $data $_FILES['name']
     * @param string $folder where want to save image
     * @param $imgName
     * @return array|string
     * also work in single image.. but return images array not name...
     * e.g: $functions->uploadMultiImages($_FILES['image'],'defect','');
     *
     * only work for single input image tag, but multi select images option
     */
    public function uploadMultiImages($data,$folder='mixed',$imgName='',$fileType=''){
        $path = __DIR__."/../../images/";
        if(substr($folder,-1,1)=='/'){}else{
            $folder = $folder.'/';
        }

        $path = $path.$folder;

        $year = date('Y').'/';
        $month = date('m').'/';
        $rand = rand(101,999);

        //create folder pages
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        //create folder year
        if (!file_exists($path.$year)) {
            mkdir($path.$year, 0777);
        }

        //create folder month
        if (!file_exists($path.$year.$month)) {
            mkdir($path.$year.$month, 0777);
        }


        $imagesReturnName = '';

        $dataTemp   = $data;
        $i = 0;
        foreach($dataTemp["size"] as $key=>$val){

            if(($data["size"][$key])>0)
            {
                if (($data["type"][$key] == "image/png") || ($data["type"][$key] == "image/jpeg") ||
                    ($data["type"][$key] == "image/gif") || ($data["type"][$key] == "image/jpg") || ($data["type"] == $fileType) || ('all' == $fileType))
                {
                    if ($data["error"][$key] > 0){
                        continue;
                    }
                    else {
                        $i++;
                        $realName = $data["name"][$key];

                        if($this->check_file_allow($realName)==false) continue;

                        if($imgName!=''){
                            $imgNameN = $rand.'-'.$i."-".$imgName.'-'.$realName;
                        }else{
                            $imgNameN = $rand.'-'.$i."-".$realName;
                        }
                        $imgNameN = sanitize_file_name($imgNameN);
                        /*$imgNameN = str_replace(' ', '-', $imgNameN);
                        $imgNameN = str_replace('/', '-', $imgNameN);
                        $imgNameN = str_replace("'", '', $imgNameN);
                        $imgNameN = str_replace('"', '', $imgNameN);*/

                        $imgNameN = $year.$month.$imgNameN;
                        $return  = $folder.$imgNameN;
                        if(move_uploaded_file($data["tmp_name"][$key],$path.$imgNameN)){
                            $imagesReturnName[] =   $return;
                        }
                    }
                }
                else{
                    continue;
                }
            }

        }
        return  $imagesReturnName;
    }

    /**
     * @param $folder
     * @return array|bool
     *
     * get All folder name from admin, use for check files MD5
     */
    private function getFolderFiles($folder){
        $adminFile  =   __DIR__.'/../../'.ADMIN_FOLDER.'/'.$folder;
        if(is_dir($adminFile)){
            $files = array_diff(scandir($adminFile), array('..', '.'));
            return $files;
        }
        return false;
    }

    /**
     * @param $filePathNotStartWithSlash
     * @param bool $required
     * @param bool $fileContent
     *
     * Best method to include file, its need path from main folder,,
     */
    public function getWebFile($filePathNotStartWithSlash,$required=false,$fileContent=false){
        //from root
        $webFile  =   __DIR__.'/../../'.$filePathNotStartWithSlash;
        if($fileContent){
            return file_get_contents($webFile);
        }

        if($required){
            require_once($webFile);
        }else{
            include_once($webFile);
        }
    }

    /**
     * @param $page
     * @param bool $fileContent
     * @return mixed|string
     *
     * Best method to include file from _models/webPages / file content, only file name,,
     */
    public function getPage($page,$fileContent=false){
        $webFile  =   __DIR__.'/../webPages/'.$page;
        if($fileContent){
            return file_get_contents($webFile);
        }

        return include_once($webFile);
    }

    public function getAdminFile($filePathNotStartWithSlash,$required=false,$fileContent=false){
        $adminFile  =   __DIR__.'/../../'.ADMIN_FOLDER.'/'.$filePathNotStartWithSlash;
        if($fileContent){
            return file_get_contents($adminFile);
        }

        if($required){
            require_once($adminFile);
        }else{
            include_once($adminFile);
        }
    }


    /**
     * @param $fileName
     * @return objectOfClass
     * This functions is design for get object, no need to call function required with file name,,,
     * use this functions if want to change path change from here, All work will ok,,,
     * but this functions need to update in all files where files are including.
     *
     */
    public function require_once_custom($fileName){
        $adminFile  =   __DIR__.'/../../'.ADMIN_FOLDER.'/';
        $webFile  =   __DIR__.'/../../';
        $model = $webFile."_models/";

        Switch($fileName){
            case 'product_functions':
                return require_once($adminFile.'product_management/functions/product_function.php');
                break;

            case 'setting.class.php':
                return require_once($adminFile.'setting/classes/setting.class.php');
                break;
            case 'orderInvoice':
                return require_once($adminFile.'order/classes/invoice.php');
                break;
            case 'webUsers.class':
                return require_once($adminFile.'webUsers/classes/webUsers.class.php');
                break;
            case 'store':
                return require_once($adminFile.'stock/classes/store.php');
                break;
            case 'shipping':
                return require_once($adminFile.'shipping/classes/shipping.php');
                break;
            case 'AdminMenuClass':
                return require_once($adminFile.'classes/menu.php');
                break;

            case 'Class.myKlarna.php':
                return require_once($adminFile.'order/classes/klarna.php');
                break;
            case 'globalVar':
                return require_once($adminFile.'globalVar.php');
                break;

            //WebSite
            case 'webProduct_functions':
                return require_once($webFile.'_models/functions/webProduct_functions.php');
                break;

            case 'webBlog_functions':
                return require_once($webFile.'_models/functions/webBlog_functions.php');
                break;

            case 'webFooter':
                return file_get_contents($webFile.'footer.php');
                break;
            default:
                return false;
                break;

        }
    }

    public function modelFunFile($fileName){
        $webFile  =   __DIR__.'/../functions/';
        include_once($webFile.$fileName);
    }

    public function modelClasFile($fileName){
        $webFile  =   __DIR__.'/../classes/';
        include_once($webFile.$fileName);
    }

    public function includeOnceCustom($fileNameWithPath){
        //path from start e.g: _models/functions/ or admin/setting/classes/class.php
        $webFile  =   __DIR__.'/../../';
        include_once($webFile.$fileNameWithPath);
    }

    /**
     * @param $fileNameWithPath
     * Admin Path From inside Admin Folder Name
     */
    public function includeAdminFile($fileNameWithPath){
        //path from admin folder name e.g: setting/classes/class.php OR product/index.php
        $adminFile  =   __DIR__.'/../../'.ADMIN_FOLDER."/";
        include_once($adminFile.$fileNameWithPath);
    }

    public function _modelFile($fileNameWithPath){
        $webFile  =   __DIR__.'/../'.$fileNameWithPath;
        include_once($webFile);
    }

    public function isImageExists($image,$HasWebUrl=false){
        if($HasWebUrl==true){
            $image = str_replace(WEB_URL,"",$image);
        }
        if(file_exists(__DIR__."/../../images/".$image) && $image != '' && $image !== false){
            return true;
        }else{
            return false;
        }
    }


    public function isFileExists($file,$HasWebUrl=false){
        if($HasWebUrl==true){
            $file = str_replace(WEB_URL,"",$file);
        }
        if(file_exists(__DIR__."/../../".$file) && $file != '' && $file !== false){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Start and stop cron job from here...
     */
    public function cronJob(){
            $sql    =   "SELECT id FROM `email_letter_queue` WHERE status = '1'";
            $data   =   $this->dbF->getRows($sql);
            //if email exists for send

            $cron_file  = __DIR__."/../../cron/crontab.txt";

            if($this->dbF->rowCount>0){
                //Create cron job
                $file   =   CRON_FILE;
                $this->add_cron_task($file);
            }else{
                //remove CronJob
                $output     = shell_exec('crontab -l');

                /*remove single crone*/
                 $file   =   CRON_FILE;
                 $remove_cron = str_replace($file."\n", "", $output);
                 $remove_cron = str_replace("\n\n", "", $remove_cron);
                 file_put_contents($cron_file, $remove_cron.PHP_EOL);

                 exec('crontab '.$cron_file);

                //remove all crone
                /*exec("crontab -r");
                file_put_contents($cron_file, 'SHELL="/usr/local/cpanel/bin/jailshell"'); //blank file..
                exec('crontab '.$cron_file);*/
            }
        }

    private function add_cron_task($file){
        $cron_file  = __DIR__."/../../cron/crontab.txt";
        $output = shell_exec('crontab -l');
        file_put_contents($cron_file, $output.$file.PHP_EOL);
        exec('crontab '.$cron_file);
    }

    public function customPdf($contentHtml,$footer=true){
        $_POST['pdfContent']    = $contentHtml;
        $_POST['pdfFooter']     = $footer;
        include(__DIR__."/../../src/pdf/pdf.php");
    }

} // trait end

?>