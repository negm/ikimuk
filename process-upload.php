<?php
//include the S3 class
if (!isset($_FILES['uploadfileSubmit']['name']) || !isset($_FILES['uploadfileSubmit']['tmp_name']))
{
 header("Location: index.php");
}
require_once 'class/settings.php';
$settings = new settings();
if (!class_exists('S3'))require_once('S3.php');
//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', $settings->awsAccessKey);
if (!defined('awsSecretKey')) define('awsSecretKey', $settings->awsSecretKey);
$bucketname = $settings->submissionBucketName;
//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);
//check whether a form was submitted
//retreive post variables
$fileName = $_FILES['uploadfileSubmit']['name'];
$s3fileName = time().'-'.rand(0,99).'-'.str_replace(' ', '', $fileName);
$fileTempName = $_FILES['uploadfileSubmit']['tmp_name'];
//create a new bucket
//$s3->putBucket("large-pics", S3::ACL_PUBLIC_READ);
//move the file
if ($s3->putObjectFile($fileTempName, $bucketname, $s3fileName, S3::ACL_PUBLIC_READ)) {
echo "https://s3.amazonaws.com/".$bucketname."/".$s3fileName;
}else{
echo "error";
}
?>