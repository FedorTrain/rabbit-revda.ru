<!DOCTYPE html>
<html >
  <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <title>SoftPajamas</title>
  </head>
  <body>

    <div id="content">
      <?php
        $pass = $_POST['pass'];
        if ($pass == "dickduck") {

          $id = $_POST['id'];
          $mysql = new mysqli('localhost', 'u1088104', 'Q8p7P2s5', 'u1088104_users');
          $result = $mysql->query("SELECT * FROM `product` WHERE `id` = $id");
          $mysql->query("DELETE FROM products WHERE id = $id");
          $mysql->close();
          $product = $result->fetch_assoc();

          $filename = $product['image'];

          unlink('/var/www/u1088104/data/www/rabbit-revda.ru/images/' . $filename);

        }
        else {
          echo "password is bad";
        }
        header('Location:/admin');

      ?>

    </div>

 </body>
</html>
