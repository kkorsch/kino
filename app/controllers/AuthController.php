<?php

use App\Models\Admin;

class AuthController extends Controller
{
  public function index()
  {
    if (Admin::isLoggedIn()) {
      return header("Location: home");
    }
    return $this->view('auth/index');
  }

  public function login()
  {
    //validation
    if (!isset($_POST['username'], $_POST['password']) || empty($_POST['username']) || empty($_POST['password'])) {
      $_SESSION['loginError'] = 'Nieprawidłowa nazwa użytkownika lub hasło';
    } else {
      $db = new Database;
      $username =  $_POST['username'];
      $password = $_POST['password'];

      //check if such a user exists
      $user = $this->selectOne("SELECT password FROM users WHERE username=:username", [':username' => $username]);

      if (!$user) {
        $_SESSION['loginError'] = 'Nieprawidłowa nazwa użytkownika lub hasło';
      } else {
        $passwordHashed = $user->password;

        //check if password matches
        if (password_verify($password, $passwordHashed)) {
          $_SESSION['loggedIn'] = true;
          $_SESSION['admin'] = $username;
          $_SESSION['flash'] = "Zalogowaleś się jako '{$username}'";
          return header("Location: ". constant("URL"));
        } else {
          $_SESSION['loginError'] = 'Nieprawidłowa nazwa użytkownika lub hasło';
        }
      }
    }
    return header("Location: ".constant('URL')."/Auth");
  }

  public function logout()
  {
    $_SESSION['loggedIn'] = false;
    unset($_SESSION['admin']);

    $_SESSION['flash'] = "Wylogowałes się";
    return header("Location: ".constant("URL"));
  }
}
