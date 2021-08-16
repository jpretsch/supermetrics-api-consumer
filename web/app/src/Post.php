<?php

namespace App\Post;

use App\Api;
use DateTime;

Class Post
{
    
    protected $api = null;
    
    /**
     * Constructor, create an api object
    */
    public function __construct(){
        $this->api = new Api\Api();
    }

    /**
     * get the total posts per time period, in this case week
     * @param string period (date format parameter string)
     * @return array totals
     */
    public function getTotalPosts($period = 'W'){
        $totals = []; 
        $posts = $this->api->getAllPosts();
        foreach($posts as $post){
            $dt = new DateTime($post['created_time']);
            $period_key = date($period, $dt->getTimestamp());
            $totals[$period_key]['count']++;
        }
        return $totals;
    }

    /**
     * get the average posts per user per time period, in this case month
     * @param string period (date format parameter string)
     * @return array monthyAverages
     */
    public function getAveragePerUser($period = 'm'){
        $posts = $this->api->getAllPosts();
        $userCounts = []; 
        $periods = [];
        foreach($posts as $post){
       
            $ukey = str_replace('user_', '', $post['from_id']);
            $userCounts[$ukey]['count']++;
            $userCounts[$ukey]['from_id'] = $post['from_id'];
        }
        ksort($userCounts);
        $monthlyAverages = [];
        $range = $this->getRangeOfPosts($posts, $period);
        foreach($userCounts as $key => $val){
            if($range == 0){
                break;
            }
            $avg = round($val['count'] / $range, 2);
            $monthlyAverages[$key]['from_id'] = $val['from_id'];
            $monthlyAverages[$key]['avg'] =  $avg;
        }
        return $monthlyAverages;
    }

    /**
     * get the longest post per each time period (month)
     * @param string period (date format parameter string)
     * @return array maximums
     */
    public function getLongestCharLength($period = 'm')
    {
        $posts = $this->api->getAllPosts();
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
        return $maximums;
    }

    /**
     * get the longest post per each time period (month)
     * @param string period (date format parameter string)
     * @return array maximums
     */
    public function getAverageCharLength($period = 'm')
    {
        $averages = []; 
        $posts = $this->api->getAllPosts();
        foreach($posts as $post){
            $dt = new DateTime($post['created_time']);
            $month = date($period, $dt->getTimestamp());
            $averages[$month]['total'] += strlen($post['message']);
            $averages[$month]['count']++;
        }
        $averages = $this->calculateAverages($averages);
        return $averages;
    }

    protected function getRangeOfPosts($posts, $period = 'm')
    {
        $periods = [];
        foreach ($posts as $post) {
            $dt = new DateTime($post['created_time']);
            $period_val = date($period, $dt->getTimestamp());
            if(in_array($period_val, $periods)){
                continue;
            }
            $periods[] = $period_val;
        }
        sort($periods);
        return (count($periods));
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