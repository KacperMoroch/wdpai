# Today I Learned (TIL)

A collaborative "Today I Learned" (TIL) platform for programmers to document and share small, everyday coding lessons. This project is built with **PHP** and **JavaScript**, and is containerized using **Docker** for easy setup and development.

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Technologies](#technologies)
- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Introduction

"Today I Learned" is a simple platform where developers can share brief lessons, tips, or snippets of code they learned throughout the day. The idea is to encourage daily learning and collaboration across the developer community.

## Features

- Post daily tips and code snippets.
- Organize posts by language or topic (e.g., PHP, JavaScript, Docker, etc.).
- Search and filter lessons.
- Simple and minimalistic design.
- API support for future extensions.

## Technologies

- **PHP**: Backend logic and API development.
- **JavaScript**: Frontend functionality and interactivity.
- **Docker**: Containerized development environment.
- **HTML/CSS**: Frontend design and layout.
- **MySQL**: Database for storing TIL posts.

## Installation

To run this project locally using Docker, follow these steps:

### Prerequisites

Make sure you have the following installed on your system:
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Steps

1. **Clone the repository:**

    ```bash
    git clone https://github.com/your-username/til-project.git
    cd til-project
    ```

2. **Create `.env` file:**

    Copy the `.env.example` file to `.env` and adjust any necessary environment variables (such as database credentials).

    ```bash
    cp .env.example .env
    ```

3. **Build and run the Docker containers:**

    ```bash
    docker-compose up --build
    ```

4. **Install PHP dependencies (inside the container):**

    After the containers are up, run the following command to install PHP dependencies (Composer must be installed):

    ```bash
    docker exec -it php-container-name composer install
    ```

5. **Install JavaScript dependencies:**

    If the project has a frontend that uses npm, install the dependencies:

    ```bash
    docker exec -it js-container-name npm install
    ```

6. **Run database migrations (optional):**

    If the project requires database migrations:

    ```bash
    docker exec -it php-container-name php artisan migrate
    ```

7. **Access the application:**

    Visit `http://localhost:8000` in your browser to see the application running.

## Usage

Once the platform is running, you can:

1. **Create a new TIL entry**: Add a title, description, and category (e.g., PHP, JS, etc.).
2. **Search for entries**: Use the search bar to find specific topics or lessons.
3. **Filter by language**: Easily filter lessons by language or technology.

## Contributing

We welcome contributions! If you'd like to contribute, please fork the repository and submit a pull request.

1. Fork the project.
2. Create your feature branch (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a pull request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
