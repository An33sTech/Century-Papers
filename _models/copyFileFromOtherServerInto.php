<?php 
    $file = 'http://p.imediahostings.com/led/js.tar.gz';
    $saveFileWithName = "js.tar.gz";


    $newfile = $_SERVER['DOCUMENT_ROOT'] . '/' . $saveFileWithName;

    if (copy($file, $newfile)) {
        echo "Copy success!";
    } else {
        echo "Copy failed.";
    }

?>

<!--<form action="" method="post">
    <input type="text" name="file"/>
    <input type="submit" value="copy"/>
</form>-->