<?php 

function getTitleAndImg($url){

    error_reporting(0);

    $html = file_get_contents($url);
    $doc = new DOMDocument();
    $doc->loadHTML($html);
  
    $image = '';
    $title = '';
    // fetch og:tags
    foreach( $doc->getElementsByTagName('meta') as $m ){

          // if had property
          if( $m->getAttribute('property') ){

              $prop = $m->getAttribute('property');

              // here search only og:tags
              if( preg_match("/og:/i", $prop) ){

                //get first image according to og content
                    if ($m->getAttribute('property') == 'og:image'){
                        $image = $m->getAttribute('content');
                    }


                    if ($m->getAttribute('property') == 'og:title'){
                        $title = $m->getAttribute('content');
                    }

          }
        }
          // end if had property

    }
    // end foreach

    echo $title.'<hr>';
    echo $image.'<hr>';
    echo '<img src="'.$image.'">';
    echo '<hr>';

}


$url = ("http://www.balkanweb.com/covid-19-ulet-numri-i-viktimave-ministria-e-shendetesise-135-raste-te-reja-1-humbje-jete-dhe-72-te-sheruar-ne-24-oret-e-fundit/");

echo getTitleAndImg($url);