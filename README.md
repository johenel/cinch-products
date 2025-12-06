Enter product service 

``` 
    docker exec -it cinch-product-1 bash
```

Create and configure .env file

```
    cp .env.example .env
```

Update the following .env variables

``` 
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=db_product
DB_USERNAME=root
DB_PASSWORD=root
```

Proceed with the following scripts in order

``` 
    composer install
    php artisan key:generate
    php artisan migrate
    php artisan db:seed
    php artisan test
```


