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
	<title>	</title>
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  
	<link rel="stylesheet" href="Libraries/clockpicker/src/clockpicker.css"></script> 
	<link rel="stylesheet" href="Libraries/clockpicker/src/standalone.css"></script> 
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
	  
	</head>
	
	
	
<body>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<div class="container">	
	<h3>Enter Your Activity Name & Time Spent</h3><br/>
	<form class="form-inline"  action="insert.php" method="post" >
	
	
				 <div class="form-group">
				 <label for="datepicker">Date:</label>
				<input type="text" class="form-control" value ="" id="datepicker" name="datepicker"/>
				</div>
				
			  <div class="form-group">
				 <label for="ActName">Activity Name:</label>
				<input type="text" class="form-control" id="ActName" name="ActName" required>
			  </div>
			  
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
			  
			  
			  
			  <!--div class="checkbox">
				<label><input type="checkbox"> Remember me</label>
			  </div-->
			  
			  <button type="submit" class="btn btn-default">Submit</button>	 

			  
			  
	</form> 

</div>



</body>



<!-- write your own CSS -->
<script>

			var Body =new Array();
			var Job = new Array();
			var Skill = new Array();
			var Application = new Array();
			var row = new Array();
			

<?php 
if(mysqli_num_rows($res)>0)
{
			$qry = "select * from addactivity";						//Prepared Statement
			
			$res = mysqli_query($conn,$qry);					//Run The Query
	
					while($row=mysqli_fetch_row($res))
					{			
						$parsed = date_parse($row[0]);
						$row[0] = ($parsed['hour']*3600+$parsed['minute']*60+$parsed['second'])*1000;
						$parsed = date_parse($row[2]);
						$row[1] = ($parsed['hour']*3600+$parsed['minute']*60+$parsed['second'])*1000;
						// $parsed = date_parse($row[2]);
						// $row[2] = ($parsed['hour']*3600+$parsed['minute']*60+$parsed['second'])*1000;
						// $parsed = date_parse($row[3]);
					    // $row[3] = ($parsed['hour']*3600+$parsed['minute']*60+$parsed['second'])*1000;
	?>
	
	Body.push(<?php echo $row[0]; ?>);
	 Job.push(<?php echo $row[1]; ?>);
	Skill.push(<?php echo $row[0]; ?>);
	Application.push(<?php echo $row[0]; ?>);
<?php 
					}
}
else
{
	echo "Error";
}
 ?>


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
    series: [{
        name: 'Body Fitness',
        data: Body

    }, {
        name: 'Job',
        type: 'column',

        data: Job,
        tooltip: {
            valueSuffix: ' mm'
        }

    }, {
        name: 'Skill Development',
        data: Skill

    }, {
        name: 'Application',
        data: Application

    }]
});
</script>
</html>