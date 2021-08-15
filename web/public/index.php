<?php
/*$token = 'smslt_d11c75a3d6bc9_deaeedce6472';
$url = "https://api.supermetrics.com/assignment/posts";
$url .= "?sl_token=$token&page=1";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
var_dump($resp);

die('bye');*/

use App\Api;
use App\Post;
use App\Helpers;

ini_set('display_errors', 'on');
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(0);

include '../app/vendor/autoload.php';
require_once('../app/src/Api.php');
require_once('../app/src/Post.php');
require_once('../app/config.php');

$request = new Api\Api();
$post = new Post\Post();
$request_data = parse_url($_SERVER['REQUEST_URI']);
#var_export($_REQUEST);

//let's start by sanitizing our inputs
$datapoint = filter_var($_REQUEST['datapoint'], FILTER_SANITIZE_STRING);
$datafunction = filter_var($_REQUEST['datafunction'], FILTER_SANITIZE_STRING);
$period = filter_var($_REQUEST['period'], FILTER_SANITIZE_STRING);

#if(Helpers\checkInput($datapoint, $datafunction, $period) === false){
#    die("error invalid input"); //@todo give a more specific message
#}//otherwise continue on

$request->registerToken();

switch($datapoint)
{
    case "charlength":
        switch($datafunction)
        {
            case "avg":
                $result = $post->getAverageCharLength();
            break;
            case "longest":
                $result = $post->getLongestCharLength();
            break;  
        }
    case "posts":
        switch($datafunction)
        {
            case "total":
                $result = $post->getTotalPosts();
            break;
 
        }

}

echo json_encode($result);

function op($data, $die=true){
    print "<br><br>******************<br><br>";
    print "<pre>";
    var_export($data);
    print "</pre>";
    if($die){ die('bye'); }
}