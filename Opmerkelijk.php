<?php
//echo "<!--  subplaylist  -->".PHP_EOL;
$fp = fopen('Opmerkelijk.xml', 'w');
$xml = new DOMDocument();
$xml = simplexml_load_file("https://www.nu.nl/rss/Opmerkelijk");

foreach ($xml->channel->item as $key => $text){
	
$item = $text;	
//$item = $xml->channel[0]->item[0];
 
 $dc = $item->children("http://purl.org/dc/elements/1.1/");

$result = $xml->xpath("//rss/channel/item/dc:rights");	
$file = basename($text->enclosure->attributes()->url);	
$headers = get_headers($text->enclosure->attributes()->url, 1);
$content_length = $headers['Content-Length'];
//$hq = preg_replace("/sqr256.jpg/", 'wd1280.jpg', $text->enclosure->attributes()->url, substr($file, 0, strpos($file, ".")).'.jpg');
$hq = (preg_replace("/sqr256.jpg/", 'wd1280.jpg', $text->enclosure->attributes()->url).'"');
//copy (preg_replace("/sqr256.jpg/", 'wd1280.jpg', $text->enclosure->attributes()->url, substr($file, 0, strpos($file, ".")).'.jpg'));
//echo $hq;


if (!file_exists('/var/www/www.internetxs.nl/html/martin/'.substr($file, 0, strpos($file, ".")).'.jpg')){

copy($hq, substr($file, 0, strpos($file, ".")).'.jpg'); 
}	
//$filename = (substr($file, 0, strpos($file, ".")).'.jpg');
$filesize = (substr($file, 0, strpos($file, ".")).'.jpg');
//echo ($filesize);
//echo filesize($filesize);	
if (!file_exists('/var/www/www.internetxs.nl/html/martin/BLUR1_'.$filesize)){

$image = imagecreatefromjpeg($filesize);

for($x=1; $x<=50; $x++) {
	            imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
}

imagefilter($image, IMG_FILTER_SMOOTH, 99);
imagefilter($image, IMG_FILTER_BRIGHTNESS, 20);
imagegammacorrect($image, 1.0, 5.0);
//imagescale($image, 1280, 720,  IMG_BICUBIC);

header("content-type: image/png");
imagepng($image, 'BLUR1_'.$filesize);
imagedestroy($image);
}

fwrite($fp, '<page exectime="0.009" description="template353" type="353" days="su,mo,tu,we,th,fr,sa," fromdate="'.date('d-m-Y').' 00:00:00" todate="'.date('d-m-Y', strtotime('+7 days')).' 23:59:00" fromtime="00:00:00" totime="24:00:00" command="" duration="10000" overlays="externalaudio," 
image2="media/content/BLUR1_'. substr($file, 0, strpos($file, ".")).'.jpg'. '" image2size="'.filesize('BLUR1_'.$filesize).'"'."\n");	
fwrite($fp, 'image2url="http://www.internetxs.nl/martin/BLUR1_'.$filesize.'"'."\n");	
fwrite($fp, 'text1="'.htmlspecialchars(preg_replace("/<a[^>]+\>[a-z]+/i", "", $text->title)).'"'."\n");
//echo ' text2="' . preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $text->description).'"';
$text2 = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $text->description);
$a= htmlspecialchars(strip_tags($text2));

//str_replace('"', "", $text2);
fwrite($fp, ' text2="'.str_replace('"', "", $a).'"'."\n");
fwrite($fp, ' ' . 'text3="'.$dc->rights.'" text99="" on="" popid="" />'."\n");
//fwrite($fp,  $dc->rights);
}
// $item = $xml->channel[0]->item[0];
// $dc = $item->children("http://purl.org/dc/elements/1.1/"); 
//echo $dc->rights;

// str_replace('"', "", $string);
//echo "<!--  end subplaylist  -->";
//print_r($content_length);

//$file = basename($text->enclosure->attributes()->url);
//echo substr($file, 0, strpos($file, ".")).'.jpg';
?>
