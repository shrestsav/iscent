<?php
global $_e;
if($_GET['pageT']=='new'){
///////////////////////////////////////////////////////////   Add Email PAGE
  ?>
    <div class="container-fluid">
      <div class="container-fluid">
      <form action="-email?page=email&pageT=submit#importEmail" class="form-horizontal" method="post" enctype="multipart/form-data">

        <div class="form-group">
          <label for="exampleInputFile"><?php echo $_e['Excel File']; ?> :</label>
          <input type="file" name="file" />
          <p class="help-block"><a href="<?php echo WEB_URL; ?>/uploads/files/emailImport.xls" target="_blank"><?php echo $_e['Example']; ?></a>. Create Excel File With 3 columns, 1 for Name, 2nd for Email, 3rd For group, Upload Excel File and submit</p>
        </div>


        <input type="submit" name="emailImport" value="<?php echo $_e['Submit']; ?>" class="btn btn-primary">

      </form>
    </div>
    </div>
<?php
  //exit;
}
else {
  global $db;
  global $dbF;
  require_once 'Excel/reader.php';


// ExcelFile($filename, $encoding);
  $data = new Spreadsheet_Excel_Reader();


///;/***********************///////////////******  FILE UPLOADER ****************///////////////////////////********************///////

//var_dump($_FILES);
  if (($_FILES["file"]["size"]) > 0) {
    $replaced = str_replace(' ', '_', $_FILES["file"]["name"]);
    $img = $_FILES["file"]["name"];

    if ($_FILES["file"]["error"] > 0) {
      echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    } else {

      if (file_exists("" . $_FILES["file"]["name"])) {

      } else {
        move_uploaded_file($_FILES["file"]["tmp_name"],
            "" . $_FILES["file"]["name"]);
        echo "<div class='alert alert-success'> File Import SuccessFully : " . "" . $_FILES["file"]["name"] ."</div>";
      }
    }
  } else {
    echo "Invalid file";
  }


  if (($_FILES["file"]["size"]) > 0) {
    $new = $img;
    $num = rand(10, 6000000);
    $newimg1 = $num;
    $newimg1 .= $replaced;
    rename($new, $newimg1);
  }

///;/***********************///////////////******  FILE UPLOADER ****************///////////////////////////********************///////


  $data->setOutputEncoding('CP1251');

  $data->read($newimg1);
  $check = 0;
  //error_reporting(E_ALL ^ E_NOTICE);

  $done =0;
  for ( $i = 1; $i <= $data->sheets[0]['numRows']; $i++ ) {
    //echo "<br>";

    $user   = isset($data->sheets[0]['cells'][$i][1]) ? $data->sheets[0]['cells'][$i][1] : "";
    if(strtolower($user) == 'name'){
      continue;
    }
    $email  = isset($data->sheets[0]['cells'][$i][2]) ? $data->sheets[0]['cells'][$i][2] : "";
    if(empty($email) || strtolower($email) == 'email'){
      continue;
    }

    $grp  = isset($data->sheets[0]['cells'][$i][3]) ? $data->sheets[0]['cells'][$i][3] : "excel";

    /*for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {

      if ($check == 0) {
        $email = $data->sheets[0]['cells'][$i][$j];
        $check++;
      } else{
        $user = $data->sheets[0]['cells'][$i][$j];
        $check = 0;
      }
    }*/

    //var_dump($data->sheets[0]['cells'][$i]);
    //var_dump($data->sheets[0]['numCols']);
     $col1 = $user;
     $col2 = $email;
     $col3 = $grp;
    ?>



    <?php

    if ( trim($col1) != "" && trim($col1) != "" ) {
      //check if email not Exists...
      $sql = "SELECT * FROM email_subscribe WHERE email = '$col2'";
      $dbF->getRow($sql);
      if($dbF->rowCount>0){
        continue;
      }else {
        $sql = "INSERT INTO  email_subscribe (
                `name` ,
                `email`,
                `verify`,
                `grp`
                )
                VALUES (?,?,1,?)";

        $dbF->setRow($sql,array($col1,$col2,$col3));
        $done++;
      }
    }




    ?>
  <?php

  }
  echo "<br><div class='alert alert-success'> $done Emails Import SuccessFully</div>";
  unlink($newimg1);

}
?>
