<?php

$curent_version_code = 1;
$app_version_code = $_REQUEST['versioncode'];

$download_app_title = "Hi Sahabat Muslim";
$download_app_message = "update available, please download it";
$download_app_url = "http://zaitunlabs.com/yourls/cmssahabatmuslim";


if($curent_version_code != $app_version_code){
    $response = array(	"status"=>2,
    					"title"=>$download_app_title,
    					"message"=>$download_app_message,
    					"detail"=>$download_app_url);
}else{
    $response = array("status"=>1,
    					"title"=>"",
    					"message"=>"no update available",
    					"detail"=>"");
}

echo json_encode($response);

?>