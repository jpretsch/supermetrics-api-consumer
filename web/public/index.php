<?php
/**
 * Public Index file. This functions as a controller for the app.
 * @author John Pretsch john.pretsch@gmail.com
 */

use App\Api;
use App\Post;
use App\Helpers;

ini_set('display_errors', 'on');
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(0);

include '../app/vendor/autoload.php';
require_once('../app/config.php');

$request = new Api\Api();
$post = new Post\Post();

//let's start by sanitizing our inputs
$datapoint = filter_var($_REQUEST['datapoint'], FILTER_SANITIZE_STRING);
$datafunction = filter_var($_REQUEST['datafunction'], FILTER_SANITIZE_STRING);
$period = filter_var($_REQUEST['period'], FILTER_SANITIZE_STRING);

#echo "datapoint = $datapoint";
#echo "datafunction = $datafunction";
//main controller
switch ($datapoint) {
    case "charlength":
        switch ($datafunction) {
            case "avg":
                $title = "Average char length per post per month"; //output titles for clarity
                $result = $post->getAverageCharLength();
                break;
            case "longest":
                $title = "Longest post by character length per month";
                $result = $post->getLongestCharLength();
                break;
            default:
                Helpers\Helpers::outputErrorMessage(INVALID_REQUEST_ERROR, 404);
                break;
        }
        break;
    case "posts":
        switch ($datafunction) {
            case "total":
                $title = "Total posts split by week number";
                $result = $post->getTotalPosts('W'); //W = week number
                break;
            default:
                Helpers\Helpers::outputErrorMessage(INVALID_REQUEST_ERROR, 404);
                break;
 
        }
        break;
    case "postsperuser":
        $result = $post->getAveragePerUser();
        break;
    default:
        Helpers\Helpers::outputErrorMessage(INVALID_REQUEST_ERROR, 404);
        break;
}

Helpers\Helpers::outputJson($result, $title);

//some debuging code @todo remove this
function op($data, $die=true){
    print "<br><br>******************<br><br>";
    print "<pre>";
    var_export($data);
    print "</pre>";
    if($die){ die('bye'); }
}