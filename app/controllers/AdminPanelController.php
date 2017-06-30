<?php

use App\Models\Admin;

class AdminPanelController extends Controller
{
  public function index()
  {
    $this->auth();
    return $this->view('admin/index');
  }

  public function deleteAdmin()
  {
    $this->auth();
    return $this->view('admin/deleteAdmin');
  }

  public function addAdmin()
  {
    $this->auth();
    return $this->view('admin/addAdmin');
  }

  public function addFilm()
  {
    $this->auth();
    return $this->view('admin/addFilm');
  }

  public function Films()
  {
    $this->auth();

    //select films from database
    $films = $this->selectMany("SELECT title, start, finish, slug FROM films ORDER BY start DESC");

    return $this->view('admin/films', $films);
  }

  public function prolong(string $slug = '')
  {
    $this->auth();

    if(isset($slug) && !empty($slug)) {
      //select film from database
      $film = $this->selectOne("SELECT title, finish, slug FROM films WHERE slug=:slug", [':slug' => $slug]);

      if ($film) {
        $_SESSION['oldTo'] = $film->finish;
        return $this->view("admin/prolong", $film);
      }
    }

    $_SESSION['flash'] = "Wystąpił bład";
    return header("Location: ".constant("URL")."/AdminPanel/Films");
  }


  public function edit(string $slug = '')
  {
    $this->auth();

    if(isset($slug) && !empty($slug)) {
      //select film from database
      $film = $this->selectOne("SELECT title, slug, description FROM films WHERE slug=:slug", [':slug' => $slug]);

      if ($film) {
        return $this->view("admin/edit", $film);
      }
    }

    $_SESSION['flash'] = "Wystąpił bład";
    return header("Location: ".constant("URL")."/AdminPanel/Films");
  }

  public function prolonging(string $slug = '')
  {
    $this->auth();

    //validation
    if (empty($slug) || empty($_POST['newTo']) || empty($_POST['check_list'])) {
      $_SESSION['flash'] = "Żadne pole nie może pozostać puste!";
    } else {
      //prapare data
      $newTo = $_POST['newTo'];
      $shows = $_POST['check_list'];

      //date validation
      if ($newTo < $_SESSION['oldTo'] || $newTo == $_SESSION['oldTo'] || $newTo > date('Y-m-d', strtotime($_SESSION['oldTo'].'+1month')) ) {
        $_SESSION['flash'] = "Nowa data musi być w ciągu miesiąca od obecnej daty zakończenia!";
      } else {
        //check if film exists
        $check = $this->selectOne("SELECT id FROM films WHERE slug=:slug", [':slug' => $slug]);

        if ($check) {
          //update 'to' date in database
          $save = $this->insert("UPDATE films SET finish=:newTo WHERE slug=:slug", [
            ':slug' => $slug,
            ':newTo' => $newTo,
          ]);

          if ($save) {
            $days = (strtotime($newTo)-strtotime($_SESSION['oldTo']))/86400;

            //adding shows to database
            for ($i=1; $i<=$days; $i++) {
              $day = date('Y-m-d', strtotime($_SESSION['oldTo'].'+'.$i.'days'));
              foreach ($shows as $h) {
                $this->insert("INSERT INTO shows(film_id, hour, show_date) VALUES (:id, :h, :day)", [
                  ':id' => $check->id,
                  ':h' => $h,
                  ':day' => $day,
                ]);
              }
            }

            $_SESSION['flash'] = 'Data została zmieniona!';
            }
          } else {
            $_SESSION['flash'] = 'Wystąpił bład podczas edytowania.';
          }
        return header("Location: ".constant("URL")."/AdminPanel/Films");
      }
    }
    if (!empty($_POST['newTo'])) {
      $_SESSION['newTo'] = $_POST['newTo'];
    }
    if (!empty($_POST['check_list'])) {
      $_SESSION['check_list'] = $_POST['check_list'];
    }

    $film = htmlspecialchars($slug);
    return header("Location: ".constant("URL")."/AdminPanel/prolong/".$film);
  }

