<?php

require_once 'db.php';
require_once 'data.php';

 
//функия для сортировки значений приходящего массива из COOKIE, сортировка по убыванию
function cmp($a, $b) {
    if ($a == $b) { return 0;}
    return ($a > $b) ? -1 : 1;
    };
  
    
 //изменение пришедшего массива из COOKIE с интересами   
function changeArray()
{

$data = json_decode($_COOKIE['Interests'], true);     //Распаковываем массив из COOKIE из  json

uasort($data, 'cmp');                       //Сортируем массив по убыванию 

$data = array_slice($data, 0, 15);         //Оставлям в массиве первых 15 значений = 15 Товарам на странице

$data =array_keys($data);                  //Выбираем значения интересов из массива без цифр для дальнейшей выборки из БД
  return($data);                          
}

$interests = changeArray();
print_r($interests);                            //УБРАТЬ, проверка готового массива 


//Функция получения данных с БД соглсно массиву интересов:
  
function selectGoods($mysqli,$interests){

//если массив пустой -раномно 15 товаров из общего каталога
 if (empty($interests)) {
       $stmt = $mysqli->prepare(" SELECT * FROM `product`  ORDER BY RAND() LIMIT 15");
       $stmt->execute();
       $result = $stmt->get_result();
           
    while ($row = $result->fetch_assoc()) {
       echo '<ul>пустой массив COOKIE'.$row['product_name'].'. : '.$row['interests'].'</ul>';  //убрать , вывод для демонстрации
    }
}
 //если в массиве меньше 15 интересов - рандомная выборка из просмотренных интересов
 elseif (count($interests) < 15){
     for($i = 1; $i <= 15; $i++){
        foreach ($interests as $item)
    $stmt = $mysqli->prepare(" SELECT * FROM `product` WHERE JSON_SEARCH(interests, 'all', ? ) ORDER BY RAND() LIMIT 1");                       
     $stmt->bind_param("s", $item);
    $stmt->execute();
    $result = $stmt->get_result();
      
while ($row = $result->fetch_assoc()) {
   
   echo '<ul>Массив меньше 15 интересов'.$row['product_name'].'. : '.$row['interests'].'</ul>';//убрать , вывод для демонстрации
     }
   }
 }
 //если в массиве 15 и более интересов - выборка по каждой категори в порядке , согласно цифрам из массива
 else{
      foreach ($interests as $item){
   $stmt = $mysqli -> prepare(" SELECT * FROM `product` WHERE JSON_SEARCH(interests, 'all', ? ) ORDER BY RAND() LIMIT 1");                       
       
   $stmt->bind_param("s", $item);
   $stmt->execute();
   $result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
  
  echo '<ul>Рандомная выборка при массиве 15 интересов '.$row['product_name'].'. : '.$row['interests'].'</ul>';//убрать , вывод для демонстрации
     }
   }
 }

}
  
selectGoods($mysqli,$interests);


  


?>

  



