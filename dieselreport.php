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

$query = mysqli_query($db,"INSERT INTO `dieselreport`  SELECT m.busid busid,count(d.busid) count,COALESCE(sum(price),0) price,COALESCE(AVG(milage),0) milage  from masterdemo m LEFT OUTER JOIN diesel d on m.busid=d.busid and d.date between '$fromdate' and '$todate' group by m.busid; ");  
$query2=mysqli_query($db,"INSERT into `dieselreport` select 'Total' as busid , sum(count) , sum(price) , NULL from `dieselreport`");

header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=\"diesel.xls\"");
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
$fields = array('&emsp;Bus ID&emsp;<br> <br> &emsp;','&emsp;Count&emsp;<br> <br> &emsp;','&emsp;Price&emsp;<br> (in &#8377;)','&emsp;Milage&emsp;<br> (in km/ltr)' ); 
 
// Display column names as first row 
$excelData = "<table border=2px solid black;><tr><td colspan='4' align='center'><b>Diesel Report ( $fromdate to $todate )</b></td></tr>\n";
$excelData .= "<tr><td><b>" . implode("</b></td><td align='center'><b>", array_values($fields)) . "</b></td></tr>\n";


// Fetch records from database 
$query = mysqli_query($db,"SELECT * from dieselreport"); 
if($query->num_rows > 0){ 
    // Output each row of the data 
    while($row = $query->fetch_assoc()){  
        $lineData = array($row['busid'], $row['count'], $row['price'], $row['milage'] ); 
        array_walk($lineData, 'filterData'); 
        $excelData .= "<tr><td>" . implode("</td><td>", array_values($lineData)) . "</td></tr>\n"; 
    } 
}else{ 
    $excelData .= "<tr><td colspan='4'>No records found...</td></tr>\n"; 
}  
echo $excelData;
// Headers for download 
// Render excel data 
$query = mysqli_query($db,"delete from dieselreport;");
exit;

ob_end_flush(); 

}
?>

<html>
<head>
<title>DIESEL REPORT</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="reports.css">
    </style>
</head>
<body>
<header>
    <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
        <h1>DIESEL REPORT</h1>
    </header><br><br>
    <script>
function back()
{
 location.href="Transport.php"
}
</script>

<form action="" method="post">
    <div class="myDiv" > 
    <br><br><br>
<label for="from" >from date: </label><input type="date" name="fromdate"  min="2007-01-01" max="2050-12-31" required value="<?php echo $_POST['fromdate'] ?? ''; ?>">  <br><br>
<label for="to">to date:</label><input type="date" name="todate"  min="2007-01-01" max="2050-12-31" required value="<?php echo $_POST['todate'] ?? ''; ?>"><br><br>
<a>
<button type="submit" class="btn" value="Display" name="display">Display</button>
<button type="submit" class="btn" name="export" value="export">Export</button>
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
        $query=mysqli_query($db,"INSERT INTO `dieselreport` SELECT m.busid busid,count(d.busid) count,COALESCE(sum(price),0) price,COALESCE(AVG(milage),0) milage  from masterdemo m LEFT OUTER JOIN diesel d on m.busid=d.busid and d.date between '$fromdate' and '$todate' group by m.busid;");
        $query2=mysqli_query($db,"INSERT into `dieselreport` select 'Total' as busid , sum(count) , sum(price) , NULL from `dieselreport`");
        $query3 = mysqli_query($db,"select * from dieselreport;"); 
        echo "<table border='1'>
       <tr>
       <th colspan='4'>Diesel Report from <br>(" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr><tr>
       <th>&emsp;Bus Id&emsp;</th>
       <th>&emsp;Count&emsp;</th>
       <th>&emsp;Price&emsp; <br> (in &#8377;)</th>
       <th>&emsp;Milage&emsp; <br> (in km/ltr)</th>
       
       </tr>
       ";
 while($row= mysqli_fetch_array($query3)){
          echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>";
          echo "<td>" . $row['count'] . "</td>";
          echo "<td>" . $row['price'] . "</td>";
          echo "<td>" . $row['milage']. "</td>";
          echo "</tr>";
          }
          echo "</table>";
     }
     $query4 = mysqli_query($db,"delete from dieselreport;");
    }
?>

</body>
</html>