<link rel="stylesheet" href="default.css">
<script src="scripts.js"></script>
<?php
	function GetInput($name, $checkPost=true, $checkGet=true)
	{
		if($checkPost && isset($_POST[$name]))
		{
			$input = $_POST[$name];
		}
		else if($checkGet && isset($_GET[$name]))
		{
			$input = $_GET[$name];
		}
		else
		{
			$input = "";
		}
		return trim($input);
	}

	$raceNumber = GetInput("raceNumber");
	$conePenalty = GetInput("conePenalty");
	$dataColsCount = GetInput("dataColsCount");
	$sourceURL = GetInput("sourceURL");

	if($sourceURL!="")
		$dataFile = file_get_contents($sourceURL, true);
	else
	{
		$dataFile = "No Source URL";
		echo "sourceURL = '$sourceURL'";
	}
	
	echo $dataFile;
	
	echo "<br>";
	echo "<div id='dataTable'></div>";
	
	echo "<BR>Formatted by Doug - Source on <a href='https://github.com/freshprogrammer/autox'>Github</a><BR> original is here <a href='$sourceURL'>$sourceURL</a>"
?>
<script>
formatPage(<?php echo $raceNumber ?>, <?php echo $dataColsCount ?>, <?php echo $conePenalty ?>);
</script>