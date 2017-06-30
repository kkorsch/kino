<?php

class ReservationController extends Controller
{
  public function shows(int $film, string $date)
  {
    $today = date('Y-m-d');
    $hour = date('H');

    //deleteing old shows from database
    $this->delete("DELETE FROM shows WHERE show_date<:today", [':today' => $today]);
    $this->delete("DELETE FROM shows WHERE show_date=:today AND hour<:h", [
      ':today' => $today,
      ':h' => $hour,
    ]);

    //delete session seats
    unset($_SESSION['seats']);

    //validation
    if (!empty($film) && !empty($date)) {

      if ($date > date('Y-m-d', strtotime($today.'+1week')) || $date < $today) {
        $_SESSION['flash'] = "Wystąpił bład.";
        return $this->redirect();
      }

      //selecting shows of the day
      if ($date == $today) {
        $data['shows'] = $this->selectMany("SELECT hour FROM shows WHERE film_id=:id AND show_date=:d AND hour>:h",[
          ':id' => $film,
          ':d' => $date,
          ':h' =>  $hour,
        ]);
      } else {
        $data['shows'] = $this->selectMany("SELECT hour FROM shows WHERE film_id=:id AND show_date=:d", [
          ':id' => $film,
          ':d' => $date,
        ]);
      }
      $data['film'] = $film;
      $data['date'] = $date;
      return $this->view('reservations/shows', $data);
    }
    return $this->redirect();
  }

}
