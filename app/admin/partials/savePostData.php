<?php
//saving data in session
if(!empty($_POST['title'])) {
  $_SESSION['title'] = $_POST['title'];
}
if (!empty($_POST['description'])) {
  $_SESSION['description'] = $_POST['description'];
}
if (!empty($_POST['check_list'])) {
  $_SESSION['check_list'] = $_POST['check_list'];
}
if (!empty($_POST['since'])) {
  $_SESSION['since'] = $_POST['since'];
}
if (!empty($_POST['to'])) {
  $_SESSION['to'] = $_POST['to'];
}
