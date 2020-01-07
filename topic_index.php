<?php

$DB_host     = 'localhost';
$DB_user     = '';
$DB_password = '';
$DB_name     = '';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try {
    $link = new PDO("mysql:host=".$DB_host.";dbname=".$DB_name, $DB_user, $DB_password,$options);
} catch (Exception $e) {
    exit('Unable to connect to database.');
}

$rss_google=file_get_contents("https://trends.google.it/trends/trendingsearches/daily/rss?geo=US"); 
$rss_google=str_replace(":", "", $rss_google);
$rss_google = new SimpleXmlElement($rss_google);

foreach($rss_google->channel->item as $entry) {  

$sql ="SELECT * FROM motore_topic WHERE titolo='".$entry->title."'";
$results = $link->query($sql); 
$verifica[0]['id'] = $results->fetchAll();

if (!!$verifica[0]['id']) { echo "Il dato esiste!<br>";  } else {	

$xyy=$entry->htpicture; $xyx=str_replace('https//', 'https://', $xyy);  $xyv=str_replace('q=tbn', 'q=tbn:', $xyx); $xyvv=str_replace('http//', 'http://', $xyv);
$descrizione_lunga=$entry->htnews_item->htnews_item_snippet;
$entry->htnews_item->htnews_item_url = str_replace('https//', 'https://', $entry->htnews_item->htnews_item_url);
	//var_dump($entry)."<br>";

$random=(rand(10000,9000000));

echo  "<br> /".$entry->title." ".$entry->htnews_item->htnews_item_title." ".$entry->pubDate."/ ";
$entry->htnews_item->htnews_item_url = str_replace('http//', 'http://', $entry->htnews_item->htnews_item_url);

  $sql = "INSERT INTO motore_topic (data, pubdata, titolo, descrizione, descrizione_lunga, token, url, immagine, stato, categoria, traffico)
VALUES ('".date("Y-m-d h:m:s")."', '".$entry->pubDate."', '".$entry->title."', '".$entry->htnews_item->htnews_item_title."', '".$descrizione_lunga."', '".md5($random)."', '".$entry->htnews_item->htnews_item_url."', '".$xyvv."', '0', 'tendenze-usa', '".$entry->htapprox_traffic."')";

$results = $link->query($sql);


}
  }


