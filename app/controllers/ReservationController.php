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

  public function hall(int $film, string $date, int $hour)
  {
    //validation get data
    if (!empty($film) && !empty($date) && !empty($hour)) {

      if ($date > date('Y-m-d', strtotime(date('Y-m-d').'+1week')) || $date < date('Y-m-d')) {
        $_SESSION['flash'] = "Wystąpił bład.";
        return $this->redirect();
      }

      //select details and reservations of current show
      $show = $this->selectOne("SELECT id FROM shows WHERE film_id=:id AND show_date=:d AND hour=:h", [
        ':id' => $film,
        ':d' => $date,
        ':h' => $hour,
      ]);
      if ($show) {
        $reservations = $this->selectMany("SELECT seat FROM reservations WHERE show_id=:id", [':id' => $show->id]);
        $data['seats'] = [];

        foreach ($reservations as $k) {
          $data['seats'][] = $k['seat'];
        }

        //create seats list
        if (!isset($_SESSION['seats'])) {
          $_SESSION['seats'] = [];
        }

        //adding seat
        if (isset($_POST['seat']) && !empty($_POST['seat']) && !in_array($_POST['seat'], $_SESSION['seats']) && count($_SESSION['seats']) < 6) {
          $_SESSION['seats'][$_POST['seat']] = $_POST['seat'];
          ksort($_SESSION['seats']);
          unset($_POST['seat']);
        }

        //deleting seat
        if (isset($_POST['delete']) && !empty($_POST['delete'])) {
            unset($_SESSION['seats'][$_POST['delete']]);
            unset($_POST['delete']);
        }
        $data['film'] = $film;
        $data['date'] = $date;
        $data['hour'] = $hour;

        return $this->view('reservations/hall', $data);
      }
    }
      return $this->redirect();
  }

  public function booking()
  {
    //validation
    if (empty($_SESSION['seats']) || empty($_POST['name'])) {
      $_SESSION['flash'] = 'Żadne pole nie może pozostać puste!';
    } elseif (count($_SESSION['seats']) > 6) {
      $_SESSION['flash'] = 'Nieprawidłowa ilość miejsc.';
    } else {
      //prepare data
      $id = $_POST['film'];
      $date = $_POST['date'];
      $h = $_POST['h'];
      $name = $_POST['name'];
      $seats = $_SESSION['seats'];

      //delete session
      unset($_SESSION['seats']);

      if ($date > date('Y-m-d', strtotime(date('Y-m-d').'+1week')) || $date < date('Y-m-d')){
        $_SESSION['flash'] = "Wystąpił bład.";
        return $this->redirect();
      }

      //check if show exists
      $show = $this->selectOne("SELECT id FROM shows WHERE film_id=:id AND show_date=:d AND hour=:h", [
        ':id' => $id,
        ':d' => $date,
        ':h' => $h,
      ]);

      if ($show) {
        //check if chosen seats are not already taken
        $seatsCheck = [];

        foreach ($seats as $m) {
          $x = $this->selectOne("SELECT id FROM reservations WHERE seat=:m AND show_id=:id", [
            ':m' => $m,
            ':id' => $show->id,
          ]);
          if ($x) $seatsCheck[] = $x;
        }

        if (!empty($seatsCheck)) {
          $_SESSION['flash'] = 'Miejsca te są zajęte!';
          return $this->redirect("Reservation/hall/{$id}/{$date}/{$h}");
        }

        //seats reservating (adding to database)
        foreach ($seats as $m) {
          $this->insert("INSERT INTO reservations(show_id, seat, name) VALUES (:id, :m, :name)", [
            ':id' => $show->id,
            ':m' => $m,
            ':name' => $name,
          ]);
        }

        $data = "Rezerwacja na nazwisko '{$name}'!";
        return $this->view('reservations/thanks', $data);
      } else {
        $_SESSION['flash'] = 'Wystąpił bład. Spróbuj ponownie.';
      }
    }
    return $this->redirect();
  }
}
