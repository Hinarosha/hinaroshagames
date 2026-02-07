<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hinarosha's Games</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-dark">
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom">
            <div class="container">
                <a class="navbar-brand" href="index.php">Hinarosha's Games</a>
                <div class="ms-auto">
                    <button v-if="!isLoggedIn" @click="showLoginModal" class="btn btn-outline-light me-2">Sign In</button>
                    <button v-if="!isLoggedIn" @click="showRegisterModal" class="btn btn-primary">Sign Up</button>
                    <button v-else @click="logout" class="btn btn-outline-light">Logout</button>
                </div>
            </div>
        </nav>

        <div class="container my-5">
            <div class="row g-4">
                <div class="col-md-6" v-for="game in games" :key="game.id">
                    <div class="card bg-dark h-100">
                        <div class="position-relative game-tile" 
                             @click="playGame(game.id)" 
                             style="cursor: pointer; transition: transform 0.2s;"
                             onmouseover="this.style.transform='scale(1.02)'" 
                             onmouseout="this.style.transform='scale(1)'">
                            <img :src="game.image" class="card-img-top" :alt="game.name" 
                                 style="filter: blur(3px); height: 300px; object-fit: cover;">
                            <div class="position-absolute top-0 start-0 w-100 h-100" 
                                 style="background: rgba(0,0,0,0.4);"></div>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <h2 class="text-white fw-bold" 
                                    style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8), 
                                           -2px -2px 4px rgba(0,0,0,0.8), 
                                           2px -2px 4px rgba(0,0,0,0.8), 
                                           -2px 2px 4px rgba(0,0,0,0.8);">
                                    {{ game.name }}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <div class="card bg-dark">
                        <div class="card-header border-bottom border-light">
                            <h3 class="text-light mb-0">Recent Completions</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>Player</th>
                                            <th>Game</th>
                                            <th>Ending</th>
                                            <th>Time</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        require_once 'db_connexion.php';
                                        $query = $db->query("
                                            SELECT username, game_name, chosen_ending, 
                                                   completion_time, completion_date
                                            FROM gameplay_records
                                            ORDER BY completion_date DESC
                                            LIMIT 10
                                        ");
                                        
                                        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
                                        
                                        while($record = $query->fetch()) {
                                            $minutes = floor($record['completion_time'] / 60);
                                            $seconds = $record['completion_time'] % 60;
                                            $time = sprintf("%02d:%02d", $minutes, $seconds);
                                            
                                            // Format français : jour/mois/année heure:minute
                                            $date = date('d/m/Y H:i', strtotime($record['completion_date']));
                                            
                                            echo "<tr>
                                                <td>{$record['username']}</td>
                                                <td>{$record['game_name']}</td>
                                                <td>{$record['chosen_ending']}</td>
                                                <td>{$time}</td>
                                                <td>{$date}</td>
                                            </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Container - Moved to top-center -->
        <div class="toast-container position-fixed top-50 start-50 translate-middle" style="z-index: 1500;">
            <div class="toast align-items-center border-0" 
                 :class="{'bg-success': notification.type === 'success', 'bg-danger': notification.type === 'error'}"
                 role="alert" 
                 aria-live="assertive" 
                 aria-atomic="true"
                 ref="toast">
                <div class="d-flex">
                    <div class="toast-body text-white fs-5 px-4 py-3">
                        {{ notification.message }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-3 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>

        <!-- Login Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title">Sign In</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="login">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" v-model="loginForm.email" class="form-control bg-dark text-light">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" v-model="loginForm.password" class="form-control bg-dark text-light">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Sign In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Register Modal -->
        <div class="modal fade" id="registerModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title">Sign Up</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="register">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" v-model="registerForm.username" class="form-control bg-dark text-light">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" v-model="registerForm.email" class="form-control bg-dark text-light">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" v-model="registerForm.password" class="form-control bg-dark text-light">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-dark text-light mt-5 border-top">
            <div class="bg-darker py-3">
                <div class="container text-center text-muted">
                    <small>&copy; 2025 Hinarosha's Games. All rights reserved.</small>
                </div>
            </div>
        </footer>
    </div>

    <script src="assets/js/vue.js"></script>
</body>
</html>