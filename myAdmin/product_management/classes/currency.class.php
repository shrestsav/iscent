<?php

trait currency
{

    public function getList()
    {
        $qry = $this->dbF->getRows("SELECT * FROM `currency` ORDER BY `cur_country` ASC ");
        $num_rows = $this->dbF->rowCount;

        if ($num_rows > 0) {
            $ret = "";
            foreach ($qry as $data) {
                $ret [] = $data;
            }
            return $ret;
        } else {
            return false;
        }
    }

    /*
     * Add Country List
     * This function will add data to the
     * country list database. Make sure to
     * supply all required 3 variables.
     */
    public function add_currency_to_list()
    {
        @$country = $this->supply['country'];
        @$currency = $this->supply['currency'];
        @$symbol = $this->supply['symbol'];

        $sql = 'INSERT INTO `currency` (`cur_country`, `cur_name`, `cur_symbol`) VALUES (?,?,?)';
        $arry=array($country, $currency, $symbol);
        $stmt = $this->dbF->setRow($sql,$arry);
        $id = $this->dbF->rowLastId;
        return $id;
    }

}

class currency_management extends functions
{
    private $functions;
    private $productF;
    use currency;

    function __construct()
    {
        parent::__construct();

        if (isset($GLOBALS['functions'])) $this->functions = $GLOBALS['functions'];
        else $this->functions=new admin_functions();

        if (isset($GLOBALS['productF'])) $this->productF = $GLOBALS['productF'];
        else {
            require_once(__DIR__."/../functions/product_function.php");
            $this->productF=new product_function();
        }


        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w=array();
        $_w['Currency Name'] = '' ;
        $_w['Country'] = '' ;
        $_w['Currency Symbol'] = '' ;
        $_w['ACTION'] = '' ;
        $_w['ADD'] = '' ;
        $_w['Currency'] = '' ;
        $_w['Symbol'] = '' ;
        $_w['No data available!'] = '' ;
        $_w['Currency Management'] = '' ;
        $_w['List'] = '' ;
        $_w['Add Currency'] = '' ;
        $_w['Edit Currency Information'] = '' ;
        $_w['Close'] = '' ;
        $_w['Update'] = '' ;
        $_w['Saving...'] = '' ;
        $_e    =   $this->dbF->hardWordsMulti($_w,$adminPanelLanguage,'Admin DashBoard');

    }
    public function ajaxcurrency(){
        $this->productF->AjaxDelScript('currencyAjax_del','currency');
        $this->productF->AjaxUpdateScript('AjaxUpdate_currency','currency');
    }


    /*
     * This take the array name of the form
     */

    public function add_currecny_controler($form_array_prefix = false)
    {
        if (isset($_POST[$form_array_prefix])) {
            $form = $_POST[$form_array_prefix];
            if (
                isset($form['country']) && !empty($form['country'])
                && isset($form['currency']) && !empty($form['currency'])
                && isset($form['symbol']) && !empty($form['symbol'])
            ) {
                $this->supply['country'] = $form['country'];
                $this->supply['currency'] = $form['currency'];
                $this->supply['symbol'] = $form['symbol'];
                $this->add_currency_to_list();
            }
        }
    }



}

?>