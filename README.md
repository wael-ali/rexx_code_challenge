# Data Base:
* tables:
    * user (name, email)
    * participation (fee)
    * event (date, name)
* relations:
    * user 1 --------> m participation
    * participation 1 --------> 1 user

    * event 1 --------> m participation
    * participation 1 --------> 1 event


# Requirements
* PHP >=7.2.5
* MySql >= 5.7 
* composer

## installation
* go the project directory
* install dependencies ``` composer install ```
* set the database in the .env file
* create the database ``` bin/console doctrine:database:create ```
* create the database ``` bin/console doctrine:migrations:migrate ```
* run app on php server ``` php -S 127.0.0.1:8080 -t public ```

* you can see the home page on localhost:8080
* upload file and data base will be filled with data (there is no validation of the json file structure)

