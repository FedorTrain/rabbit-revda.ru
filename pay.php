<!DOCTYPE html>
<html >
  <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <meta name="description" content="Продажа упоротых людей">

    <meta name="Keywords" content="">
    <title>SoftPajamas - Купить</title>
    <link href="/styles/main.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/javascript/jquery-3.4.1.min.js"></script>
  </head>
  <body>
    <div id="header">
      <div class="container">
        <div class="row">
          <div class="col-4">
            <a href="http://rabbit-revda.ru"><img src="/images/logo1.svg" id="logo" ></a>
          </div>

          <div id="sidebar" class="col-8">
            <a href="/" class="btn btn-info btn-lg">Все</a>
            <a href="/sections/stock.html" class="btn btn-success btn-lg">В наличии</a>
            <a href="/sections/new.html" class="btn btn-warning btn-lg">Новинки</a>
            <a href="/sections/popular.html" class="btn btn-primary btn-lg">Популярно</a>
            <!-- <a href="http://softpajamas.ru/sections/designer.html" class="btn btn-success btn-lg">Заказать</a> -->
          </div>
        </div>
      </div>
    </div>

    <div id="content">
      <div class="container">
        <div class="row">
          <?php
            $id = $_GET['id'];
            $mysql = new mysqli('localhost', 'u1088104', 'Q8p7P2s5', 'u1088104_users');
            $result = $mysql->query("SELECT * FROM `products` WHERE `id` = $id");
            $mysql->close();
            $product = $result->fetch_assoc();
            echo '<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
               <figure>
                 <p><img src="/images/' . $product['image'] . '" alt="Error"/></p>
                 <figcaption>' . $product['name'] . '</figcaption>
               </figure>
             </div>
              <form action="/php/mail.php?id=' . $id . '" method="post" class="frm col-xs-6 col-sm-6 col-md-8 col-lg-8">
                <input type="text" name="name" placeholder="Имя">
                <input type="tel" name="phone" placeholder="Телефон">
                <input type="text" name="mail" placeholder="Электронная почта">
                <input type="text" name="vk" placeholder="Вконтакте">
                <textarea name="msg" placeholder="Комментарий"></textarea>
                <input type="submit" name="otprav" value="Отправить">
              </form>';
          ?>

        </div>
      </div>
    </div>

 </body>
</html>
