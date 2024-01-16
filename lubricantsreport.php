<?php
ob_start();
if(isset($_POST['export']))
{
$dbHost     = "localhost"; 
$dbUsername = "root"; 
$dbPassword = ""; 
$dbName     = "transport"; 
$fromdate=$_POST["fromdate"];
$todate=$_POST["todate"];
 
// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

$query=mysqli_query($db,"insert into lubricantsreport Select busid , COALESCE(Coolent,0) Coolent , COALESCE(GearOil,0) GearOil  , 
        COALESCE(StearingOil,0) StearingOil , COALESCE(Greasing,0) Greasing , COALESCE(Coolent,0)+ 
        COALESCE(GearOil,0)+ COALESCE(StearingOil,0)+COALESCE(Greasing,0) Total  from(select m1.busid busid , 
        (select sum(c.tcost) from coolent c where  c.dates BETWEEN '$fromdate' and '$todate' and c.id=m1.busid 
        GROUP by c.id) Coolent , (select sum(g.tcost) from gearoil g where g.dates BETWEEN '$fromdate' and '$todate'
         and g.id=m1.busid GROUP by g.id   ) GearOil , (select sum(st.tcost) from stearingoil st WHERE st.dates 
         BETWEEN '$fromdate' and '$todate' and st.id=m1.busid GROUP by st.id) StearingOil , ( select sum(gr.tcost) 
         from greasing gr where gr.dates BETWEEN '$fromdate' and '$todate' and gr.id=m1.busid GROUP by gr.id) 
         Greasing from masterdemo m1 ) as q1; ");
$query2=mysqli_query($db,"INSERT into `lubricantsreport` select 'Total' as busid , sum(Coolent) , sum(GearOil) , sum(StearingOil) , sum(Greasing) , sum(Total) from `lubricantsreport`");
         

header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=\"lubricants.xls\"");
header("Pragma:no-cache");
header("Expires:0"); 

 
// Check connection 
if ($db->connect_error) { 
    die("Connection failed: " . $db->connect_error); 
}
 
// Filter the excel data 
function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 
 
// Excel file name for download 
//$fileName = "members_" . date('Y-m-d') . ".xls"; 
 
// Column names 
$fields = array('&emsp;busId&emsp; <br> <br> &emsp;','&emsp;Coolent&emsp; <br> (in &#8377;)','&emsp;GearOil&emsp; <br> (in &#8377;)','&emsp;StearingOil&emsp; <br> (in &#8377;)','&emsp;Greasing&emsp; <br> (in &#8377;)','&emsp;Total&emsp; <br> (in &#8377;)'); 
 
$excelData = "<table border=2px solid black;><tr><td colspan='6' align='center'><b>Lubricants Report ( $fromdate to $todate )</b></td></tr>\n";
// Display column names as first row 
$excelData .= "<tr><td  ><b>" . implode("</b></td><td  align='center'><b>", array_values($fields)) . "</b></td></tr>\n";
 
 
// Fetch records from database 
$query = mysqli_query($db,"SELECT * from lubricantsreport;"); 
if($query->num_rows > 0){ 
    // Output each row of the data 
    while($row = $query->fetch_assoc()){  
        $lineData = array($row['busid'], $row['Coolent'], $row['GearOil'], $row['StearingOil'],$row['Greasing'],$row['Total'] ); 
        array_walk($lineData, 'filterData'); 
        $excelData .=  "<tr><td>" . implode("</td><td>", array_values($lineData)) . "</td></tr>\n"; 
    } 
}else{ 
    $excelData .= 'No records found...'. "\n"; 
}  
echo $excelData;
// Headers for download 
// Render excel data 
$query = mysqli_query($db,"delete from lubricantsreport;");
exit;

ob_end_flush(); 

}
?>

<html>
<head><title>LUBRICANTS REPORT</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="reports.css">
    
</head>
<body>
<header>
    <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">  
    <h1>LUBRICANTS REPORT</h1> 
    </header><br><br>
    <script>
function back()
{
 location.href="Transport.php"
}
</script>
<form action="" method="post">
    <div class="myDiv"> 
    <br><br><br>
<label for="from" >from date: </label><input type="date" name="fromdate"  min="2007-01-01" max="2050-12-31" required value="<?php echo $_POST['fromdate'] ?? ''; ?>">  <br><br>
<label for="to">to date:</label><input type="date" name="todate"  min="2007-01-01" max="2050-12-31" required value="<?php echo $_POST['todate'] ?? ''; ?>"><br><br>
<a>
<button type="submit" class="btn"  name="display">DISPLAY</button>
<button type="submit" name="export" class="btn">EXPORT</button>
</a>
</div>
</form>

<?php
if(isset($_POST['display'])){
    $db=new mysqli("localhost","root","","transport");
    if($db->connect_error){
      die("connection failed");
    }
    else{
        $fromdate=$_POST["fromdate"];
        $todate=$_POST["todate"];
        $query=mysqli_query($db,"insert into lubricantsreport Select busid , COALESCE(Coolent,0) Coolent , COALESCE(GearOil,0) GearOil  , 
        COALESCE(StearingOil,0) StearingOil , COALESCE(Greasing,0) Greasing , COALESCE(Coolent,0)+ 
        COALESCE(GearOil,0)+ COALESCE(StearingOil,0)+COALESCE(Greasing,0) Total  from(select m1.busid busid , 
        (select sum(c.tcost) from coolent c where  c.dates BETWEEN '$fromdate' and '$todate' and c.id=m1.busid 
        GROUP by c.id) Coolent , (select sum(g.tcost) from gearoil g where g.dates BETWEEN '$fromdate' and '$todate'
         and g.id=m1.busid GROUP by g.id   ) GearOil , (select sum(st.tcost) from stearingoil st WHERE st.dates 
         BETWEEN '$fromdate' and '$todate' and st.id=m1.busid GROUP by st.id) StearingOil , ( select sum(gr.tcost) 
         from greasing gr where gr.dates BETWEEN '$fromdate' and '$todate' and gr.id=m1.busid GROUP by gr.id) 
         Greasing from masterdemo m1 ) as q1; ");
         $query2=mysqli_query($db,"INSERT into `lubricantsreport` select 'Total' as busid , sum(Coolent) , sum(GearOil) , sum(StearingOil) , sum(Greasing) , sum(Total) from `lubricantsreport`");
         $query3 = mysqli_query($db,"select * from lubricantsreport;"); 

        echo "<table border='1'>
       <tr>
       <th colspan='6'>Lubricants Report from <br>(" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr><tr>
       <th>&emsp;Bus ID&emsp;</th>
       <th>&emsp;Coolent&emsp; <br> (in &#8377;)</th>
       <th>&emsp;Gear Oil&emsp; <br> (in &#8377;)</th>
       <th>&emsp;Stearing Oil&emsp; <br> (in &#8377;)</th>
       <th>&emsp;Greasing Cost&emsp; <br> (in &#8377;)</th>
       <th>&emsp;Total&emsp; <br> (in &#8377;)</th>
       </tr>
       ";
 while($row= mysqli_fetch_array($query3)){
          echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>";
          echo "<td>" . $row['Coolent'] . "</td>";
          echo "<td>" . $row['GearOil'] . "</td>";
          echo "<td>" . $row['StearingOil']. "</td>";
          echo "<td>" . $row['Greasing']. "</td>";
          echo "<td>" . $row['Total']. "</td>";
          echo "</tr>";
          }
          echo "</table>";
     }
     $query4 = mysqli_query($db,"delete from lubricantsreport;");
    }
?>

</body>
</html>