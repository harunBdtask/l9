<?php
// $a=1;
// ++$a;
// $a*=$a;
// echo$a--;


// class Foo
// {
//     protected $_value;
//     public function construct()
//     {
    
//         $this->$_value=1;
//     }
//     public static function getSomething()
//     {
    
//         return $this->$_value*5;
//     }
// }
// echo Foo :: getSomething();



// session_start();
// $count=0;
// $_SESSION['key1'] = 2;
// $_SESSION['key2']=4;
// session_destroy();
// $count=count($_SESSION);
// echo$count;


// $var='0';
// var_dump(isset($var));
// var_dump(empty($var));
// var_dump(is_null($var));


// $function=function(){
//     return1;
// };
// echo get_class($function);


// $n=rand(2,PHP_INT_MAX);
// $range = range(1,$n);
// foreach($range as $i){
//     foreach($range as $j){
//          echo$i*$j,"\t";
//     }
//     echo PHP_EOL;
// }


// $string ='ELEMENTARY';
// $array=str_split($string);
// echo implode(array_filter($array,function($item){
//     return$item ==='E';
// }));


// function changeValue($y)
// {
//     $y = $y+5;
// }
// $myNumber = 8;
// changeValue($myNumber);
// echo$myNumber;

// $w = 'hello';
// echo "{$w}, world \n";
// echo "$w, world \n";
// echo '$w, world';
// echo $w.', world';


//2
// $array = array('0'=>'z1', '1'=>'Z10', '2'=>'z12', '3'=>'Z2', '4'=>'z3');
// uasort($array, function ($x, $y) {
//     if (strlen($x) === strlen($y)) {
//         return 0;
//     }
//     return strlen($x) < strlen($y) ? -1 : 1;
// });
// print_r($array);
// die;
//1
// $ch = curl_init('http://103.219.147.17/api/json.php');
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HEADER, 0);
// $response = curl_exec($ch);
// $decode = json_decode($response);
// $data = $decode->data;
// preg_match_all('#speed=([^\s]+)#', $data, $matches);
// $newArray = [];
// foreach ($matches[1] as $key => $value) {
//    if ($value >= 60) {
//       $newArray[] = $value; 
//       echo $value."\n";
//    }
// }
// echo "Total:".count($newArray);
// curl_close($ch);
// die;

