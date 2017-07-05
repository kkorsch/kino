<?php

class Database extends PDO
{
  protected $dns = 'mysql:host=localhost;dbname=cinema';
  protected $user = 'root';
  protected $pass = '';

  public function __construct()
  {
      parent::__construct($this->dns, $this->user, $this->pass);
  }
}
