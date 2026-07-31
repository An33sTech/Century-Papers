<?php

require_once (__DIR__."/../../global.php");

echo $id = $_POST['id'];
echo $comment = $_POST['comment'];

$sql    =  "UPDATE `surveyFormData` SET
            `comment`=?
            WHERE id = '$id' ";
$array   = array($comment);
$dbF->setRow($sql,$array,false);

// $id = $_POST['id'];
// $sql  = "SELECT * FROM formAllData where id = '$id' ";
// $variable =  $dbF->getRow($sql);

// $comment = $variable['comment'];
echo $comment;

if($_POST['comm'] == 'delete')
{
        $id = $_POST['id'];
        $sql2="DELETE FROM surveyFormData WHERE id= ?";
       $dbF->setRow($sql2,array($id));
}
 ?>

