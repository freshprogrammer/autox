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
	$sourceURL = GetInput("sourceURL");
?>
<link rel="stylesheet" href="default.css">
<script>

function formatPage()
{
	var numberOffset = -2;//start of row is 2 cell to the left
	/*
	::assumes this format::
	header row with colspan in first cell and last cells are 'Total' & 'Diff.'
	data row
	pos/number/name/car/color/runX (1-10)/Total/Diff
	
	*/
	
	
	console.log("scanning");
	var raceNumber = '<?php echo $raceNumber ?>';
	if(!(!isNaN(parseFloat(raceNumber)) && isFinite(raceNumber)))
	{
		//not a number
		raceNumber = "zzz";
	}
	console.log("race number = '"+raceNumber+"'");
	
	var firstDataCell = 0;
	var cols = 0;
	var highlightedRowStart = -1;
	
	var tds = document.getElementsByTagName("td");
	//find start of data
	for (i = 0; i < tds.length; i++) 
	{ 
		if(tds[i].textContent == "Total")
		{
			firstDataCell = i+2;
			break;
		}
	}
	for (i = firstDataCell; i < tds.length; i++) 
	{ 
		if(tds[i].textContent == raceNumber)
		{
			console.log("found it - "+i);
			cols = tds[i].parentElement.children.length;
			highlightedRowStart = i+numberOffset;
		}
	}
	
	//highlight row
	if(highlightedRowStart!=-1)
	{
		tds[highlightedRowStart].parentElement.className = "highlightedRacer";
		for (i = highlightedRowStart; i < highlightedRowStart+cols; i++) 
		{ 
			//process row
		}
	}	
}
</script>

<?php
if($sourceURL!="")
	$dataFile = file_get_contents($sourceURL, true);
else
{
	$dataFile = "No Source URL";
	echo "sourceURL = '$sourceURL'";
}

echo $dataFile;
?>
<script>
formatPage();
</script>