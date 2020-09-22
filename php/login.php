<?php
  session_start();
  $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
  $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

  $mysql = new mysqli('localhost', 'u1088104', 'Q8p7P2s5', 'u1088104_users');
  $result = $mysql->query("SELECT * FROM `info` WHERE 'username' = $username and 'password' = $password");

  $mysql->close();
  // header('Location:/admin');
  $user = $result->fetch_assoc();

  echo json_encode($user);

?>
