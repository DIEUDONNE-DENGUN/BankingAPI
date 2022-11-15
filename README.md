### Project Objective
    To build an internal API for a fake financial institution using PHP and Laravel.
    Laravel is accessible, powerful, and provides tools required for large, robust applications.

### Project Overview
    While modern banks have evolved to serve a plethora of functions, at their core, banks must provide certain basic features. 
    Today, your task is to build the basic REST API for one of those banks! Imagine you are designing a backend API for bank employees. 
    It could ultimately be consumed by multiple frontends (web, iOS, Android etc).
### Getting Started
    The Banking API is a restful based application (backend) running as a RESTFUL API service to be consumed by any client apps
    The application design makes lots of use of domain driven design in structure and leverage lots of dependency injection and the program to an interafce design principle.
    Communication on protected RESTFUL resources ia achieved using an authorization bearer security key exchange

    These instructions will get you a copy of the project up and running on your local machine 
    for development and testing purposes.
    See deployment section for notes on how to deploy the project on an apache  system. 
### Installation Dependencies
    * PHP >=7.3
    * Mysql server
    * Composer
    * Postman
    * Apache or Nginx Server
### Core Business Components

* Customer Bank Account Component (Account Domain)
* Customer 
* Web Application Client Interface
### Server Requirements  
      * PHP >= 7.3
      * BCMath PHP Extension
      * Ctype PHP Extension
      * Fileinfo PHP Extension
      * JSON PHP Extension
      * Mbstring PHP Extension
      * OpenSSL PHP Extension
      * PDO PHP Extension
      * Tokenizer PHP Extension
      * XML PHP Extension

### Set Up and Deployments

    * Clone the project repository: git clone https://github.com/DIEUDONNE-DENGUN/TransactionTracker.git 
    * CD into the project directory (banking-api) with command line or terminal
    * Run on the project directory from the terminal: "composer install" to install dependencies
    * From the project root, rename .env.example to .env and set following values:
    * DB_DATABASE, DB_USERNAME and DB_PASSWORD
    *Navigate to your Database interface (Phpmyadmin) and create an empty database with name as stated on the DB_DATABASE value
    * From the root project directory on terminal, run: "php artisan migrate" to generate database tables
    * Lastly, moved the entire project directory to your web server htdocs or www dir to serve the application
### Built With
    Laravel ^8.75
    Mysql Database
    Laravel Sanctum for authentication and authorization

### API Documentation
A Postman API collection and documentation is published here : ***[BankingAPI Postman Doc](https://documenter.getpostman.com/view/11921397/2s8YmHxQxh)***
### Last Thought
    Thank you for taking the time in testing the project. Would appreciate feedback

### Author

     Dieudonne Takougang