  public function editing(string $slug)
  {
    $this->auth();

    //validation
    if (empty($_POST['title']) || empty($_POST['description'])) {
      $_SESSION['flash'] = "Żadne pole nie może pozostać puste!";
    } elseif (strlen($_POST['title']) > 250) {
      $_SESSION['flash'] = 'Tytuł może mieć maksymalnie 250 znaków!';
    } else {

      //prepare data
      $title = $_POST['title'];
      $description = $_POST['description'];

      //check if film exists
      $check = $this->selectOne("SELECT id FROM films WHERE slug=:slug", [':slug' => $slug]);

      if ($check) {
        //update film in database
        $save = $this->insert("UPDATE films SET title=:title, description=:description WHERE slug=:slug", [
          ':slug' => $slug,
          ':title' => $title,
          ':description' => $description,
        ]);

        if ($save) {
          $_SESSION['flash'] = 'Film został zedytowany!';
          }
        } else {
          $_SESSION['flash'] = 'Wystąpił bład podczas edytowania.';
        }
      return header("Location: ".constant("URL")."/AdminPanel/Films");
    }

    if(!empty($_POST['title'])) {
      $_SESSION['title'] = $_POST['title'];
    }
    if (!empty($_POST['description'])) {
      $_SESSION['description'] = $_POST['description'];
    }

    $film = htmlspecialchars($slug);
    return header("Location: ".constant("URL"). "/AdminPanel/edit/".$film);
  }

