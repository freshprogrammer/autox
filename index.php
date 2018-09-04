<?php

//NTAXS
$clubName[1] = "North Texas Autocross Series";
$clubLiveURL[1] = 'http://live.ntaxs.com';
$clubPastResultsURL[1] = 'http://www.ntaxs.com/results.html';
$clubConePenalty[1] = 2;
$clubDataColsCount[1] = 5;

//LSCBMW
$clubName[2] = "Lone Star Chapter BMW CCA";
$clubLiveURL[2] = 'http://autox.lscbmwcca.org';
$clubPastResultsURL[2] = 'https://lscbmwcca.org/motorsports/autocross/autocross-results/';
$clubConePenalty[2] = 1;
$clubDataColsCount[2] = 6;


$totalGroups = 2;
$latestEventDate = "DateMissing";
$latestEventGroup = 1;//should match one of the above

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
Latest Event: <?php echo $clubName[$latestEventGroup]?> on <?php echo $latestEventDate?><br>
<a href='#' onclick="window.location = 'autoxResults.php?raceNumber='+document.getElementById('raceNumber').value + '&conePenalty=<?php echo $clubConePenalty[$latestEventGroup] ?>&dataColsCount=<?php echo $clubDataColsCount[$latestEventGroup] ?>&sourceURL=<?php echo $clubLiveURL[$latestEventGroup] ?>'">Current Data</a><br> 
<form action='autoxResults.php' method='get'>
Race Number:<input type="number" id="raceNumber" name="raceNumber" placeholder="Race Number" min="1" max="999" value='110' />
<input type='hidden' name='conePenalty' value='<?php echo $clubConePenalty[$latestEventGroup] ?>' />
<input type='hidden' name='dataColsCount' value='<?php echo $clubDataColsCount[$latestEventGroup] ?>' />
<input type='hidden' name='sourceURL' value='<?php echo $clubLiveURL[$latestEventGroup] ?>' />
</form>
		   </td></tr>
		   
<tr><td></td></tr>

<?php
//list all group links
for ($i = 1; $i <= $totalGroups; $i++) {
	?>
	<tr><td>
	<?php echo $clubName[$i]?> 
	(<a href='#' onclick="window.location = 'autoxResults.php?raceNumber='+document.getElementById('raceNumber').value + '&conePenalty=<?php echo $clubConePenalty[$i] ?>&dataColsCount=<?php echo $clubDataColsCount[$i] ?>&sourceURL=<?php echo $clubLiveURL[$i] ?>'">formatted data</a>) - 
	Original Live Results: <a href='<?php echo $clubLiveURL[$i] ?>' target='_blank'><?php echo $clubLiveURL[$i] ?></a> - 
	Past <a href='<?php echo $clubPastResultsURL[$i] ?>' target='_blank'>Results</a>
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