<?php

namespace App\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

class Api
{
    protected $token = '';
    protected $tokenSetTime = null;

    public function getName()
    {
        return 'woo woo woo';
    }

    public function getToken(){
        return $this->token;
    }

    public function registerToken()
    {
        return 'smslt_5bfafdd25bee_4b9b1fd9d4705';
 
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
         
        //get status code using $response->getStatusCode();
         
        $body = $response->getBody();
        $arr_body = json_decode($body);
        $token = $arr_body->data->sl_token;
        print "token = $token";
        $this->token = $token;
    }

    public function getAllPosts()
    {
        $page = 1;
        $max_pages = 11;
        $all_posts = [];
        $return = [];
        while(true){
            $result = $this->getPosts($this->token, $page);
            $all_posts = array_merge($all_posts, $result['data']['posts']);
            $page ++;
            if($page > 10){ //api behavior note: pages greater than 10 return page 10
                break;
            }

        }
        #die('73');
        #op($all_posts);
        return $all_posts;
    }

    public function getPosts($token = null, $page = 1){
        
        #die("token  = $token");
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.supermetrics.com'
        ]);
        try {
            #die("token = $token");
            $response = $client->request('GET', '/assignment/posts', [
                'query' => [
                    'sl_token' => $token,
                    'page' => $page
                ]
            ]);
        } catch (ClientException $e) {
            
            $response = $e->getResponse(); 
            
            
        }# catch(ServerException $e) {
        #    $response = $e->getResponse(); 
        #}
         
        //get status code using $response->getStatusCode();
         
        $body = $response->getBody();
        $arr_body = json_decode($body, true);
        
        return $arr_body;
        
    }
}