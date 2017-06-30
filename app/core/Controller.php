<?php

 class Controller
 {

   public function view($view, $data = [])
   {
     include('../app/views/partials/header.php');
     require_once '../app/views/'. $view .'.php';
     include('../app/views/partials/footer.php');
   }

   public function redirect(string $url = "Home")
   {
     header("Location: ".constant("URL")."/".$url);
   }

   public function insert(string $query, $data = [])
   {
     return $this->execute($query, $data);
   }

   public function delete(string $query, $data = [])
   {
     return $this->execute($query, $data);
   }

   public function selectOne(string $query, $data = [])
   {
     $result = $this->execute($query, $data);
     return $result->fetchObject();
   }

   public function selectMany(string $query, $data = [])
   {
     $result = $this->execute($query, $data);
     return $result->fetchAll();
   }

   private function execute(string $query, $data = [])
   {
     $db = new Database;
     $q = $db->prepare($query);
     if (!empty($data)) {
       $q->execute($data);
     } else {
       $q->execute();
     }
     return $q;
   }

 }
