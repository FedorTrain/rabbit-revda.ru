function inputProduct(id, image, name, price, info, type, number) {
  $('#content .row').append(
    '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">'+
      '<a href ="/product.php?id=' + id + '" class="product">'+
        '<div>'+
          '<img src="/images/' + image + '" alt="Loading..."/>'+
          '<h3>' + name + '</h3>'+
          '<samp>' + price + 'р</samp>'+
        '</div>'+
      '</a>'+
    '</div>');
}

function product(id) {
  var name;
  var price;
  var image;
  var id;
  var get = false;
  $.ajax({
    type: "POST",
    url: '/php/get.php',
    dataType: 'json',
    data: {id: id},
    //async: false,
    success: function (data, textStatus, jqXHR) {
    //  alert("good, maybe");
      id = data.id;
      name = data.name;
      price = data.price;
      image = data.image;
      info = data.info;
      type = data.type;
      if (data.number > 0) {
        number = "В наличии";
      } else {
        number = "Нет в наличии";
      }
      get = true;
      inputProduct(id, image, name, price, info, type, number);
    },
    error: function (jqXHR, textStatus, errorThrown){
      // alert('error');
    }
  });
  return get;
}

var product_id;
function load_id() {
  $.ajax({
    type: "POST",
    url: '/php/load_id.php',
    dataType: 'json',
    data: {},
    async: false,
    success: function (data, textStatus, jqXHR) {
      product_id = Object.values(data);
    },
    error: function (jqXHR, textStatus, errorThrown){
    }
  });
}

$('document').ready(function() {

  // inputProduct(1 ,'p1.jpg', 'name', 123, 'rrrd sdfds f', 'new', 1);

  load_id();
  for (i = 0; i < product_id.length ; i++) {
    product(product_id[i]);
  }


});
