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
          $mysql = new mysqli('localhost', 'u0842107_admin', '2Q0n1R1h', 'u0842107_products');
          $result = $mysql->query("SELECT * FROM `product` WHERE `id` = $id");
          $mysql->query("DELETE FROM products WHERE id = $id");
          $mysql->close();
          $product = $result->fetch_assoc();

          $filename = $product['image'];

          unlink('/var/www/u0842107/data/www/softpajamas.ru/images/' . $filename);

        }
        else {
          echo "password is bad";
        }
        header('Location:/admin');

      ?>

    </div>

 </body>
</html>
