# Hinarosha's Games

A web-based game portal featuring a collection of browser games (only one for now), user authentication, and a leaderboard system. Built with PHP, Vue.js, and MySQL.

## Features

- **User Authentication**: Secure sign-up and sign-in system.
- **Game Library**: Access to multiple browser-based games.
  - **RPG**: A role-playing game adventure. (Not available yet)
  - **Escape Game**: A puzzle-solving escape room experience.
- **Leaderboard**: Real-time display of recent game completions, including player name, game, ending achieved, and completion time.
- **Responsive Design**: Modern, dark-themed UI built with Bootstrap 5.

## file Structure

```
furoshagames/
├── assets/
│   ├── bdd.exemple.sql    # Database schema
│   └── js/
│       └── vue.js         # Frontend logic (Vue.js app)
├── auth/                  # Authentication scripts
│   ├── check_session.php
│   ├── login.php
│   ├── logout.php
│   └── register.php
├── games/                 # Game files
│   ├── escapegame/
│   └── rpg/
├── db_connexion.php       # Database connection
├── index.php              # Main portal page
└── README.md
```

## Setup & Installation

1.  **Clone the repository** (or extract the project files).
2.  **Configure the Database**:
    - Import `assets/bdd.exemple.sql` into your MySQL server to create the `hinaroshagames` database and necessary tables (`users`, `gameplay_records`).
    - Update `db_connexion.php` with your database credentials if necessary.
3.  **Run the Server**:
    - Build a local PHP server (e.g., using XAMPP, WAMP, or built-in PHP server).
    - If using the built-in PHP server, run:
      ```bash
      php -S localhost:8000
      ```
4.  **Access the Application**:
    - Open your browser and navigate to `http://localhost:8000` (or your configured host).

## Database Schema

The project uses a MySQL database named `hinaroshagames` with the following tables:

- **users**: Stores user credentials.
  - `id`, `username`, `email`, `password_hash`, `created_at`
- **gameplay_records**: Tracks game completion stats.
  - `id`, `user_id`, `username`, `game_name`, `completion_time`, `chosen_ending`, `completion_date`

## Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript, [Vue.js 3](https://vuejs.org/), [Bootstrap 5](https://getbootstrap.com/)
- **Backend**: PHP
- **Database**: MySQL

## Usage

1.  **Register** a new account using the "Sign Up" button.
2.  **Sign In** to access the games.
3.  **Play** a game by clicking on its card.
4.  Upon completing a game, your score and ending will be recorded and displayed in the **Recent Completions** table.
