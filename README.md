# Expense Management System
- Api Based Expense Management Using Laravel + JWT + Vue
---------------------------------

## Setup Project / Deploy instructions
- git clone https://github.com/jaodavid/expense-manager.git OR extract ZIP file
- cd to expense-manager
- composer install
- npm install
- cp .env.example .env
- php artisan key:generate
- Create an empty database for your project using the database tools you prefer
- In the .env file, add database information to allow Laravel to connect to the database
    - In the .env file fill in the DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD options to match the credentials of the database     you just created. This will allow us to run migrations and seed the database in the next step.
- php artisan migrate
- php artisan db:seed
- php artisan jwt:secret
- php artisan serve
- enjoy :)

Note: I may forgot indicate some steps so feel free to contact me if you have any questions
- I will also try to deploy a demo and refactor some code
----------------------------------

# Author
- Mark Jourine David

