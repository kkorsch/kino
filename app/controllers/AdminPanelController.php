<?php

use App\Models\Admin;

class AdminPanelController extends Controller
{
  public function index()
  {
    if (!Admin::isLoggedIn()) {
      return header("Location: home");
    }
    return $this->view('admin/index');
  }

  public function addFilm()
  {
    if (!Admin::isLoggedIn()) {
      return header("Location: home");
    }
    return $this->view('admin/addFilm');
  }

  public function addingFilm()
  {
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

  function slugify($string, $replace = array(), $delimiter = '-') {
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
