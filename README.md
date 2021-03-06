# Test app for Supermetrics api

## Install prerequisites

To run the docker commands without using **sudo** you must add the **docker** group to **your-user**:

```
sudo usermod -aG docker your-user
```

All requisites should be available for your distribution. The most important are :

* [Git](https://git-scm.com/downloads)
* [Docker](https://docs.docker.com/engine/installation/)
* [Docker Compose](https://docs.docker.com/compose/install/)

## Installation

Keeping this nice and simple

git clone https://github.com/jpretsch/supermetrics-api-consumer.git    
docker-compose up -d

To shut it down:
docker-compose down

## Usage 

Output can be accessed through a browser using localhost.

* Browser:
    * Average character length of posts per month  
    http://localhost/?datapoint=charlength&datafunction=avg&period=m
    
    * Longest post by character length per month  
    http://localhost/?datapoint=charlength&datafunction=longest&period=m

    * Total posts split by week number   
    http://localhost/?datapoint=posts&datafunction=total&period=W

    * Average number of posts per user per month  
    http://localhost/?datapoint=postsperuser&period=m

    note: the period parameter is included for future extensibility. It is not currently needed and defaults to the appropriate value.

    * Other query strings will produce a 404 error

## Design consideratiosn

* web/app/src/Api.php  This is the source file for the Api class which does all the interaction with the api. An external library, Guzzle is used here for api calls. Its a little cleaner and easier than accessing curl directly. 

* web/app/src/Post.php This class handles crunching the data for the various requests. Methods which return something per time period use php datetime format string for extensibility.

* web/app/src/user.php Does nothing presently. Included for future dev.

* web/app.config.php defines constants

* web/public/index.php the public facing interface for the app which also acts as the controller


## Possible future improvements

* add unit tests
* use Traits for data structures
* store the token and use it when still valid saving api hits

## Images to use

* [Nginx](https://hub.docker.com/_/nginx/)
* [PHP-FPM](https://hub.docker.com/r/nanoninja/php-fpm/)
* [Composer](https://hub.docker.com/_/composer/)



