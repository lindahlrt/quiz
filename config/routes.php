<?php
class Route
{
    public $action;

    public function __construct()
    {
        $this->action = $_GET['action'] ?? 'default';
    }

    public function route()
    {
        // Charger le controller des erreurs pour le cas default
        require_once RACINE . "/app/controllers/errorController.php";

        switch ($this->action) {

            // ── Accueil ─────────────────────────────────
            case 'default':
                require_once RACINE . "/app/controllers/homeController.php";
                showHome();
                break;

            // ── Auth ─────────────────────────────────────
            case 'loginPage':
                require_once RACINE . "/app/controllers/authController.php";
                showAuthPage('login');
                break;

            case 'registerPage':
                require_once RACINE . "/app/controllers/authController.php";
                showAuthPage('register');
                break;

            case 'login':
                require_once RACINE . "/app/controllers/authController.php";
                login();
                break;

            case 'register':
                require_once RACINE . "/app/controllers/authController.php";
                register();
                break;

            case 'logout':
                require_once RACINE . "/app/controllers/authController.php";
                logout();
                break;

            // ── Jeu ──────────────────────────────────────
            case 'game':
                require_once RACINE . "/app/controllers/gameController.php";
                showGame();
                break;

            case 'answer':
                require_once RACINE . "/app/controllers/gameController.php";
                submitAnswer();
                break;

            case 'endGame':
                require_once RACINE . "/app/controllers/gameController.php";
                endGame();
                break;

            case 'useJoker':
                require_once RACINE . "/app/controllers/gameController.php";
                useJoker();
                break;

            // ── Entraînement ──────────────────────────────
            case 'training':
                require_once RACINE . "/app/controllers/trainingController.php";
                showTraining();
                break;

            case 'trainingAnswer':
                require_once RACINE . "/app/controllers/trainingController.php";
                submitTrainingAnswer();
                break;

            case 'useTrainingJoker':
                require_once RACINE . "/app/controllers/trainingController.php";
                useTrainingJoker();
                break;

            // ── Classement ────────────────────────────────
            case 'ranking':
                require_once RACINE . "/app/controllers/rankingController.php";
                showRanking();
                break;

            // ── Profil ────────────────────────────────────
            case 'showProfile':
                require_once RACINE . "/app/controllers/profileController.php";
                showProfile();
                break;

            case 'editProfile':
                require_once RACINE . "/app/controllers/profileController.php";
                showEditProfile();
                break;

            case 'updatePseudo':
                require_once RACINE . "/app/controllers/profileController.php";
                submitUpdatePseudo();
                break;

            case 'updateEmail':
                require_once RACINE . "/app/controllers/profileController.php";
                submitUpdateEmail();
                break;

            case 'updatePassword':
                require_once RACINE . "/app/controllers/profileController.php";
                submitUpdatePassword();
                break;

            case 'deleteAccount':
                require_once RACINE . "/app/controllers/profileController.php";
                submitDeleteAccount();
                break;

            // ── Admin ─────────────────────────────────────
            case 'admin':
                require_once RACINE . "/app/controllers/adminController.php";
                showAdmin();
                break;

            case 'adminUsers':
                require_once RACINE . "/app/controllers/adminController.php";
                showAdminUsers();
                break;

            case 'deleteUser':
                require_once RACINE . "/app/controllers/adminController.php";
                submitDeleteUser();
                break;

            case 'toggleAdmin':
                require_once RACINE . "/app/controllers/adminController.php";
                submitToggleAdmin();
                break;

            case 'addQuestion':
                require_once RACINE . "/app/controllers/adminController.php";
                submitAddQuestion();
                break;

            case 'editQuestion':
                require_once RACINE . "/app/controllers/adminController.php";
                submitEditQuestion();
                break;

            case 'deleteQuestion':
                require_once RACINE . "/app/controllers/adminController.php";
                submitDeleteQuestion();
                break;

            // ── Pages statiques ───────────────────────────
            case 'legal':
                require_once RACINE . "/app/controllers/legalController.php";
                showLegal();
                break;

            default:
                show404();
                break;
        }
    }
}
