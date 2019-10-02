<?php
//Normal
try {
    $phar = new PharData('filename.zip');
    $phar->extractTo('./', null, true); // extract all files, and overwrite
} catch (Exception $e) {
    // handle errors
}

//unzip emethod special for One.com hostings
/*
$unzip = new ZipArchive;
$out = $unzip->open('file-name.zip');

if ($out === TRUE) {
    $unzip->extractTo(getcwd());
    $unzip->close();
    echo 'File unzipped';
} else {
    echo 'Something went wrong?';
}
*/
//unzip emethod special for One.com hostings end



//copy file from other server....
/*  $file = 'http://p.imediahostings.com/led/js.tar.gz';
    $saveFileWithName = "js.tar.gz";
    $newfile = $_SERVER['DOCUMENT_ROOT'] . '/' . $saveFileWithName;

    if (copy($file, $newfile)) {
        echo "Copy success!";
    } else {
        echo "Copy failed.";
    }
*/


?>