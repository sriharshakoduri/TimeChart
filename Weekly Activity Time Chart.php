<!DOCTYPE html>

<?php
		
		$conn =  mysqli_connect('localhost','root','','mydb');  //Connection To Database
		
			if( !$conn )
			{
				die("Connection failed to database");			//Check The Database Connection
			}
			
			$qry = "select * from mychart";						//Prepared Statement
			
			$res = mysqli_query($conn,$qry);					//Run The Query
			
?>
<html>


	<head>
	<title>Average Week Spent</title>
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  
	<link rel="stylesheet" href="Libraries/clockpicker/src/clockpicker.css"> 
	<link rel="stylesheet" href="Libraries/clockpicker/src/standalone.css">
	<script src="Libraries/clockpicker/src/clockpicker.js"></script> 
	
		<script>
	$(document).ready(function(){
	var d = new Date();
	var curr_date = d.getDate();
	var curr_month = d.getMonth() + 1;
	var curr_year = d.getFullYear();
	$("#datepicker").val(curr_year + "-" + curr_month + "-" + curr_date);
	});
	</script>
	
	<style>
	.choicebg
	{
		color:red;
	}
	</style>
	  
	</head>
		
<body>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<div class="container">	
	<h3>Enter Your Activity Name & Time Spent</h3><br/>
	<form class="form-inline"  action="insert.php" method="post" >
	
	
				 <div class="form-group">
				 <label for="datepicker">Date:</label>
				<input type="text" class="form-control" value ="" id="datepicker" name="datepicker" />
				</div> &nbsp;&nbsp;&nbsp;&nbsp;
				
			  <div class="form-group" style="color:blue;">
				 <!-- input type="text" class="form-control" id="ActName" name="ActName" required -->
				<select name="ActName" class="form-control" required>
					<option value="Default_Text" class="choicebg" selected disabled >Activity Name</option>
					<option value="Body Fitness">Body Fitness</option>
					<option value="English Communication">English Communication</option>
					<option value="Learing Programming">Learing Programming</option>
					<option value="Application">Application</option>
				</select>
			 </div>
			  
			  &nbsp;&nbsp;&nbsp;&nbsp;
			  
				<label for="Time">Time:</label>
				<div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true">
				 
				<input type="text" class="form-control" value="01:00" name = "ActTime" id="ActTime">
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-time"></span>
				</span>
				</div>
				<script type="text/javascript">
				$('.clockpicker').clockpicker();
				</script>			  
			  
			  <button type="submit" class="btn btn-default">Submit</button>	 

			  <div id ="Date"> </div>
			  <div id="output"></div>
			  
	</form> 

</div>



</body>



<!-- write your own CSS -->
<script>

			var j =0;
			
			//New Way New Variables
			var body_fitness = [0,0,0,0,0,0];
			var english_communication = [0,0,0,0,0,0];
			var learing_programming = [0,0,0,0,0,0];
			var application = [0,0,0,0,0,0];

<?php 
 $day = date('w');
$week_start = date('Y-m-d', strtotime('this week'));
$week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));

if(mysqli_num_rows($res) > 0)
{
			$qry = "select * from addactivity where date between'".$week_start."'and'".$week_end."';";						//Prepared Statement
			
			$res = mysqli_query($conn,$qry);					//Run The Query
	
					while($row=mysqli_fetch_row($res))
					{			
						$parsed = date_parse($row[2]);
						$row[2] = ($parsed['hour']*3600+$parsed['minute']*60+$parsed['second'])*1000;
						
						$dayofweek = date('w', strtotime($row[0]));
						$result    = date('Y-m-d', strtotime(($day - $dayofweek).' day', strtotime($row[0])));
					
	?>
		 
		var indexing_week ="<?php echo $dayofweek ?>";
		indexing_week = indexing_week - 1 ;
		//document.getElementById("Date").innerHTML += indexing_week+" is the index of the week <br/>";
		
		j++;	 
		 
		 //Body Fitness Bar
		 if( "<?php echo $row[1] ?>" == "Body Fitness" )
		 {
			// body_fitness.push(<?php echo $row[2] ?>); 
			body_fitness.splice(indexing_week, 1, <?php echo $row[2] ?>);
			console.log("Body Fitness: "+body_fitness.join());
		 }
		 		
		
		//English Communication Bar
		if( "<?php echo $row[1] ?>" == "English Communication" )    
		{
			 english_communication.splice(indexing_week, 1, <?php echo $row[2] ?>);
			 console.log("English Communication: "+english_communication.join());
		}
		
		

		//Learing Programming Bar
		if( "<?php echo $row[1] ?>" == "Learing Programming" )
		{
			 learing_programming.splice(indexing_week, 1, <?php echo $row[2] ?>);
			 console.log("learing_programming: "+learing_programming.join());
		}
				 
		 //Application Bar
		 if( "<?php echo $row[1] ?>" == "Application" )
		{
			 application.splice(indexing_week, 1, <?php echo $row[2] ?>);
			 console.log("application: "+application.join());
		}		
		 
<?php 
						echo "console.log(\"".$qry."\")";
					}
}
else
{
	echo "Error";
}

?> 
  j=0;
 
 console.log("Body fitness : ");
 console.log(body_fitness);
 console.log(english_communication);
 console.log(learing_programming);
 console.log(application);


Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Weekly Average Time Spent'
    },
    subtitle: {
        text: 'Source: Sriharsha Koduri'
    },
    xAxis: {
		type: 'datetime',
		 dateTimeLabelFormats: { //force all formats to be hour:minute:second
            second: '%H:%M',
            minute: '%H:%M',
            hour: '%H:%M',
            day: '%H:%M',
            week: '%H:%M',
            month: '%H:%M',
            year: '%H:%M'
        },
		categories:		
         [
            'Mon',
            'Tue',
            'Wed',
            'Tur',
            'Fri',
            'Sat',
            'Sun'            
        ],		
        crosshair: true
    },
	
    yAxis: [{
		type: 'datetime',
		
		 dateTimeLabelFormats: { //force all formats to be hour:minute:second
            second: '%H:%M',
            minute: '%H:%M',
            hour: '%H:%M',
            day: '%H:%M',
            week: '%H:%M',
            month: '%H:%M',
            year: '%H:%M'
        },
		pointInterval: 1800000,
		tickInterval:600000,
        title: {
            text: 'Time (hh : mm)'
        }
		
    },

	],
    tooltip: {
			
        formatter: function () {
			if((this.y)/60000 > 60 )
			{
				this.y = ((this.y)/3600000)+' Hours';
			}
			else
			{
				this.y = (this.y)/60000+' Minutes';
			}
            return 'The Time Spent for <b>' + this.series.name +
                '</b> is <b>' +	(this.y) + '</b>';
        }
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [
	{
		name: 'Body Fitness',
		data: body_fitness			
	},{
		name: 'English Communication',
		data: english_communication
	},{
		name: 'Learing Programming', 
		data: learing_programming
	},{
		name: 'Application', 
		data: application
	}	
	]
			
});
</script>
</html>