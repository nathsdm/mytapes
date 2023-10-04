# Project Name

This project was created by Nathan SOARES DE MELO and is built using PHP and Composer.

## Architecture

The project follows a [MVC (Model-View-Controller)](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) architecture, with the following components:

- **Model**: The model layer contains the business logic and data access code. It is responsible for interacting with the database and providing data to the controller layer.
- **View**: The view layer contains the presentation logic and is responsible for rendering the HTML output to the user.
- **Controller**: The controller layer contains the application logic and is responsible for handling user requests, interacting with the model layer, and rendering the appropriate view.

## Entities

The project has three entities: Tape, Inventory, and Member. Here's a brief overview of each entity:

- Tape: Represents a music tape. Each tape has a name and belongs to an inventory.
- Inventory: Represents a collection of tapes owned by a member. Each inventory has a name and belongs to a member.
- Member: Represents a member who owns one or more inventories. Each member has a name.

## Installation

To install and run the project, follow these steps:

1. Clone the repository to your local machine.
2. Run 'composer install' to install the project dependencies.
3. Run 'symfony console doctrine:database:create' to create the database.
4. Run 'symfony console doctrine:schema:create' to create the database schema.
5. Run 'symfony console doctrine:fixtures:load' to load the fixtures.
6. Run 'symfony server:start' to start the development server.

## Usage

To use the project, follow these steps:

1. Open your web browser and navigate to the development server URL.
2. Use the application to navigate through the inventories and tapes.