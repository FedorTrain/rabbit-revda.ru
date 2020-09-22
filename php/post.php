<?php
        if ($_POST['pass'] == "dickduck") {
          $uploaddir = '/var/www/u1088104/data/www/rabbit-revda.ru/images/';
          $uploadfile = $uploaddir . basename($_FILES['image']['name']);

          if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {

            $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
            $price = filter_var(trim($_POST['price']), FILTER_SANITIZE_STRING);
            $type = filter_var(trim($_POST['type']), FILTER_SANITIZE_STRING);
            $number = filter_var(trim($_POST['number']), FILTER_SANITIZE_STRING);
            $image = $_FILES['image']['name'];
            $info = filter_var(trim($_POST['info']), FILTER_SANITIZE_STRING);

            $mysql = new mysqli('localhost', 'u1088104', 'Q8p7P2s5', 'u1088104_users');
            $mysql->query("INSERT INTO `products` (`name`, `price`, `image`, `info`, `type`, `number`)
            VALUES('$name','$price','$image','$info','$type','$number')");

            $mysql->close();
            header('Location:/admin');
          }
        } else {
          echo "password is bad";
        }
      ?>
<!--
    </div>

 </body>
</html> -->
