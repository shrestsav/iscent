<?php
//integrate with models,, on ajax call axis from here,, to save model secrets


include_once(__DIR__ . "/../global.php");
//Encrypt From here

global $webClass;
global $dbF;
$WEB_URL = WEB_URL;
function productAjaxCallEndOfThisPage()
{
    if (isset($_GET['page'])) {
        $page = $_GET['page'];

        $ajax = new ajax_call();
        switch ($page) {
            case 'loadCustomFormInfo':
                //when custom form show for measurement then info for every single field showm when user click on info icon...
                $ajax->loadCustomFormInfo();
                break;
        }
    }
}

class ajax_call extends object_class
{
    public $productClass;
    public $webClass;

    public function __construct()
    {
        parent::__construct('3');

        $this->functions->require_once_custom('webProduct_functions');
        $this->productClass = new webProduct_functions();
        $this->webClass = $GLOBALS['webClass'];

        $_w['Gift Card Id is Not Valid. Note: It is Case Sensitive.'] = '';
        $_e = $this->dbF->hardWordsMulti($_w,currentWebLanguage(),'Web Rating');

    }

    public function loadCustomFormInfo(){
        $this->functions->getPage("viewOrder.php");
        $viewOrder = new viewOrder();
        echo $viewOrder->getCustomSingleFieldInfo($_GET['id']);
    }

}

productAjaxCallEndOfThisPage();