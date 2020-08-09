<?php

// first you have to get both input files in separate variables

$image = $_FILES["image"]["name"];

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);

$uploadOk = 1;
$imageFileType1 = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$imageFileType2 = strtolower(pathinfo($target_file1,PATHINFO_EXTENSION));

if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    echo "<hr>The file ". basename( $_FILES["image"]["name"]). " has been uploaded.\n";
  }



// crop the image to ig size and scale it

$command = 'ffmpeg -i "uploads/' . $image . '" -vf "scale=(iw*sar)*max(1080/(iw*sar)\,1080/ih):ih*max(1080/(iw*sar)\,1080/ih), crop=1080:1080" "uploads/output.png"';
// execute that command
system($command);

echo "Overlay has been resized<p></p>";

// convert image to 15 sec video and zoom image

$command = 'ffmpeg -r 1/15 -i uploads/output.png -c:v libx264 -vf fps=25 -pix_fmt yuv420p uploads/out.mp4';
system($command);

echo "Image transformed to video<p></p>";

// add overlay image to top right (logo)
$command = 'ffmpeg -i uploads/out.mp4 -i uploads/descript.png -filter_complex "[0:v][1:v] overlay=25:25:enable=\'between(t,0,15)\'" -pix_fmt yuv420p -c:a copy uploads/overlay.mp4';
system($command);

echo "Logo overlay has been added<p></p>";

// add overlay movable image (border) from bottom to up (depending on title size)

$command = 'ffmpeg -i uploads/overlay.mp4 -ignore_loop 0 -i uploads/border.gif -filter_complex "[1:v]scale=1920:-1 [ovrl], [0:v][ovrl] overlay=0:500:enable=\'between(t,0,15)\'" -c:a copy uploads/overlayborder.mp4';
system($command);

echo "Overlay has been added<p></p>";

// add title with opacity and overlayed 

$command = 'ffmpeg -i uploads/overlayborder.mp4 -filter_complex "[0:v]drawtext=fontfile=OpenSans-Regular.ttf:text=\'Ruku Ruku osh i mollRuku Ruku osh i mollRuku Ruku osh i mollRuku Ruku osh i mollRuku Ruku osh i mollRuku Ruku osh i mollRuku Ruku osh i mollRuku Ruku osh i mollRuku Ruku osh i mollRuku Ruku osh i moll\':fontsize=30:fontcolor=ffffff:alpha=\'if(lt(t,1),0,if(lt(t,3.5),(t-3)/0.5,if(lt(t,13.5),1,if(lt(t,14),(0.5-(t-13.5))/0.5,0))))\':x=30:y=700" uploads/overlayborderandtext.mp4';
system($command);

echo "<p></p>Text Overlay has been added\n\n";