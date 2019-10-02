<?php
ob_start();

//require_once("classes/setting.class.php");
global $dbF;

$functions->require_once_custom('setting.class.php');
$setting    =  new setting();


echo '<h4 class="sub_heading borderIfNotabs">'. _uc($_e['IBMS History']) .'</h4>';

$functions->dataTableDateRange();

echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>'. _uc($_e['SNO']) .'</th>
                        <th>'. _uc($_e['TITLE']) .'</th>
                        <th>'. _uc($_e['REFERENCE']) .'</th>
                        <th>'. _uc($_e['DATE TIME']) .'</th>
                        <th>'. _uc($_e['USER']) .'</th>
                        <th>'. _uc($_e['DESCRIPTION']) .'</th>
                        <th>'. _uc($_e['IP']) .'</th>
                        <th>'. _uc($_e['BROWSER']) .'</th>

                    </thead>
                <tbody>';
$sql  = "SELECT * FROM activity_log ORDER by log_id DESC ";
$data =  $dbF->getRows($sql);
$i = 0;
foreach($data as $val){
    $i++;
    echo "       <tr>
                    <td>$i</td>
                    <td>$val[log_title]</td>
                    <td>$val[ref_name]</td>
                    <td>$val[log_time]</td>
                    <td>$val[ref_user]</td>
                    <td>$val[log_desc]</td>
                    <td>$val[log_ip]</td>
                    <td>$val[log_browser]</td>

                  </tr>";
}


echo '</tbody>
      </table>
     </div> <!-- .table-responsive End -->';

$deleteDay = $functions->ibms_setting('historyDeleteAfterDays');
$days = date('Y-m-d',strtotime("-"."$deleteDay days"));
$sql = "DELETE FROM activity_log WHERE log_time < '$days' ";
$dbF->setRow($sql);
?>


    <script>
        $(document).ready(function(){
            tableHoverClasses();
            dateJqueryUi();
            minMaxDate();
            dTableRangeSearch();
        });
    </script>


<?php return ob_get_clean(); ?>