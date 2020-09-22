<?php

  $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
  $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

  $mysql = new mysqli('localhost', 'u1088104_admin', 'Q8p7P2s5', 'u1088104_users');
  $mysql->query("INSERT INTO `info` (`username`, `password`)
  VALUES('$username','$password')");

  $mysql->close();
  // header('Location:/admin');

?>
