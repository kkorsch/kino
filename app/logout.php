<?php
session_start();
session_destroy();

session_start();
$_SESSION['flash'] = "Wylogowałes się";
header("Location: ../views/index.php");
