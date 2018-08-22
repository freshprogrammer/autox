<link rel="stylesheet" href="default.css">
<?php

$dataURL = "http://www.ntaxs.com/uploads/2/5/5/1/25513290/2018_4_july_21_fin.htm";

$dataFile = file_get_contents($dataURL, true);

echo $dataFile;
?>