<?php
// example call: http://localhost/largest.php?url_without_http=bbc.co.uk

$url = $_GET['url'];
$largest = get_largest_img($url);
$embed = empty($largest) ? "Suitable image not found" : "<img src='" . $largest . "'>";
echo("<html><body>" . $embed . "</body></html>");

function get_largest_img($url) {
  libxml_use_internal_errors(true);
  $html = file_get_contents($url);
  $dom = new DOMDocument();
  $dom->loadHTML($html);
  $imgs = $dom->getElementsByTagname('img');
  $largest = "";
  $largest_area = 0;
  foreach ($imgs as $img) {
    $img_url = $img->getAttribute("src");
    if (empty($img_url)){
       continue;
    }
    if (substr( $img_url, 0, 2 ) === "//"){
       $img_url = "http:" . $img_url;
    } else if (substr( $img_url, 0, 1 ) === "/"){
       $img_url = $url . $img_url;
    }
    $size = getimagesize($img_url);
    if (notlogo($img_url)) {
        if (is_suitable($size)) {
        $width = $size[0];
        $height = $size[1];
        if ($width*$height > $largest_area) {
            $largest = $img_url;
            $largest_area = $width*$height;
        }
        }
    }
  }
  return $largest;
}


function is_suitable($size) {
  // no image for some reason
  if (is_null($size)) {
    return false;
  }
  $width = $size[0];
  $height = $size[1];
  // long images are uninteresting (eg. they can be advert banners)
  if ($width/$height > 10 || $width/$height < 0.1) {
    return false;
  }
  return true;
}

// change Descript.png to logo name
function notlogo($path){
    if (basename($path) == 'Descript.png') return false;
    else return true;
}


?>
