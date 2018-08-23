<?php

//NTAXS
$dataName[1] = "North Texas Autocross Series";
$dataURL[1] = 'http://live.ntaxs.com';

//LSCBMW
$dataName[2] = "Lone Star Chapter BMW CCA";
$dataURL[2] = 'http://autox.lscbmwcca.org';

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

<tr><td>Lone Star Chapter BMW CCA (<a href='#' onclick="window.location = 'autoxResults.php?raceNumber='+document.getElementById('raceNumber').value + '&sourceURL=http://autox.lscbmwcca.org'">live data</a>) - <a href='http://autox.lscbmwcca.org/'>http://autox.lscbmwcca.org</a></td></tr>
<tr><td>North Texas Autocross Series (<a href='#' onclick="window.location = 'autoxResults.php?raceNumber='+document.getElementById('raceNumber').value + '&sourceURL=http://live.ntaxs.com'">live data</a>) - <a href='http://live.ntaxs.com/'>http://live.ntaxs.com</a></td></tr>
<tr><td></td></tr>
<tr><td>Other links</td></tr>
<tr><td><a href='http://www.dlbracing.com/' target='_blank'>DLB Racing</a></td></tr>
<tr><td></td></tr>
<tr><td><a href='https://www.lightspeedimages.com/' target='_blank'>Light Speed Images</a></td></tr>

</tbody>
</table>

</body>
<footer>
Made by Doug - Source on <a href='https://github.com/freshprogrammer/autox' target='_blank'>GitHub</a>
</footer>

<?php
?>