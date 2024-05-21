# Laravel Task API

This project is a Laravel-based API for managing tasks. It provides endpoints for creating, reading, updating, and deleting tasks. The API also includes user authentication.

## Prerequisites

- PHP >= 8.1
- Composer
- Sail
- Laravel >= 10.x
- MySQL >= 8.x

## Installation

1. Clone the repository:

    ```sh
    git clone git@github.com:Troyan911/task-manager.git
    cd task-manager
    ```

2. Install dependencies:

    ```sh
    composer install
    ```

3. Copy `.env.example` to `.env` and configure your environment variables, especially the database settings:

    ```sh
    cp .env.example .env
    ```

4. Generate an application key:

    ```sh
    php artisan key:generate
    ```

5. Start the development server:

    ```sh
    ./vendor/bin/sail up -d
    ```
6. Run the database migrations and seed the database:

    ```sh
    sail artisan migrate --seed
    ```

## API Endpoints

### Authentication

- **Login**

    ```http
    POST /api/auth
    ```

  Request Body:

    ```json
    {
        "email": "user@example.com",
        "password": "password"
    }
    ```

  Responses:

    - `200 OK` - Successful login with JWT token
    - `401 Unauthorized` - Invalid credentials

### Tasks

- **Get All Tasks**

    ```http
    GET /api/tasks
    ```
  
    For filtering data by field add query parameter `field=value`:

    ```http
    GET /api/tasks?priority=1
    ```
    For sorting data by field add query parameter `sort1=field1` and direction parameter `dir1=desc` (asc by default). 
    There is an opportunity to sort data by two fields

    ```http
    GET /api/tasks?sort1=priority&dir1=desc&sort2=title&dir2=asc
    ```

  Responses:

    - `200 OK` - List of tasks

- **Get Task by ID**

    ```http
    GET /api/tasks/{id}
    ```

  Responses:

    - `200 OK` - Task details
    - `404 Not Found` - Task not found

- **Create Task**

    ```http
    POST /api/tasks
    ```

  Request Body:

    ```json
    {
        "title": "Task Title",
        "description": "Task Description"
    }
    ```

  Responses:

    - `201 Created` - Task created successfully
    - `422 Unprocessable Entity` - Validation errors

- **Update Task**

    ```http
    PUT /api/tasks/{id}
    ```

  Request Body:

    ```json
    {
        "title": "Updated Task Title",
        "description": "Updated Task Description"
    }
    ```

  Responses:

    - `200 OK` - Task updated successfully
    - `404 Not Found` - Task not found
    - `422 Unprocessable Entity` - Validation errors

- **Delete Task**

    ```http
    DELETE /api/tasks/{id}
    ```

  Responses:

    - `204 No Content` - Task deleted successfully
    - `404 Not Found` - Task not found

