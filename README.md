This repo is built by Zaid for Julo Test

----------

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/9.x/deployment#server-requirements)
And make sure that your machine has [Docker](https://www.docker.com/products/docker-desktop) and [Composer](https://getcomposer.org/download/) installed.

Clone the repository

    git clone git@github.com:zaidysf/julo-wallet.git

Switch to the repo folder

    cd julo-wallet

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Build and run julo-wallet container

    ./vendor/bin/sail up -d

Generate a new JWT authentication secret key

    ./vendor/bin/sail artisan jwt:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    ./vendor/bin/sail artisan migrate

You can now access the API server at [http://localhost](http://localhost).
You can find all of available endpoints in [julo-wallet.postman_collection](https://github.com/zaidysf/julo-wallet/blob/main/julo-wallet.postman_collection.json)

**TL;DR command list**

    git clone git@github.com:zaidysf/julo-wallet.git
    cd julo-wallet
    composer install
    cp .env.example .env
    ./vendor/bin/sail up -d
    ./vendor/bin/sail artisan jwt:generate
    ./vendor/bin/sail artisan migrate
 
# Authentication
 
This applications uses JSON Web Token (JWT) to handle authentication. The token is passed with each request using the `Authorization` header with `Token` scheme. The JWT authentication middleware handles the validation and authentication of the token. Please check the following sources to learn more about JWT.
 
- https://jwt.io/introduction/
- https://self-issued.info/docs/draft-ietf-oauth-json-web-token.html
