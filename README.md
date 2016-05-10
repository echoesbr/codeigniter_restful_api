# CodeIgniter Rest Server

A fully RESTful server implementation for CodeIgniter.
This project connects on Oracle (calling procedures) and MongoDB databases to retrieve data.

It also has two useful libraries, one for validating tokens in an external OAuth server and one for making HTTP requests.

## Requirements

1. PHP 5.4 or greater
2. CodeIgniter 3.0+

_Note: for 1.7.x support download v2.2 from Downloads tab_

## Config

Config files must be changed! 

    - application/config/config.php:
    Base URL of the project $config['base_url'].

    - application/config/constants.php:
    Database and OAuth server endpoints.

## TODO

Develop the model for MongoDB databases.


