<?php
require_once "./../models/cartModel.php";
class CartController {
  private $model;
    public function __construct()
    {
        
        $this->model = new cartModel();
    }

  public function add() {
    session_start();
    if (!isset($_SESSION['client'])) {
      header('Location: ./auth.php');
      exit;
    }
    $userId = $_SESSION['client']['id'];
    $prodId = $_GET['product_id'] ?? null;
    if ($prodId) {
      $this->model->addItem($userId, $prodId, 1);
    }
    header('Location: ./?controller=cart&action=view');
  }

  public function view() {
    session_start();
    if (!isset($_SESSION['client'])) {
      header('Location: ./auth.php');
      exit;
    }
    $items = $this->model->getItems($_SESSION['client']['id']);
    include 'views/cart/view.php';
  }

  public function remove() {
    session_start();
    if (!isset($_SESSION['client'])) {
      header('Location: ./auth.php');
      exit;
    }
    $this->model->removeItem($_SESSION['client']['id'], $_GET['product_id']);
    header('Location: ./?controller=cart&action=view');
  }
}
