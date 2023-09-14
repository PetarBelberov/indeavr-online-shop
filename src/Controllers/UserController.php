<?php


namespace Controllers;
use Models\User;

class UserController
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function home(): void
    {
        echo "Home";
    }

    public function register(): void
    {
        // Handle the form submission to register a new user
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $city = $_POST['city'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Validate the required fields.
            if (empty($name) || empty($surname) || empty($email) || empty($password) || empty($confirmPassword)) {
                echo 'Name, surname, email, password, and confirm password are mandatory fields.';
                return;
            }

            // Validate password and confirm password match.
            if ($password !== $confirmPassword) {
                echo 'Password and confirm password do not match.';
                return;
            }

            // Hash the password.
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database.
            $this->userModel->setUser($name, $surname, $email, $phone, $city, $hashedPassword);


            // Redirect to the login page.
            header('Location: /login');
            exit;
        }

        // Render the view to register a new user.
        include __DIR__ . '/../templates/user/register.php';
    }

    public function login(): void
    {
        // Handle the form submission to log in the user.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Validate the user's credentials.
            $user = $this->userModel->getUser($email);

            if ($user && password_verify($password, $user['password'])) {
                // User is authenticated, store the user's information in the session.
                $_SESSION['user'] = $user;

                // Redirect to the home page or a dashboard.
                header('Location: /');
                exit;
            } else {
                echo 'Invalid email or password.';
                return;
            }
        }

        // Render the view to log in the user.
        include __DIR__ . '/../templates/user/login.php';
    }

    // TODO: Add other methods for logout, edit contact information, create, edit, delete unlimited items for sale after log in.
}