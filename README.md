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
## Images to use

* [Nginx](https://hub.docker.com/_/nginx/)
* [PHP-FPM](https://hub.docker.com/r/nanoninja/php-fpm/)
* [Composer](https://hub.docker.com/_/composer/)



