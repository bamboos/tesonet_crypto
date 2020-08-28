## Load on local machine
- Checkout the project.
- Make sure docker (and `docker-compose`) is installed on local machine.
- Go to the project's 'docker' dir and run `docker-compose up -d`. It should pull images and start containers.
- After that, run `docker exec -it tesonet_web php artisan db:seed --class=UserSeeder` to have some users preloaded into the database.
- Use some HTTP client (Postman) to test API requests.
