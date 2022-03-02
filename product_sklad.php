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

$url_p = 'https://lagunaexpo.su/api/product/read.php';
$result_read_product=curl_result($url_p);
$products=json_decode($result_read_product);
$table_product='';

foreach($products->records as $k){
  $table_product.= "<tr>";
    $table_product.="<td>".$k->name."</td>";  
    $text_option='';
    $list_optionn=explode(';',$k->optionn);
    foreach($list_optionn as $lo){
       $list_lo=explode(':',$lo);
       $text_option.=$list_lo[1].':'.$list_lo[2].'; <br/>';  
    }
    $table_product.="<td>".$text_option."</td>";
    $table_product.="<td>".$k->quantity."</td>";
  $table_product.="</tr>";
}

$url_o = 'https://lagunaexpo.su/api/option/read.php';
$result_read_option=curl_result($url_o);
$options=json_decode($result_read_option);
$filtr='';

$filtr.='<ul>';
foreach($options->records as $op){
  $filtr.='<li>';
    $filtr.=$op->name_option;
      $filtr.='<ul>';
        $value_op=explode(';',$op->ovalue);
        $value_op=array_unique($value_op);
        sort($value_op,SORT_STRING);
        foreach($value_op as $vop){
          $filtr.='<li>';
            $filtr.="<input type='radio' name='".$op->code."' value='".$vop."'>".$vop;
          $filtr.='</li>';
        }
      $filtr.='</ul>';
  $filtr.='</li>';
  
}
$filtr.='</ul>';
?>

<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Наличие носков</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <style>
    html {background: #a691911a;}
    html h1{color: #625d5d;}
    table {border-collapse: collapse; display:block;float:left;}
    table thead {font-weight: bold;}
    th, td {border: 1px solid grey;}
    td {padding:3px;}
    li {list-style-type: none;}
    .filtr{display:block; float:left; border-right:2px solid grey; margin-right:20px;}
    .filtr ul li {border-bottom:2px solid grey;}
    .filtr ul li:last-child {border:none;}
    .filtr ul li ul li {border:none;}
    .filtr label { border-bottom:1px solid grey; font-weight:bold;}
    #submit_filtr{background:#90EE90; padding:10px; width:100%;}
    #dop_link{}
    .btn{margin:7px; background:#90EE90;}
    #info{width:100%;}
    </style>
  </head>
  <body>
    <a href="index.html"> <-Назад </a>
        
    <h1>Наличие</h1>
    <div class="filtr" id="block_filtr"> 
      <label>Фильтр</label>
      <?php echo $filtr; ?>
      <button id="submit_filtr"> Применить</button>
    </div>
    
    <div id="dop_link"></div>
    <div id="info"></div>
    <table>
      <thead>
          <tr>
            <td>Наименование</td>
            <td>Характеристики</td>
            <td>Количество</td>
          </tr>
      </thead>
      <tbody id="result_sql">
        <?php echo $table_product; ?>
     </tbody>
   </table>
   
  </body>
  <script>
  function result_search(url){
     $.ajax({
	   url: url,
	   method: 'get',
	   dataType: 'json',  
	   success: function(json){ 
	         let html='';
	         let sum_q=0;
	         let res=json.records;
	         res.forEach(function(elem, i, res){
	             sum_q=Number(sum_q)+Number(elem.quantity);
                 html+= "<tr>";
                 html+="<td>"+elem.name+"</td>";  
                 html+="<td>"+elem.optionn+"</td>";
                 html+="<td>"+elem.quantity+"</td>";
                 html+="</tr>";
	         });
	         $('#result_sql').html(html);
	         let link1='<button class="btn" onclick=result_search("https://lagunaexpo.su/api/product/search.php?operation=lessThan&cottonPart='+$("#block_filtr input[name=\'cottonPart\']:checked").val()+'")> Показать товары с меньшим содержанием хлопка</button><button class="btn" onclick=result_search("https://lagunaexpo.su/api/product/search.php?operation=moreThan&cottonPart='+$("#block_filtr input[name=\'cottonPart\']:checked").val()+'")> Показать товары с большим содержанием хлопка</button>';
	          $('#dop_link').html(link1);
	         let info='Наименования: '+res.length+', общее количество: '+sum_q+' шт';
	         $('#info').html(info);
       }
      });   
  }
   $("#submit_filtr").click(function(){
      $.ajax({
	   url: 'https://lagunaexpo.su/api/product/search.php?operation=equal',
	   method: 'get',
	   dataType: 'json',  
	   data: $("#block_filtr input[type=\'radio\']:checked"),
	   success: function(json){ 
	         let html='';
	         let sum_q=0;
	         let res=json.records;
	         res.forEach(function(elem, i, res){
	             sum_q=Number(sum_q)+Number(elem.quantity);
                 html+= "<tr>";
                 html+="<td>"+elem.name+"</td>";  
                 html+="<td>"+elem.optionn+"</td>";
                 html+="<td>"+elem.quantity+"</td>";
                 html+="</tr>";
	         });
	         $('#result_sql').html(html);
	         let link1='<button class="btn" onclick=result_search("https://lagunaexpo.su/api/product/search.php?operation=lessThan&cottonPart='+$("#block_filtr input[name=\'cottonPart\']:checked").val()+'")> Показать товары с меньшим содержанием хлопка</button><button class="btn" onclick=result_search("https://lagunaexpo.su/api/product/search.php?operation=moreThan&cottonPart='+$("#block_filtr input[name=\'cottonPart\']:checked").val()+'")> Показать товары с большим содержанием хлопка</button>';
	          $('#dop_link').html(link1);
	          let info='Наименования: '+res.length+', общее количество: '+sum_q+' шт';
	         $('#info').html(info);
       }});  
   });

  </script>
</html>
