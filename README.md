# Installing


### Install dependencies:
````
composer install
````

### Copy env example to env:
````
cp .env.example .env 
````

### Configure your database connection:
````
DB_CONNECTION=mysql
DB_HOST=your_host
DB_PORT=your_port
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
````

### Generate application key:
````
php artisan key:generate 
````

### Run migrations and seeds:
````
php artisan migrate --seed 
````

### Run migrations for test database:
````
DB_DATABASE=blog_test php artisan migrate 
````

### Build front-end:
````
yarn && yarn prod 
````

### Run tests and static analysis:
````
composer test  
````
