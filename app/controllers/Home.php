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

  
}
