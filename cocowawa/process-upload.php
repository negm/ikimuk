<?php
//include the S3 class
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
if (!isset($_FILES['uploadfile']['name']) || !isset($_FILES['uploadfile']['tmp_name']))
{
 header("Location: index.php");
}
require_once $_SERVER["DOCUMENT_ROOT"].'/class/settings.php';
$settings = new settings();
if (!class_exists('S3'))require_once($_SERVER["DOCUMENT_ROOT"].'/inc/S3.php');
//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', $settings->awsAccessKey);
if (!defined('awsSecretKey')) define('awsSecretKey', $settings->awsSecretKey);
$bucketname = $settings->imageBucketName;
//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);
//check whether a form was submitted
//retreive post variables
$fileName = $_FILES['uploadfile']['name'];
$s3fileName = time().'-'.rand(0,99).'-'.str_replace(' ', '', $fileName);
$fileTempName = $_FILES['uploadfile']['tmp_name'];
//move the file
if ($s3->putObjectFile($fileTempName, $bucketname, $s3fileName, S3::ACL_PUBLIC_READ)) {
echo "https://s3.amazonaws.com/".$bucketname."/".$s3fileName;
}else{
echo "error";
}
?>