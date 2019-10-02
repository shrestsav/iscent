<?php
################ Import CSV into database ###############

require_once("../global.php");
global $_e;

$_d = ","; //$delimiter

$show_form = true;
###### When file submit
if (isset($_POST['csvImport'])) {
    global $dbF;
    if ($_FILES["csv"]["size"] > 0) {
        ###### check uploaded file type
        if ( ($_FILES["csv"]["type"] == "text/csv") ||
             ($_FILES["csv"]["type"] == "application/csv") ||
             ($_FILES["csv"]["type"] == "application/vnd.ms-excel")) {

            ###### Delete Previous All stock... ######
            $sql = "DELETE FROM product_inventory";
            $dbF->setRow($sql);

            ###### get the csv file
            $file = $_FILES["csv"]["tmp_name"];
            $handle = fopen($file, "r");
            $row = 0; // count find no of rows
            $val = fgetcsv($handle, 1000, "{$_d}"); // First Heading column Skip
            while ( ($val = fgetcsv($handle, 1000, "{$_d}") ) !== FALSE) {
                ###### Get csv value by columns no
                $qty_item   = $val[3];
                $location   = $val[4];
                $qty_pk     = $val[5];
                $qty_store_id = $val[6];
                $qty_product_id = $val[7];
                $qty_product_scale = $val[8];
                $qty_product_color = $val[9];
                $product_store_hash = $val[10];

                ###### Insert Into DB
                $sql = "INSERT INTO product_inventory
                      (qty_pk,qty_store_id,qty_product_id,qty_product_scale,qty_product_color,qty_item,location,product_store_hash) VALUES
                      (?,?,?,?,?,?,?,?)";
                $dbF->setRow($sql, array($qty_pk, $qty_store_id, $qty_product_id, $qty_product_scale, $qty_product_color, $qty_item, $location, $product_store_hash));
                $row++;
            }
            fclose($handle);

            echo "<div class='alert alert-success'>File Import SuccessFully : " . "" . $_FILES["csv"]["name"] . "</div>";
            $show_form = false;
        } else {
            echo "<div class='alert alert-danger'>File Not Supported : " . "" . $_FILES["csv"]["name"] . "</div>";
        }
    }//if end csv size
}//if end on submit


###### Get CSV File HTML Form
if ($show_form) {
    ?>
    <div class="container-fluid">
        <form action="-<?php echo $functions->getLinkFolder(); ?>?page=csv#import" class="form-horizontal" method="post"
              enctype="multipart/form-data">

            <div class="form-group">
                <label><?php echo $_e['Stock Inventory Exported File']; ?> :</label>
                <input type="file" name="csv" required/>
            </div>

            <input type="submit" name="csvImport" value="<?php echo $_e['Submit']; ?>" class="btn btn-primary">
        </form>
    </div>
    <?php
}
?>