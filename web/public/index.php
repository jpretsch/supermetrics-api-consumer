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

//main controller
switch ($datapoint) {
    case "charlength":
        switch ($datafunction) {
            case "avg":
                $result = $post->getAverageCharLength();
            break;
            case "longest":
                $result = $post->getLongestCharLength();
            break;
            default:
                Helpers\Helpers::outputErrorMessage(INVALID_REQUEST_ERROR, 404);
        }
    case "posts":
        switch ($datafunction) {
            case "total":
                $result = $post->getTotalPosts();
            break;
            default:
                Helpers\Helpers::outputErrorMessage(INVALID_REQUEST_ERROR, 404);
 
        }
    case "postsperuser":
        $result = $post->getAveragePerUser();
    break;
    default:
    Helpers\Helpers::outputErrorMessage(INVALID_REQUEST_ERROR, 404);
}

Helpers\Helpers::outputJson($result);

//some debuging code @todo remove this
function op($data, $die=true){
    print "<br><br>******************<br><br>";
    print "<pre>";
    var_export($data);
    print "</pre>";
    if($die){ die('bye'); }
}