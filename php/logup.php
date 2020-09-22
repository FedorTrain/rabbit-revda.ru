<?php

  $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
  $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

  $mysql = new mysqli('localhost', 'u1088104_users', 'Q8p7P2s5', 'info');
  $mysql->query("INSERT INTO `products` (`name`, `price`, `image`, `info`, `type`, `number`)
  VALUES('$name','$price','$image','$info','$type','$number')");

  $mysql->close();
  header('Location:/admin');

?>
