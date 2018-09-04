class racerRec
{
	
	constructor()
	{
		this.name = '';
		this.number = 0;
		this.carClass = '';
		this.car = '';
		this.runs = [];
		this.finals = []
		this.best = 0;
	}
	
	calcBest()//return best time or dnf if no non dnf times found
	{
		this.best = '999';
		for (var i = 0; i < this.runs.length; i++) 
		{
			this.finals[i] = this.GetFinalTime(this.runs[i]);
			if(this.finals[i]<this.best)
				this.best = this.finals[i];
		}
	}
	
	bestTime()
	{
		if(this.best=='dnf')
			return 999999;
		else 
			return this.best;
	}
	
	GetFinalTime(input)
	{
		let thisTime = 'dnf';
		if(input.indexOf("dnf")!=-1 || input.indexOf("off")!=-1 || input=="")
			thisTime = 'dnf';
		else if(input.indexOf("+")!=-1)
		{
			thisTime = parseFloat(input.substr(0,input.indexOf("+")));
			let cones = input.substr(input.indexOf("+")+1);
			thisTime += cones*conePenalty;
		}
		else
			thisTime = parseFloat(input);
		return thisTime;
	}
	
	GetRawTime(input, killDNF=false)
	{
		let thisTime = '999999';
		if(typeof input === 'undefined' || input === null || input=="")
			return thisTime;
		if(killDNF && input.indexOf("dnf")!=-1)
			thisTime = 999999;
		if(killDNF && input.indexOf("off")!=-1)
			thisTime = 999999;
		else if(input.indexOf("+")!=-1)
			thisTime = input.substr(0,input.indexOf("+"));
		else
			thisTime = input;
		
		return thisTime;
	}
	
	FirstToLast()
	{
		let lastX = this.runs.length;
		while(--lastX>=0 && (this.GetRawTime(this.runs[lastX])<=0 || this.GetRawTime(this.runs[lastX])>=999999));
		return this.GetRawTime(this.runs[lastX]) - this.GetRawTime(this.runs[0]);
	}
	
	BestToWorst(ignoreDNF=false)
	{
		let worst = 0;
		let best =999999;
		for (var i = 0; i < this.runs.length; i++) 
		{
			let thisTime = this.GetRawTime(this.runs[i],false);
			if(thisTime<best && thisTime>1)
				best = thisTime;
			if(thisTime>worst && thisTime<999999)
				worst = thisTime;
		}
		if(worst==0)
			return 0;
		return best-worst;
	}
	
	BestToWorstIgnoreDNF()
	{
		return this.BestToWorst(true);
	}
}

function roundTime(input)
{
	return Math.round(1000*input)/1000
}

var highlightIndex = 0;
var raceNumber = -1;
var runCount = 1;
var conePenalty = 0;
function formatPage(raceN, dataColsCount, coneP)
{
	raceNumber = raceN;
	conePenalty = coneP;
	/*
	::assumes this format::
	header row with colspan in first cell and last cells are 'Total' & 'Diff.'
	data row
	pos/class/number/name/car/color/runX (1-10)/Total/Diff
	*/
	
	if(!(!isNaN(parseFloat(raceNumber)) && isFinite(raceNumber)))
	{
		//not a number
		raceNumber = "zzz";
	}
	
	var cols = 0;
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
			runCount = cols-8;//8 non run cols;
			//assume 6 data cols - offset as necisary
			runCount += 6-dataColsCount;
			
			var rec = new racerRec();
			
			var col = 0;
			var pos = trs[r].children[col++].textContent;
			var rowCarClass = trs[r].children[col++].textContent;//ignored in favor of th class
			var number = trs[r].children[col++].textContent;
			var name = trs[r].children[col++].textContent;
			var car = trs[r].children[col++].textContent;
			if(dataColsCount==6)
			{//skip color if this page only have 5 base cols
				var carColor = trs[r].children[col++].textContent;
			}
			
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
			else
				trs[r].className = "dataRow";
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
	bottomTable += "<th>Worst-Best</th>";
	
	for (runX = 0; runX < runCount; runX++)
	{
		bottomTable += "<th>Run "+(runX+1)+"</th>";
	}
	bottomTable += "</tr>";
	var lastTime = data[0].best;
	for (i = 0; i < data.length; i++) 
	{
		if(data[i].number==41)
			var debug = true;
		if(data[i].number==raceNumber)
		{
			highlightIndex = i;
			bottomTable += "<tr class='highlightedRacer'>";
		}
		else
			bottomTable += "<tr class='dataRow'>";
		
		bottomTable += "<td>"+(i+1)+"</td>";//0 offset +1
		bottomTable += "<td>"+data[i].carClass+"</td>";
		bottomTable += "<td>"+data[i].number+"</td>";
		bottomTable += "<td>"+data[i].name+"</td>";
		bottomTable += "<td>"+data[i].car+"</td>";
		bottomTable += "<td>"+data[i].best+"</td>";
		bottomTable += "<td>+"+roundTime(data[i].best-lastTime)+"</td>";
		bottomTable += "<td>"+roundTime(data[i].FirstToLast())+"</td>";
		bottomTable += "<td style='border-right-color: black;border-right-width: 4px;'>"+roundTime(data[i].BestToWorstIgnoreDNF())+"</td>";

		for (runX = 0; runX < runCount; runX++)
		{
			if(data[i].finals[runX]==data[i].best)
				bottomTable += "<td><div style='background-image: radial-gradient(ellipse, rgba(255,255,0,1), rgba(0,0,0,0));'>"+data[i].runs[runX]+"</div></td>";
			else
				bottomTable += "<td>"+data[i].runs[runX]+"</td>";
		}
		
		bottomTable += "</tr>";
	}
	bottomTable += "</tbody>";
	bottomTable += "</table>";
	
	let chartCanvas = "<canvas id='myChart' width='8' height='3'></canvas>";
	
	document.getElementById('dataTable').innerHTML = bottomTable + chartCanvas;
	
	createChart(data);
}

function createChart(data)
{
	let chartLabels = [];
	for (runX = 0; runX < runCount; runX++)
	{
		chartLabels[runX] = "Run #"+(runX+1);
	}
	
	let chartDataset = [];
	
	let i = highlightIndex;
	var ctx = document.getElementById("myChart").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: chartLabels,
			datasets: [{
				label: data[i].name,
				data: data[i].finals,
				backgroundColor: '#00f',
				borderColor: '#00f',
				lineTension: 0,
				fill: false,
			}, {
				label: data[i-1].name,
				data: data[i-1].finals,
				backgroundColor: '#66f',
				borderColor: '#66f',
				lineTension: 0,
				fill: false,
			}, {
				label: data[i-2].name,
				data: data[i-2].finals,
				backgroundColor: '#bbf',
				borderColor: '#bbf',
				lineTension: 0,
				fill: false,
			}, {
				label: data[2].name,
				data: data[2].finals,
				backgroundColor: '#fbb',
				borderColor: '#fbb',
				lineTension: 0,
				fill: false,
			}, {
				label: data[1].name,
				data: data[1].finals,
				backgroundColor: '#f66',
				borderColor: '#f66',
				lineTension: 0,
				fill: false,
			}, {
				label: data[0].name,
				data: data[0].finals,
				backgroundColor: '#f00',
				borderColor: '#f00',
				lineTension: 0,
				fill: false,
			}]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Autocross Times'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: false,
						labelString: 'Run'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: false,
						labelString: 'Time'
					}
				}]
			}
		}
	});
}