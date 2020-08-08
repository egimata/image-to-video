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
$command = "ffmpeg -i 'uploads/" . $image . "' -s 128x128 'uploads/output.jpeg'";

// execute that command
system($command);

echo "Overlay has been resized";

// both input files has been selected
$command = "ffmpeg -i 'uploads/" . $video . "' -i 'uploads/output.jpeg'";

// now apply the filter to select both files
// it must enclose in double quotes
// [0:v] means first input which is video
// [1:v] means second input which is resized image
$command .= " -filter_complex \"[0:v][1:v]";

// now we need to tell the position of overlay in video
$command .= " overlay=25:25\""; // closing double quotes

// save in a separate output file
$command .= " -c:a copy 'uploads/output.mp4'";

// execute the command
system($command);

echo "Overlay has been added";