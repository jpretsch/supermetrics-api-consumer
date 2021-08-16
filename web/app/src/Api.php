<?php

namespace App\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;

/**
 * Api class
 * Handles interaction with the supermetrics api
 * @package supermetrics-api-consumer
 * @author John Pretsch john.pretsch@gmail.com
 */
class Api
{
    protected $token = null;
    protected $tokenSetTime = null;
    /**
     * Constructor, registers the token
     */
    public function __construct() {
        //since we don't have any storage mechanism set up, just get a new token
        $this->token = $this->registerToken();
        $this->tokenSetTime = time(); //not yet used
        //@todo compare current time to tokenSetTime and if < 1 hour
        //use one that is in a data store
    }
     /**
      * simple getter for the token (not currently in use)
      * @return String token
      */
    public function getToken(){
        return $this->token;
    }

    /**
     * Registers the token
     * @return string token
     */
    protected function registerToken()
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => BASE_URL,
            ['http_errors' => false]
        ]);
        try {
            $response = $client->request('POST', BASE_PATH, [
                'json' => [
                    'client_id' => CLIENT_INFO['client_id'],
                    'email' => CLIENT_INFO['email'],
                    'name'  => CLIENT_INFO['name']
                ]
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse(); 
            
        } catch(ServerException $e) {
            $response = $e->getResponse(); 
        }
                       
        $body = $response->getBody();
        $arr_body = json_decode($body);
        $token = $arr_body->data->sl_token;
        return $token;
    }
    /**
     * Returns all the posts
     * @return array posts 
     */
    public function getAllPosts()
    {
        $page = 1;
        $max_pages = 10;
        $all_posts = [];
        while(true){
            $result = $this->getPosts($page);
            $all_posts = array_merge($all_posts, $result['data']['posts']);
            $page ++;
            if($page > $max_pages){ //api behavior note: pages greater than 10 return page 10
                break;
            }

        }
        return $all_posts;
    }

    /**
     * get a page of posts
     * @param int page
     * @return array posts
     */
    public function getPosts($page = 1) {
        
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.supermetrics.com'
        ]);
        try {
            $response = $client->request('GET', '/assignment/posts', [
                'query' => [
                    'sl_token' => $this->token,
                    'page' => $page
                ]
            ]);
        } catch (ClientException $e) {
            
            $response = $e->getResponse(); 
            
            
        } catch(ServerException $e) {
            $response = $e->getResponse(); 
        }
        $body = $response->getBody();
        $posts = json_decode($body, true);
        return $posts;
    }
}