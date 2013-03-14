<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
echo $_SERVER['HTTP_HOST'];
/*
$logger = new payment_log();
$logger->response_code = "M";
$logger->entire_url = http_build_query($_GET);
$x = $logger->log_request();
$x = array_search($logger->response_code, $logger->codes_for_email);
echo $x;
$result = null;
switch ($_GET["test"]) {
        case "0" : $result = "Payment is successful.";
            break;
        case "?" : if ($_GET["test2"] == "shit")$result = "Payment is unsuccessful. We will contact you shortly to resolve this issue.";
                    else $result = "shit";
            break;
}
echo $result;*/
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/localisation.php");
$x = _txt("test");
echo $x[0];
?>

