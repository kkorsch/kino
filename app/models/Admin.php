<?php

namespace App\Models;

class Admin
{
  public function isLoggedIn()
  {
    if (!isset($_SESSION['loggedIn'])) {
      $_SESSION['loggedIn'] = false;
    }
    return $_SESSION['loggedIn'] ? true : false;
  }
}
