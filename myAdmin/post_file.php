<?php

// If you want to ignore the uploaded files, 
// set $demo_mode to true;
// http://tutorialzine.com/2011/09/html5-file-upload-jquery-php/
include_once("global_ajax.php");
global $db;
global $dbF;

$demo_mode = false;
$upload_dirMain = '../images/'; // Main folder DIR
$path = 'ajax/';  //change name here if you want to change ajax to asad :P
//Now Go to below switch Case your work is finish

$upload_dir = $upload_dirMain.$path;
//create folder ajax
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777);
}

//create page folder
if(isset($_POST['page'])){
    $page = $_POST['page'];
    $page = $page."/";
    $path = $path.$page;
    $upload_dir = $upload_dirMain.$path;
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777);
    }
    /////
    $year = date('Y').'/';
    $month = date('m').'/';
    $path  = $path.$year;
    $upload_dir = $upload_dirMain.$path;
//create folder year
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777);
    }

    $path  = $path.$month;
    $upload_dir = $upload_dirMain.$path;
//create folder month
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777);
    }
}else{
    exit;
}

//$upload_dir = $upload_dir.$path;

// $allowed_ext = array('jpg','jpeg','png','gif', 'pdf');
$allowed_ext = array('jpg','jpeg','png','gif');


if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
	exit_status('Error! Wrong HTTP method!');
}


if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 ){
	
	$pic = $_FILES['pic'];

	if(!in_array(get_extension($pic['name']),$allowed_ext)){
		exit_status('Only '.implode(',',$allowed_ext).' files are allowed!');
	}

    if(isset($pic['name'])){
        $pic['name'] = specialChar_to_english_letters2($pic['name']);
    }


	if($demo_mode){
		
		// File uploads are ignored. We only log them.

		$line = implode('		', array( date('r'), $_SERVER['REMOTE_ADDR'], $pic['size'], $pic['name']));
		file_put_contents('log.txt', $line.PHP_EOL, FILE_APPEND);
		
		exit_status('Uploads are ignored in demo mode.');
	}
	
	
	// Move the uploaded file from the temporary 
	// directory to the uploads folder:
    //@$name=$_POST['page']."_".$_POST['item_id']."_".$pic['name'];
    $rand = rand(100,999);
    @$name=$_POST['item_id']."_".$rand.'_'.$pic['name']; //IMG name : INSERT PARENT ID_RANDOM NUMBER_IMG NAME;
    if(!file_exists(__DIR__."/".$upload_dir.$name)){
        if(move_uploaded_file($pic['tmp_name'], $upload_dir.$name)){

            $ajax=new ajax_functions(); //Class obj
            @$page=$_POST['page']; //This is comming from your upload page in hidden input
            $saveName = $path.$name;

            //File name saving in DB
            switch($page){
                case 'product':
                        $ajax->productImage($_POST['item_id'],$saveName);
                    break;
                case 'defect':
                        $ajax->defectImage($_POST['item_id'],$saveName);
                    break;
                case 'album':
                    $ajax->albumImage($_POST['item_id'],$saveName);
                    break;
            }
    }


		exit_status('File was uploaded successfuly!');
	}
	
}

exit_status('Something went wrong with your upload!');


// Helper functions

function exit_status($str){
	echo json_encode(array('status'=>$str));
	exit;
}

function get_extension($file_name){
	$ext = explode('.', $file_name);
	$ext = array_pop($ext);
	return strtolower($ext);
}






?>