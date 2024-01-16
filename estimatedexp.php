
<!DOCTYPE html>
<html>

<head>
<title>ESTIMATED EXPENDITURE</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
<link rel="stylesheet" href="reports.css">
</head>
<body>
<header>
    <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
        <h1> TAXES TO PAY </h1>
    </header>
    <script>
function back()
{
 location.href="Transport.php"
}
</script>

<div class="myDiv">
<form action="" method="POST">
  <label for="month_year">Select month and year:</label>
  <input type="month" id="month_year" name="month_year" required>
  <input type="submit" value="Display" name="display" id="btn1"> 
</form> 
</div>
</body>
</html>

<?php
if(isset($_POST['display'])){
    $month_year=$_POST["month_year"];
//echo $month_year;
$arr=explode('-',$month_year);
$year=$arr[0];

$month= $arr[1];

    $conn=new mysqli("localhost","root","","transport");
      if($conn->connect_error){
          die("connection failed");
      }
    
    else{
        $query=mysqli_query($conn,"select Busid , Insurance , QuarterlyTax , PeriodicalTaxes from(
            select m1.busid Busid , (select IF(EXTRACT(YEAR_MONTH FROM i.todate)='202302', 'yes' , 'no' ) as IStatus from masterdemo m LEFT OUTER JOIN insurance i on m.busid=i.id  where m.busid=m1.busid group by m.busid ) Insurance ,
                (select IF(EXTRACT(YEAR_MONTH FROM i.to)='202302', 'yes' , 'no' ) as QStatus from masterdemo m LEFT OUTER JOIN quarterlytax i on m.busid=i.busid  where m.busid=m1.busid group by m.busid) QuarterlyTax ,
                (select IF(EXTRACT(YEAR_MONTH FROM i.to)='202302', 'yes' , 'no' ) as PStatus from masterdemo m LEFT OUTER JOIN periodicaltaxes i on m.busid=i.busid  where m.busid=m1.busid group by m.busid) PeriodicalTaxes
            from `masterdemo` m1
            ) as q1 where Insurance='yes' or QuarterlyTax='yes' or PeriodicalTaxes='yes';
             ");
        echo "<table border='2' style='border: 2px solid black; color:black; background-color:white;'>
        <tr>
        <th colspan=8>Body Repairs</th></tr>
        <tr>
        <th>Busid</th>
        <th>Insurance</th>
        <th>QuarterlyTax</th>
        <th>PeriodicalTaxes</th> 
        </tr> ";
    
        while($row= mysqli_fetch_array($query)){
            echo "<tr>";
              echo "<td>" . $row['Busid'] . "</td>";
              echo "<td>" . $row['Insurance'] . "</td>";
              echo "<td>" . $row['QuarterlyTax'] . "</td>";
              echo "<td>" . $row['PeriodicalTaxes'] . "</td>"; 
              echo "</tr>";
        }
        echo "</table>";
    }
    }
    



?>