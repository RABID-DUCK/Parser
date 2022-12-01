<?php 

require_once __DIR__.'/Librarys/phpQuery-onefile.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

function Parser($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

$result = Parser("https://vsedveri-33.ru/dveri/vhodnie/%D0%B4%D0%B2%D0%B5%D1%80%D0%B8-%D0%B2-%D0%BD%D0%B0%D0%BB%D0%B8%D1%87%D0%B8%D0%B8");

$pq = phpQuery::newDocument($result);

$arrDataCards = array();
$arrListLinks = $pq->find('.sblock4 .name a');
foreach($arrListLinks as $link){
    $arrDataCards[] = pq($link)->attr('href');
}

$arrListCards = array();
foreach($arrDataCards as $card){
    $linkPage = "https://vsedveri-33.ru".$card;
    $resultCard = Parser($linkPage);
    $pq = phpQuery::newDocument($resultCard);
    $arrListCards[] = [
        "name" => $pq->find('.sp-column h1')->text(),
        "price" => intval(preg_replace('/[^0-9]/', '', $pq->find('.price-product')->text())),
        "url" => $linkPage,
        "desc" => $pq->find('.jshop_prod_description')->html()
    ];
}

$jsonData = json_encode($arrListCards);
file_put_contents('product_json.txt', $jsonData);

$json = file_get_contents(__DIR__.'/product_json.txt');
$arrDataCards = json_decode($json, true);
var_dump($arrDataCards); 


// =========================ДНС====================
// $ch = curl_init('https://www.dns-shop.ru/catalog/17a8a01d16404e77/smartfony/');
// curl_setopt($ch, CURLOPT_TIMEOUT, 400);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HEADER, false);
// curl_exec($ch);
// $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// curl_close($ch);
// echo $httpCode;

// $user = "root";
// $pass = "d@fYjyvdq-8fAMOP";

// $db = new PDO('mysql:host=localhost;dbname=testpdo;', $user, $pass);
// try{
//     if($db){
//         echo "Connecting to database is succeful! =)";
//     }
// } catch (PDOException $e){
//     print "Error: ".$e . "<br />";
// }