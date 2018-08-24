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

class racerRec{
	
	constructor()
	{
		this.name = '';
		this.number = 0;
		this.carClass = '';
		this.car = '';
		this.runs = [];
		this.best = 0;
	}
	
	calcBest()//return best time or dnf if no non dnf times found
	{
		this.best = 'dnf';
		for (var i = 0; i < this.runs.length; i++) 
		{
			let thisTime = 'dnf';
			if(this.runs[i].indexOf("dnf")!=-1 || this.runs[i]=="")
				thisTime = 'dnf';
			else if(this.runs[i].indexOf("+")!=-1)
				thisTime = this.runs[i].substr(0,this.runs[i].indexOf("+"));
			else
				thisTime = this.runs[i];
			
			if(thisTime<this.best)
				this.best = thisTime;
		}
	}
	
	bestTime()
	{
		if(this.best=='dnf')
			return 999999;
		else return this.best;
	}
	
	GetRawTime(input)
	{
		let thisTime = '999999';
		if(typeof input === 'undefined' || input === null || input=="")
			return thisTime;
		if(input.indexOf("+")!=-1)
			thisTime = input.substr(0,input.indexOf("+"));
		else
			thisTime = input;
		
		return thisTime;
	}
	
	FirstToLast()
	{
		if(this.number==74)
			var debug = true;
		let lastX = this.runs.length;
		while(--lastX>=0 && (this.GetRawTime(this.runs[lastX])<=0 || this.GetRawTime(this.runs[lastX])>=999999));
		return this.GetRawTime(this.runs[0]) - this.GetRawTime(this.runs[lastX]);
	}
	
	BestToWorst()
	{
		let worst = 0;
		let best =999999;
		for (var i = 0; i < this.runs.length; i++) 
		{
			let thisTime = this.GetRawTime(this.runs[i]);
			if(thisTime<best && thisTime>1)
				best = thisTime;
			if(thisTime>worst && thisTime<999999)
				worst = thisTime;
		}
		if(worst==0)
			return 0;
		return worst-best;
	}
}

function roundTime(input)
{
	return Math.round(1000*input)/1000
}

function formatPage()
{
	var numberOffset = -2;//start of row is 2 cell to the left
	/*
	::assumes this format::
	header row with colspan in first cell and last cells are 'Total' & 'Diff.'
	data row
	pos/class/number/name/car/color/runX (1-10)/Total/Diff
	*/
	
	var raceNumber = '<?php echo $raceNumber ?>';
	if(!(!isNaN(parseFloat(raceNumber)) && isFinite(raceNumber)))
	{
		//not a number
		raceNumber = "zzz";
	}
	
	var cols = 0;
	var runCount = 1;
	var data = [];
	
	var carClass = '?';
	var trs = document.getElementsByTagName("tr");
	for (r = 0; r < trs.length; r++) 
	{
		if(trs[r].children.length<9)
			continue;//non part of data table
		if(trs[r].children[0].tagName=='TH')
		{
			if(trs[r].children[0].textContent.indexOf(' - ')==-1)
			{
				continue;//only care about th rows with class info
			}
			else
			{
				carClass = trs[r].children[0].textContent.substr(0,trs[r].children[0].textContent.indexOf(' - '));
			}
		}
		else if(trs[r].children[0].tagName=='TD')
		{
			if(carClass=='?')
				continue;//skip untill a car class is found (in the data)
			cols = trs[r].children.length;
			var runCount = cols-8;//8 non run cols;
			
			var rec = new racerRec();
			
			var col = 0;
			var pos = trs[r].children[col++].textContent;
			var rowCarClass = trs[r].children[col++].textContent;//ignored in favor of th class
			var number = trs[r].children[col++].textContent;
			var name = trs[r].children[col++].textContent;
			var car = trs[r].children[col++].textContent;
			var carColor = trs[r].children[col++].textContent;
			
			
			for (runX = 0; runX < runCount; runX++)
			{
				rec.runs.push(trs[r].children[col++].textContent);
			}
			
			rec.number = number;
			rec.name = name;
			rec.carClass = carClass;
			rec.car = car;
			rec.calcBest();
			data.push(rec);
			
			if(number==raceNumber)
				trs[r].className = "highlightedRacer";
		}
	}
	
	//sort
	data.sort((a, b) => a.bestTime() - b.bestTime());
	
	//relist all racers
	var bottomTable = "<table cellpadding=3 style='border-collapse: collapse' border=1 align='center'>";
	bottomTable += "<tbody>";
	bottomTable += "<tr>";
	bottomTable += "<th>Pos</th>";
	bottomTable += "<th>Class</th>";
	bottomTable += "<th>#</th>";
	bottomTable += "<th>Name</th>";
	bottomTable += "<th>Car</th>";
	bottomTable += "<th>Best</th>";
	bottomTable += "<th>Diff</th>";
	bottomTable += "<th>First-Last</th>";
	bottomTable += "<th>Best-Worst</th>";
	bottomTable += "</tr>";
	var lastTime = data[0].best;
	for (i = 0; i < data.length; i++) 
	{
		if(data[i].number==raceNumber)
			bottomTable += "<tr class='highlightedRacer'>";
		else
			bottomTable += "<tr>";
		
		bottomTable += "<td>"+(i+1)+"</td>";//0 offset +1
		bottomTable += "<td>"+data[i].carClass+"</td>";
		bottomTable += "<td>"+data[i].number+"</td>";
		bottomTable += "<td>"+data[i].name+"</td>";
		bottomTable += "<td>"+data[i].car+"</td>";
		bottomTable += "<td>"+data[i].best+"</td>";
		bottomTable += "<td>+"+roundTime(data[i].best-lastTime)+"</td>";
		bottomTable += "<td>"+roundTime(data[i].FirstToLast())+"</td>";
		bottomTable += "<td>"+roundTime(data[i].BestToWorst())+"</td>";
		bottomTable += "</tr>";
	}
	bottomTable += "</tbody>";
	bottomTable += "</table>";
	document.getElementById('dataTable').innerHTML = bottomTable;
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

echo "<br>";
echo "<div id='dataTable'></div>";

echo "<BR>Formatted by Doug - Source on <a href='https://github.com/freshprogrammer/autox'>Github</a><BR> original is here <a href='$sourceURL'>$sourceURL</a>"
?>
<script>
formatPage();
</script>