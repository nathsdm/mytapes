# Project Name

This project was created by Nathan SOARES DE MELO and is built using PHP and Composer.

## Architecture

The project follows a [MVC (Model-View-Controller)](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) architecture, with the following components:

- **Model**: The model layer contains the business logic and data access code. It is responsible for interacting with the database and providing data to the controller layer.
- **View**: The view layer contains the presentation logic and is responsible for rendering the HTML output to the user.
- **Controller**: The controller layer contains the application logic and is responsible for handling user requests, interacting with the model layer, and rendering the appropriate view.

## Installation

To install the project, follow these steps:

1. Clone the repository to your local machine.
2. Run `composer install` to install the project dependencies.
3. Configure the database connection in the `config/database.php` file.
4. Run the database migrations using the `php artisan migrate` command.
5. Start the development server using the `php artisan serve` command.

## Usage

To use the project, follow these steps:

1. Open your web browser and navigate to the development server URL.
2. Use the application to perform the desired actions.