## Load on local machine
- Checkout the project.
- Copy .env.example to .env
- Make sure docker (and `docker-compose`) is installed on local machine.
- Go to the project's 'docker' dir and run `docker-compose up -d`. It should pull images and start containers.
- Install composer dependencies: `docker exec -it tesonet_web composer install`
- Run migration `docker exec -it tesonet_web php artisan migrate`
- After that, run `docker exec -it tesonet_web php artisan db:seed --class=UserSeeder` to have some users preloaded into the database.
- After that, run `docker exec -it tesonet_web php artisan passport:install` to have some users preloaded into the database.
- Use some HTTP client (Postman) to test API requests (view swagger documentation [http://localhost:8080/api/docs](http://localhost:8080/api/docs))

System uses external prices exchange API from [https://www.cryptonator.com/api/](https://www.cryptonator.com/api/).
