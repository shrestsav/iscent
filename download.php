<?php
include_once("global.php");
global $webClass;
global $dbF;
global $_e;

$fileId = isset($_GET['file']) ? base64_decode($_GET['file']) : NULL;
if ($fileId === NULL) {
    exit();
}

$sql = "SELECT * FROM tenders WHERE publish = '1' AND id= ? ";
$data = $dbF->getRow($sql, array($fileId) );
if($dbF->rowCount>0) {
    //check if same person download same file again...
    if(!isset($_SESSION['webUser']['downloadFile_'.$fileId])) {
        $sql = "UPDATE tenders SET tender_view = tender_view+1 WHERE id= ? ";
        $dbF->setRow($sql, array($fileId) );
        $_SESSION['webUser']['downloadFile_'.$fileId] = $fileId;
    }
    if($dbF->rowCount>0) {
        $downloadLink    =  $functions->addWebUrlInLink(translateFromSerialize($data['tender_download']));
            //if our link check is file exists
            if($functions->isFileExists($downloadLink,true)){
                header("Location: {$downloadLink}");
                exit();
            }else{
                die('Not found!');
            }

    }else{
        die('Not found !');
    }

}

//Download file End

?>