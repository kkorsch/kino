<?php
session_start();

$db = new mysqli('localhost', 'root', '', 'cinema');

if ($db->connect_errno) {
  echo 'Could not connect to database. Please try again later.';
  die();
}

$db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 100);
