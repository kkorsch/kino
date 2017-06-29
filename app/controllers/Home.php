<?php

class Home extends Controller
{
  public function index()
  {
    $db = new Database;
    $today = date('Y-m-d');
    $nextWeek = date('Y-m-d', strtotime($today.'+1week'));
    $query = $db->prepare("SELECT title, slug FROM films WHERE finish>=:today and start<=:nextWeek ORDER BY start DESC");

    if ($filmy = $query->execute([
      ':today' => $today,
      ':nextWeek' => $nextWeek,
    ]) && $query->rowCount()) {
      $filmy = $query->fetchAll();
    }

    $this->view('home/index', $filmy);
  }

  public function film(string $slug = '')
  {
    if (isset($slug) && !empty($slug)) {
      $today = date('Y-m-d');
      $nextWeek = date('Y-m-d', strtotime($today.'+1week'));
      $db = new Database;

      //check if film exists and select from database
      $query = $db->prepare("SELECT * FROM films WHERE slug=:slug AND finish>=:today AND start<=:nextWeek");
      if ($query->execute([
        ':slug' => $slug,
        ':today' => $today,
        ':nextWeek' => $nextWeek,
        ]) && $query->rowCount()) {
        $film = $query->fetchObject();
        $days = [];

        //list of show days
        if ($today < $film->start) {
          $daysCount = (strtotime($today.'+6days')-strtotime($film->start))/86400;
          $start = $film->start;
        } elseif ($today >= $film->start && $today < $film->finish) {
          $daysCount = (strtotime($film->finish)-strtotime($today))/86400;
          if ($daysCount > 6) $daysCount = 6;
          $start = $today;
        } else {
          $daysCount = 0;
          $start = $today;
        }

        for ($i=0; $i<=$daysCount; $i++) {
          $day = date('Y-m-d, l', strtotime($start.'+'.$i.'days'));
          $days[$i] = $day;
        }
        $data['film'] = $film;
        $data['days'] = $days;

        return $this->view('home/film', $data);
      }
      return header("Location: ".constant('URL'));

    }
    return header("Location: ".constant('URL'));
  }
}
