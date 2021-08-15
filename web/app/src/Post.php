<?php

namespace App\Post;

use App\Api;
use DateTime;

Class Post
{
    
    public function __construct(){

    }

    public function getAll($token)
    {
        $api = new Api\Api();
        $result = $api->getAllPosts($token);
    }  
     
    public function getTotalPosts($period = 'W'){
        global $allThePosts; //@todo just for dev
        $totals = []; 
        $posts = $allThePosts;
        foreach($posts as $post){
            $dt = new DateTime($post['created_time']);
            $period_key = date($period, $dt->getTimestamp());
            $totals[$period_key]['count']++;
        }
        ksort($totals);
        return $totals;
    }

    public function getLongestCharLength($period = 'm')
    {
        global $allThePosts; //@todo just for dev
        $posts = $allThePosts;
        $maximums = [];
        foreach($posts as $post){
            $dt = new DateTime($post['created_time']);
            $month = date($period, $dt->getTimestamp());
            $message_length = strlen($post['message']);
            if ($message_length > $maximums[$month]['max']) {
                $maximums[$month]['max'] = $message_length;
                $maximums[$month]['id'] = $post['id'];
            }
        } 
        ksort($maximums);
        return $maximums;
    }
    
    public function getAverageCharLength($period = 'm')
    {
        global $allThePosts; //@todo just for dev
        $averages = []; 
        $posts = $allThePosts;
        foreach($posts as $post){
            $dt = new DateTime($post['created_time']);
            $month = date($period, $dt->getTimestamp());
            $averages[$month]['total'] += strlen($post['message']);
            $averages[$month]['count']++;


        }
        $averages = $this->calculateAverages($averages);
        return $averages;
    }

    protected function calculateAverages($averages){

        $returnval = [];
        ksort($averages);

        foreach($averages as $key => $val){
            if($val['total'] == 0){
                $returnval[$key] = 0;
            } elseif($val['count' == 0]) {
                //musn't divide by zero
                continue;
            } else {
                $returnval[$key] = round($val['total'] / $val['count']);
            }
            
        }
        return $returnval;
    }


}