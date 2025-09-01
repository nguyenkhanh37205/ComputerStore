<?php
class cartModel {
  private $pdo;
    public function __construct()
    {
        $this->pdo = Database::connect();
    }
    
  public function addItem($userId, $productId, $qty = 1) {
    $sql = "INSERT INTO cart_items (user_id, product_id, quantity)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + ?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$userId, $productId, $qty, $qty]);
  }
  public function getItems($userId) {
    $sql = "SELECT ci.*, p.name, p.price, p.image
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.user_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function removeItem($userId, $productId) {
    $stmt = $this->pdo->prepare("DELETE FROM cart_items WHERE user_id=? AND product_id=?");
    return $stmt->execute([$userId, $productId]);
  }
}
