<?php

//NTAXS
$dataName[1] = "North Texas Autocross Series";
$dataURL[1] = 'http://live.ntaxs.com';
$moreInfoURL[1] = 'http://www.ntaxs.com/results.html';

//LSCBMW
$dataName[2] = "Lone Star Chapter BMW CCA";
$dataURL[2] = 'http://autox.lscbmwcca.org';
$moreInfoURL[2] = 'https://lscbmwcca.org/motorsports/autocross/autocross-results/';

$totalGroups = 2;
$latestEventGroup = 2;//should match one of the above
$latestEventDate = "DateMissing"

?>

<head>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>DFW Autocross stuff</title>
<link rel="stylesheet" href="default.css">
</head>
<body>

<table>
<tbody align='center'>

<tr><td><h2>DFW Autocross stuff</h2></td></tr>

<tr><td>
Latest Event: <?php echo $dataName[$latestEventGroup]?> on <?php echo $latestEventDate?><br>
<a href='#' onclick="window.location = 'autoxResults.php?raceNumber='+document.getElementById('raceNumber').value + '&sourceURL=<?php echo $dataURL[$latestEventGroup] ?>'">Current Data</a><br> 
<form action='autoxResults.php' method='get'>
Race Number:<input type="number" id="raceNumber" name="raceNumber" placeholder="Race Number" min="1" max="999" value='110' />
<input type='hidden' name='sourceURL' value='<?php echo $dataURL[$latestEventGroup] ?>' />
</form>
		   </td></tr>
		   
<tr><td></td></tr>

<?php
//list all group links
for ($i = 1; $i <= $totalGroups; $i++) {
	?>
	<tr><td>
	<?php echo $dataName[$i]?> 
	(<a href='#' onclick="window.location = 'autoxResults.php?raceNumber='+document.getElementById('raceNumber').value + '&sourceURL=<?php echo $dataURL[$i] ?>'">formatted data</a>) - 
	     <a href='<?php echo $dataURL[$i]     ?>' target='_blank'><?php echo $dataURL[$i] ?></a> - 
	Past <a href='<?php echo $moreInfoURL[$i] ?>' target='_blank'>Results</a>
	</td></tr>
	<?php
}
?>


<tr><td></td></tr>
<tr><td>Other links</td></tr>
<tr><td><a href='http://www.dlbracing.com/' target='_blank'>DLB Racing</a></td></tr>
<tr><td><a href='https://www.lightspeedimages.com/' target='_blank'>Light Speed Images</a></td></tr>

</tbody>
</table>

</body>
<footer>
Made by Doug - Source on <a href='https://github.com/freshprogrammer/autox' target='_blank'>GitHub</a>
</footer>

<?php
?>