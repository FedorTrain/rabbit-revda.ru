<?php
  header('Content-Type: application/json');

  $mysql = new mysqli('localhost', 'u1088104', 'Q8p7P2s5', 'u1088104_users');
  $result = $mysql->query("SELECT `id` FROM `products`");
  $mysql->close();

  $_id_;

  $i = 0;
  while ($row = $result->fetch_assoc()) {
    $_id_[$i] = $row["id"];
    $i++;
  }

  echo json_encode($_id_);

?>
