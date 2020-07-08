<?php
  header('Content-Type: application/json');

  $mysql = new mysqli('localhost', 'u0842107_admin', '2Q0n1R1h', 'u0842107_products');
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
