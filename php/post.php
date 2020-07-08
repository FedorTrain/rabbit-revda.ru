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
        if ($_POST['pass'] == "dickduck") {
          $uploaddir = '/var/www/u0842107/data/www/softpajamas.ru/images/';
          $uploadfile = $uploaddir . basename($_FILES['image']['name']);

          if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {

            $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
            $price = filter_var(trim($_POST['price']), FILTER_SANITIZE_STRING);
            $type = filter_var(trim($_POST['type']), FILTER_SANITIZE_STRING);
            $number = filter_var(trim($_POST['number']), FILTER_SANITIZE_STRING);
            $image = $_FILES['image']['name'];
            $info = filter_var(trim($_POST['info']), FILTER_SANITIZE_STRING);

            $mysql = new mysqli('localhost', 'u0842107_admin', '2Q0n1R1h', 'u0842107_products');
            $mysql->query("INSERT INTO `products` (`name`, `price`, `image`, `info`, `type`, `number`)
            VALUES('$name','$price','$image','$info','$type','$number')");

            $mysql->close();
            header('Location:/admin');


          }
          echo 'Error: ';
          echo $_FILES['image']['name'] . ' - ';
          echo $_FILES['image']['name'] . ' - ';
          echo $uploaddir . ' - ';
          echo $uploadfile;
        } else {
          echo "password is bad";
        }
      ?>

    </div>

 </body>
</html>
