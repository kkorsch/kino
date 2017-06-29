<?php

 class Controller
 {

   public function model($model)
   {
     require_once '../app/models/'. $model .'.php';

     return new $model();
   }

   public function view($view, $data = [])
   {
     include('../app/views/partials/header.php');
     require_once '../app/views/'. $view .'.php';
     include('../app/views/partials/footer.php');
   }
 }
