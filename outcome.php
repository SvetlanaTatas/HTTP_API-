<?php
function curl_result($url){
  
  $curl_handle=curl_init();
  curl_setopt($curl_handle, CURLOPT_URL,$url);
  curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
  curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.87 Safari/537.36');
  $query = curl_exec($curl_handle);
  return $query;
  curl_close($curl_handle);
}

$url_o = 'https://lagunaexpo.su/api/option/read.php';
$result_read_option=curl_result($url_o);
$options=json_decode($result_read_option);

$attr='';
foreach($options->records as $op){
  $attr.='<div class="block_input"><label>'.$op->name_option.'</label><input type="hidden" name="option['.$op->code.'][id]" value="'.$op->id.'"><input type="text" name="option['.$op->code.'][value]"></div>';    
}

$url_p = 'https://lagunaexpo.su/api/product/read.php';
$result_read_product=curl_result($url_p);
$products=json_decode($result_read_product);

$list_products='';
foreach($products->records as $pr){
  $list_products.='<option value="'.$pr->id.'">'.$pr->name.'</option>';    
}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Отпуск носков</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <style>
    html {background: #a691911a;}
    html h1{color: #625d5d;}
    .kn{background:#fff; border: 1px solid #fff; float:left; padding:30px; margin:15px;}
    .kn a{color: #5a8cea; text-decoration: none; font-weight:600;}
    .block_input label{display:inline-block;width:250px;}
    </style>
  </head>
  <body>
    <a href="index.html"> <-Назад </a>
    <h1>Отпуск носков</h1>
    <div id="form_update">
    <fieldset>
       <legend>Отпустить товар</legend>
       <div class="block_input">
          <label>Наименование</label> 
          <select name="product_u">
            <option value="0">Выберите</option>
            <?php echo $list_products; ?>
          </select>
      </div>  
      <div class="block_input">
          <label>Количество</label> 
          <input type="text" name="quantity_u">
          <input type="hidden" name="operation" value="less">
      </div>
    </fieldset>
      <div class="block_input"><label></label><input type="submit" id="submit_update" value="Изменить"></div>
      <div id="info_u"></div>
      
    </div>
  </body>
  <script>
  $('#submit_update').click(function(){
     $.ajax({
	   url: window.origin +'/api/product/update.php',
	   method: 'post',
	   dataType: 'json',  
	   data: $("#form_update input[type=\'text\'], #form_update select, #form_update input[type=\'hidden\'] "),
	   success: function(json){ 
	     $("#info_u").html(json['message']);
	     $("#form_update input[type=\'text\'], #form_update select, #form_update input[type=\'hidden\'] ").val('');

	   },
	   error: function (jqXHR, exception) {
	      $("#info_u").html(jqXHR.status +' - '+jqXHR.responseText); 
	      $("#form_update input[type=\'text\'], #form_update select, #form_update input[type=\'hidden\'] ").val('');
	   }
      });   
  });
    
  </script>
</html>