# Laravel Vue Order Project Setup

To set up a Laravel Vue Order project, follow these steps:

1. Clone the project repository.
2. Install dependencies using `composer install` and `npm install`.
3. Create a copy of the `.env.example` file and name it `.env`.
4. Generate an application key using `php artisan key:generate`.
5. Configure the database connection in the `.env` file.
6. Migrate the database using `php artisan migrate`.
7. Compile assets using `npm run dev` for development or `npm run production` for production.
8. Start the Laravel development server using `php artisan serve`.
9. Run the queue worker using `php artisan queue:work`.
10. Set up scheduled tasks using `php artisan schedule:work`.

## Running Queue and Schedule Commands

To run the queue and schedule commands, use the following commands:

- To run the queue worker:
  ```
  php artisan queue:work
  ```

- To run the scheduler for scheduled tasks:
  ```
  php artisan schedule:work
  ```

## Adding Mail Credentials to .env

To add mail credentials to the `.env` file, include the following configuration:

