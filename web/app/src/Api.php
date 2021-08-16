<?php

namespace App\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;

class Api
{
    protected $token = null;
    protected $tokenSetTime = null;

    public function __construct() {
        //since we don't have any storage mechanism set up, just get a new token
        $this->token = $this->registerToken();
        $this->tokenSetTime = time(); //not yet used
        //@todo compare current time to tokenSetTime and if < 1 hour
        //use one that is in a data store
    }

    public function getToken(){
        return $this->token;
    }

    protected function registerToken()
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.supermetrics.com',
            ['http_errors' => false]
        ]);
        try {
            $response = $client->request('POST', '/assignment/register', [
                'json' => [
                    'client_id' => 'ju16a6m81mhid5ue1z3v2g0uh',
                    'email' => 'john.pretsch@gmail.com',
                    'name'  => 'Jonny Stomperton'
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

    public function getAllPosts()
    {
        $page = 1;
        $max_pages = 11;
        $all_posts = [];
        $return = [];
        while(true){
            $result = $this->getPosts($page);
            $all_posts = array_merge($all_posts, $result['data']['posts']);
            $page ++;
            if($page > 10){ //api behavior note: pages greater than 10 return page 10
                break;
            }

        }
        return $all_posts;
    }

    public function getPosts(int $page = 1) {
        
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
        $arr_body = json_decode($body, true);
        return $arr_body;
    }
}