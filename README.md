# Stock price aggregator


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.


### Development Setup

A step by step guide to getting a development environment running

 1. Clone repository
 2. Change to project root directory
 3. Copy **.env.example** to **.env**
 4. Change **ALPHA_VANTAGE_API_KEY** to your api key in  **.env**
 5. Build and run docker containers: `docker compose up -d`
 6. Create Database schema: `docker exec -it php_cont php artisan migrate`
 7. Seed data to database (Predefined stock symbols will be added): `docker exec -it php_cont php artisan db:seed`
 
### Synchronization process

There is a laravel console command that should pull the data from Alpha Vantage to Database:

`docker exec -it php_cont php artisan sync:stock-series`
    
Only diff should be added to database, no updates will occur.
To automate the synchronization process this command needs to be added to your crontab. 
For example if it should be triggered every minute add the following line to your crontab:

`* * * * * docker exec -it php_cont php artisan sync:stock-series`

### Database
MySQL 8.0 is used as Database. There are only two tables in database:
1. stock_symbols  (Keeps the list of predefined symbols, e.g. IBM, Amazon)
2. stock_time_series  (Includes interval price data)

### Caching
Redis is responsible for storing the caches. Invalidation functionality has been implemented at the stage of synchronization.  

## API Usage

There are three endpoints that returns stock data:
   1. GET http://localhost/api/stock/symbol/list
   2. GET http://localhost/api/stock/latest-price/{symbol}  (symbol can be taken from the first request, for example: **IBM**). 
   If no symbol is provided then the response will include all symbols
   3. GET http://localhost/api/stock/price-changes/{symbol}  (symbol can be taken from the first request, for example: **IBM**).
      If no symbol is provided then the response will include all symbols

All these routes use Basic authentication. There is one predefined user with the following credentials:
    `Username: demo`
    `Password: Stock123Market`
To make request working just include the header to request:
    `Authorization: Basic ZGVtbzpTdG9jazEyM01hcmtldA==`

## UI Usage

Starting point to access the web site is http://localhost/login. Fill demo credentials:
`Username: demo`
`Password: Stock123Market`
Then you will be redirected to dashboard with the list of stock prices and their changes. 

### Tests
Tests are located in tests folder. To run the tests execute the command:

   `docker exec -it php_cont vendor/bin/phpunit`

The tests rely on the data in database. Run the tests only after the synchronization process is done.
