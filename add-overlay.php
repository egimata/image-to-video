<?php

// first you have to get both input files in separate variables
$video = $_FILES["video"]["name"];
$image = $_FILES["image"]["name"];

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);
$target_file1 = $target_dir . basename($_FILES["video"]["name"]);
$uploadOk = 1;
$imageFileType1 = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$imageFileType2 = strtolower(pathinfo($target_file1,PATHINFO_EXTENSION));

if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    echo "<hr>The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
  }

if (move_uploaded_file($_FILES["video"]["tmp_name"], $target_file1)) {
    echo "<hr>The file ". basename( $_FILES["video"]["name"]). " has been uploaded.<hr>";
  }


// then you have to resize the selected image to lower resolution
$command = "ffmpeg -i 'uploads/" . $image . "' -s 860x1000 'uploads/output.png'";

// execute that command
system($command);

//$command = "ffmpeg -i 'uploads/output.png' -i 'uploads/" . $video . "' -filter_complex 'overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2' -vcodec png 'uploads/out.mov'";

$command = "ffmpeg -i 'uploads/output.png' -i 'uploads/" . $video . "' -filter_complex '[0:v]setpts=PTS-STARTPTS, scale=480x360[top];[1:v]setpts=PTS-STARTPTS, scale=480x360, format=yuva420p[bottom];[top][bottom]overlay=shortest=1' -vcodec libx264 'uploads/out.mov'";

//$command = "ffmpeg -y -i 'uploads/" . $video . "' -i 'uploads/output.png' -filter_complex '[1:v]alphaextract[alf];[0:v]alphaextract[oalf];[alf][oalf]blend=all_mode=darken[res];[0:v][res]alphamerge' -c:v qtrle -an 'uploads/out.mov'"


// execute that command
system($command);



//$command = "ffmpeg -i uploads/out.mp4 -vf 'format=yuv444p, drawbox=y=ih/PHI:color=black@0.4:width=iw:height=48:t=fill, drawtext=fontfile=OpenSans-Regular.ttf:text=\'Testi Xhanit\':fontcolor=white:fontsize=24:x=(w-tw)/2:y=(h/PHI)+th, format=yuv420p' -c:v libx264 -c:a copy -movflags +faststart outputaa.mp4";

//$command = "ffmpeg -ss 00:00:15 -t 5 -i 'uploads/" . $video . "' -vf drawtext="/usr/share/fonts/truetype/ubuntu/Ubuntu-R.ttf:fontsize=200:fontcolor=white:box=1:boxcolor=black@0.8:x=(w-text_w)/2:y=(h-text_h)/2:text='teksti rakut'" 'uploads/input.mp4'";

// execute that command

//system($command);


echo "Overlay has been resized";

// both input files has been selected
$command = "ffmpeg  -i 'uploads/". $video ."' -i 'uploads/output.png'";

// now apply the filter to select both files
// it must enclose in double quotes
// [0:v] means first input which is video
// [1:v] means second input which is resized image
$command .= " -filter_complex \"[1:v][0:v]";

// now we need to tell the position of overlay in video
$command .= "overlay=(860-800)/2:(1000-800)/2:enable='between(t,0,9)',drawbox=y=ih/PHI:color=black@0.4:t=fill,drawtext=OpenSans-Regular.ttf:fontsize=70:fontcolor=white:x=(w-text_w)/2:y=(h/PHI)+th-10:text='Teksti Ktu'\""; // closing double quotes

// save in a separate output file

$command .= " -c:a copy -movflags +faststart -vcodec libx264 'uploads/output.mov'";

// execute the command
system($command);

echo "Overlay has been added";




