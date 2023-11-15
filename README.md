# Project Name

This project was created by Nathan SOARES DE MELO and is built using PHP, Symfony, Composer and Twig. It aims to provide a simple web application for managing music tapes. Each member should be able to create an account, add inventories, and add tapes to their inventories.

## Architecture

The project follows a [MVC (Model-View-Controller)](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) architecture, with the following components:

- **Model**: The model layer contains the business logic and data access code. It is responsible for interacting with the database and providing data to the controller layer.
- **View**: The view layer contains the presentation logic and is responsible for rendering the HTML output to the user.
- **Controller**: The controller layer contains the application logic and is responsible for handling user requests, interacting with the model layer, and rendering the appropriate view.

## Entities

The project has five entities: Tape, Inventory, Gallery, Member and User. Here's a brief overview of each entity:

- Tape: Represents a music tape. Each tape has a name and belongs to an inventory.
- Inventory: Represents a collection of tapes owned by a member. Each inventory has a name and belongs to a member.
- Gallery: Represents a collection of tapes. Each gallery has a name and contains one or more tapes. A gallery can be public or private.
- Member: Represents a member who owns one or more inventories. Each member has a name.
- User: Represents a user who can log in to the application. Each user has a username, password, and is linked to a member.

## Installation

To install and run the project, follow these steps:

1. Clone the repository to your local machine using the following command:
```bash
git clone https://github.com/nathsdm/mytapes.git
```
2. Run the following command to install the dependencies:
```bash
composer install
```
3. Run the following command to create the database:
```bash
symfony console doctrine:database:create
```
4. Run the following command to create the database schema:
```bash
symfony console doctrine:schema:create
```
5. Run the following command to load the fixtures:
```bash
symfony console doctrine:fixtures:load
```
6. Run the following command to start the development server:
```bash
symfony server:start
```

Now you are ready to use the application !

## Usage

To use the project, follow these steps:

1. Open your web browser and navigate to the development server URL (e.g. http://localhost:8000/home).
2. Use the application to navigate through the inventories and tapes.

## Tests

With the data fixtures, you can test the application with the following users:

Seb:
- **Username**: seb@localhost
- **Password**: seb
- **Role**: ROLE_USER

Nathan:
- **Username**: nathan@localhost
- **Password**: nathan
- **Role**: ROLE_ADMIN

Each user has several tapes and galleries:

- Seb has 3 tapes (2 public and 1 private) and 1 gallery.
- Nathan has 4 tapes (public) and 2 galleries.

Test the application with these users to see the different features. For example, you can try to log in with Seb and Nathan to see the difference between a user and an admin. The role admin has access to the CRUD of the entities and to a dashboard (http://localhost:8000/admin). The role user has access to the profiles of the users and to his own profile (http://localhost:8000/profile). These links are protected. If you try to access them without being logged in, you will be redirected to the login page.
Concerning the entity that should have a complete front end CRUD, it's in my case the entity gallery. It is more interesting to have a CRUD on the gallery entity because it is the entity that contains the tapes and that the user can share with others. Moreover, he can create multiple galleries to organize his tapes.