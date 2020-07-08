<!DOCTYPE html>
<html >
  <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <title>SoftPajamas</title>
    <link href="styles/product.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="javascript/jquery-3.4.1.min.js"></script>
  </head>
  <body>
    <div id="header">
      <p align="right">
        +7 9xx xxx xx xx<br>
        sanya@mail.ru
      </p>

      <br><br><br>
      <a href="http://softpajamas.ru">SoftPajamas</a>
      <br><br><br><br>

    </div>
    <div id="sidebar">
      <a href="http://softpajamas.ru"class="sidebar_botton">Все</a>
      <a href="http://softpajamas.ru/sections/stock.html"class="sidebar_botton">В наличии</a>
      <a href="http://softpajamas.ru/sections/new.html"class="sidebar_botton">Новинки</a>
      <a href="http://softpajamas.ru/sections/popular.html"class="sidebar_botton">Популярно</a>
      <a href="http://softpajamas.ru/sections/designer.html"class="sidebar_botton">Заказать</a>

    </div>
    <div id="content">
      <?php
        $id = $_GET['id'];

        $mysql = new mysqli('localhost', 'u0842107_admin', '2Q0n1R1h', 'u0842107_products');
        $result = $mysql->query("SELECT * FROM `products` WHERE `id` = $id");
        $mysql->close();
        $product = $result->fetch_assoc();
        echo '<div class="product">
         <div class="image">
           <img src="./images/' . $product['image'] . '" alt="Error"/>
         </div>
         <div class="info">
           <form action="" method="post" enctype="multipart/form-data">
             <button class="btn btn-success">Купить</button>
           </form>
           <p>' . $product['name'] . '</p>
           <p>' . $product['price'] . 'р</p>
           <p>В наличии: ' . +$product['number'] . '</p>
             <div class="info_of_product">
           <p>' . $product['info'] . '</p>
           </div>
         </div>
         </div>';

       ?>
    </div>

 </body>
</html>
