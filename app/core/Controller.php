<?php

 class Controller
 {

   public function view($view, $data = [])
   {
     include('../app/views/partials/header.php');
     require_once '../app/views/'. $view .'.php';
     include('../app/views/partials/footer.php');
   }
 }
