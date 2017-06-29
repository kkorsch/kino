<?php

use App\Models\Admin;

class AdminPanelController extends Controller
{
  public function index()
  {
    if (!Admin::isLoggedIn()) {
      return header("Location: home");
    }
    return $this->view('admin/index');
  }
}
