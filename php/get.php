<?php
  header('Content-Type: application/json');
  $id = $_POST['id'];
  $mysql = new mysqli('localhost', 'u1088104', 'Q8p7P2s5', 'u1088104_users');
  $result = $mysql->query("SELECT * FROM `products` WHERE `id` = $id");
  $mysql->close();
  $product = $result->fetch_assoc();
  $product['number'] = +$product['number'];
  echo json_encode($product);
?>