  public function addingFilm()
  {
    $this->auth();

    $today = date('Y-m-d');
    $nextMonth = date('Y-m-d', strtotime($today.'+1month'));

    //validation
    if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['check_list']) || empty($_POST['start']) || empty($_POST['finish'])) {
      $_SESSION['flash'] = "Żadne pole nie może pozostać puste!";

    } elseif ($_POST['start'] < $today || $_POST['start'] > $nextMonth) {
      $_SESSION['flash'] = 'Data Rozpoczęcia musi być w ciągu najbliższego miesiąca!';

    } elseif ($_POST['finish'] < $today || $_POST['finish'] < $_POST['start'] || $_POST['finish'] > date('Y-m-d', strtotime($_POST['start'].'+1month')) ) {
      $_SESSION['flash'] = 'Data Zakończenia może być maksymalnie miesiąc od Rozpoczęcia!';

    } elseif (strlen($_POST['title']) > 250) {
      $_SESSION['flash'] = 'Tytuł może mieć maksymalnie 250 znaków!';

    } else {

      //prapare data
      $db = new Database;
      $title = $_POST['title'];
      $slug = $this->slugify($title);
      $description = $_POST['description'];
      $shows = $_POST['check_list'];
      $start = $_POST['start'];
      $finish = $_POST['finish'];

      //check if film already exists
      $check = $this->selectOne("SELECT id FROM films WHERE slug=:slug", [':slug' => $slug]);

      if ($check) {
        $_SESSION['flash'] = "Ten film został już dodany!";
        return header("Location: ".constant("URL"). "/AdminPanel");
      }

      //adding film to database
      $save = $this->insert("INSERT INTO films(title, slug, description, start, finish) VALUES (:title, :slug, :description, :start, :finish)", [
        ':title' => $title,
        ':slug' => $slug,
        ':description' => $description,
        ':start' => $start,
        ':finish' => $finish,
      ]);

      if ($save) {
        $film = $this->selectOne("SELECT id FROM films WHERE slug=:slug", [':slug' => $slug]);
        $id = $film->id;

        $days = (strtotime($finish)-strtotime($start))/86400;

        //adding shows to database
        for ($i=0; $i<=$days; $i++) {
          $day = date('Y-m-d', strtotime($start.'+'.$i.'days'));
          foreach ($shows as $h) {
            $this->insert("INSERT INTO shows(film_id, hour, show_date) VALUES (:id, :h, :day)", [
              ':id' => $id,
              ':h' => $h,
              ':day' => $day,
            ]);
          }
        }

        $_SESSION['flash'] = 'Film został dodany!';
        return header("Location: ".constant("URL") . "/AdminPanel");
      }
    }
    //save form data
    foreach($_POST as $key => $value) {
      if (!empty($key)) {
        $_SESSION[$key] = $value;
      }
    }

    return header("Location: ".constant("URL"). "/AdminPanel/addFilm");
  }

  public function addingAdmin()
  {
    $this->auth();

    //validation
      if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['password_again']) || empty($_POST['password_confirm'])) {
        $_SESSION['flash'] = "Żadne pole nie może pozostać puste!";
      } elseif ($_POST['password'] != $_POST['password_again']) {
        $_SESSION['flash'] = "Nowe hasło i powtorzone muszą być identyczne!";
      } elseif (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 30) {
        $_SESSION['flash'] = "Hasło musi mieć od 6 do 30 znaków!";
      } elseif (strlen($_POST['username']) < 3 || strlen($_POST['username']) > 30) {
        $_SESSION['flash'] = "Nazwa użytkownika musi mieć od 3 do 30 znaków!";
      } else {
        $password_confirm = $_POST['password_confirm'];

        //check if logged user exists and select data from database
        $user = $this->selectOne("SELECT password FROM users WHERE username=:admin", [':admin' => $_SESSION['admin']]);
        if (!$user) {
          $_SESSION['flash'] = "Bład krytyczny";
          return header("Location: ".constant("URL"));
        }

        //check if password matches
        if (!password_verify($password_confirm, $user->password)) {
          $_SESSION['flash'] = "Niepoprawne hasło";
        } else {
          $username = $_POST['username'];

          //check if user we want to add already exists
          $check = $this->selectOne("SELECT id FROM users WHERE username=:user", [':user' => $username]);

          if ($check) {
            $_SESSION['flash'] = "Istnieje już admin o tej nazwie!";
          } else {
            $password = $_POST['password'];
            $passwordHashed = password_hash($password, PASSWORD_BCRYPT);

            //adding user to database
            $save = $this->insert("INSERT INTO users(username, password) VALUES (:user, :password)", [
              ':user' => $username,
              ':password' => $passwordHashed,
            ]);
            if ($save) {
              $_SESSION['flash'] = "Dodano admina!";
              return header("Location: ".constant("URL")."/AdminPanel");
            }
            $_SESSION['flash'] = "Wystąpił bład, spróbuj ponownie.";
          }
        }
      }
      return header("Location: ".constant("URL")."/AdminPanel/addAdmin");
  }

  public function deletingAdmin()
  {
    $this->auth();

    //validation
      if (empty($_POST['username']) || empty($_POST['password']) ) {
        $_SESSION['flash'] = "Żadne pole nie może pozostać puste!";
      } else {
        $user = $this->selectOne("SELECT password FROM users WHERE username=:admin", [':admin' => $_SESSION['admin']]);
        if (!$user) {
          $_SESSION['flash'] = "Bład krytyczny";
          return header("Location: ".constant("URL"));
        }
        //preapare data
        $password = $_POST['password'];
        $correctPassword = $user->password;

        //check if password matches
        if (!password_verify($password, $correctPassword)) {
          $_SESSION['flash'] = "Niepoprawne hasło";
        } else {
          $username = $_POST['username'];

          //check if user we want to delete exists
          $check = $this->selectOne("SELECT id FROM users WHERE username=:username", [':username' => $username]);

          if (!$check) {
            $_SESSION['flash'] = "Nie ma admina o tej nazwie!";
          } else {
            //deleting user
            $delete = $this->delete("DELETE FROM users WHERE username=:username", [':username' => $username]);
            if ($delete) {
              $_SESSION['flash'] = "Usunięto admina!";
              return header("Location: ".constant("URL")."/AdminPanel");
            }
            $_SESSION['flash'] = "Wystąpił bład, spróbuj ponownie.";
          }
        }
      }
      return header("Location: ".constant("URL")."/AdminPanel/deleteAdmin");
  }

  private function auth()
  {
    if (!Admin::isLoggedIn()) {
      return header("Location: ".constant("URL"));
    }
  }

  private function slugify($string, $replace = array(), $delimiter = '-') {
    // https://github.com/phalcon/incubator/blob/master/Library/Phalcon/Utils/Slug.php
    if (!extension_loaded('iconv')) {
      throw new Exception('iconv module not loaded');
    }
    // Save the old locale and set the new locale to UTF-8
    $oldLocale = setlocale(LC_ALL, '0');
    setlocale(LC_ALL, 'en_US.UTF-8');
    $clean = iconv('UTF-8', 'ascii//TRANSLIT', $string);
    if (!empty($replace)) {
      $clean = str_replace((array) $replace, ' ', $clean);
    }
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower($clean);
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
    $clean = trim($clean, $delimiter);
    // Revert back to the old locale
    setlocale(LC_ALL, $oldLocale);
    return $clean;
  }
}
