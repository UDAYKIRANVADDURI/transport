
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

$query=mysqli_query($db,"insert into `electricalrepairreport` select id , COALESCE(Battery,0) Battery , COALESCE(SelfMotorAndDinamo,0) SelfMotorAndDinamo ,COALESCE( 
    Battery,0)+COALESCE(Selfmotoranddinamo,0) Total from (
    select m1.busid id ,
 (select sum(b.total) from  battery b where b.from BETWEEN '$fromdate' and '$todate' and b.busid=m1.busid GROUP by b.busid)
  Battery ,
    (select sum(s.cost)  from SelfMotorAndDinamo s  where s.fromdate BETWEEN '$fromdate' and '$todate' and s.busid=m1.busid GROUP by s.busid) Selfmotoranddinamo
    from masterdemo m1 ) as q1; ");

$query2=mysqli_query($db,"INSERT into `electricalrepairreport` select 'Total' as busid , sum(Battery) , sum(SelfMotorAndDinamo) , sum(Total) from `electricalrepairreport`");

header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=\"electricalrepair.xls\"");
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
$fields = array('&emsp;Bus ID&emsp;<br> <br> &emsp;','&emsp;Battery Cost&emsp;<br> (in &#8377;)','&emsp;SelfMotor and Dinamo Cost&emsp;<br> (in &#8377;)','&emsp;Total&emsp;<br> (in &#8377;)' ); 
 
// Display column names as first row 
$excelData = "<table border=2px solid black;><tr><td colspan='4' align='center'><b>Electrical Repair Report ( $fromdate - $todate )</b></td></tr>\n";
$excelData .= "<tr><td><b>" . implode("</b></td><td  align='center' ><b>", array_values($fields)) . "</b></td></tr>\n";

 
// Fetch records from database 
$query = mysqli_query($db,"select * from electricalrepairreport;"); 
if($query->num_rows > 0){ 
    // Output each row of the data 
    while($row = $query->fetch_assoc()){  
        $lineData = array($row['busid'], $row['Battery'], $row['SelfMotorAndDinamo'],  $row['Total']); 
        array_walk($lineData, 'filterData'); 
        $excelData .= "<tr><td>" . implode("</td><td>", array_values($lineData)) . "</td></tr>\n"; 
    } 
}else{ 
    $excelData .= 'No records found...'. "\n"; 
}  
echo $excelData;
// Headers for download 
// Render excel data 
$query = mysqli_query($db,"delete from electricalrepairreport;");
exit;

ob_end_flush(); 

}
?>

<html>
    <head>
    <title>ELECTRICAL REPAIRS REPORT</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="reports.css">
    </head>
    <body>
    <header>
    <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
        <h1>ELECTRICAL REPAIR REPORT</h1>
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
<label for="from" >from date: </label><input type="date" name="fromdate"  min="2007-01-01" max="2050-12-31" required value="<?php echo $_POST['fromdate'] ?? ''; ?>"><br><br>
<label for="to">to date:</label><input type="date" name="todate"  min="2007-01-01" max="2050-12-31" required value="<?php echo $_POST['todate'] ?? ''; ?>">
<br><br>
<a>
<button type="submit" class="btn"  name="display">Display</button>
<button type="submit" class="btn"  name="export" >Export</button>
</a>
</div>
</form>
<br><br>

<?php
if(isset($_POST['display']))
{
$fromdate=$_POST["fromdate"];
$todate=$_POST["todate"];

$db=new mysqli("localhost","root","","transport");
  if($db->connect_error){
      die("connection failed");
  }

else{
    $query=mysqli_query($db,"insert into `electricalrepairreport` select id , COALESCE(Battery,0) Battery , COALESCE(SelfMotorAndDinamo,0) SelfMotorAndDinamo ,COALESCE( 
        Battery,0)+COALESCE(Selfmotoranddinamo,0) Total from (
        select m1.busid id ,
     (select sum(b.total) from  battery b where b.from BETWEEN '$fromdate' and '$todate' and b.busid=m1.busid GROUP by b.busid)
      Battery ,
        (select sum(s.cost)  from SelfMotorAndDinamo s  where s.fromdate BETWEEN '$fromdate' and '$todate' and s.busid=m1.busid GROUP by s.busid) Selfmotoranddinamo
        from masterdemo m1 ) as q1; ");
    $query2=mysqli_query($db,"INSERT into `electricalrepairreport` select 'Total' as busid , sum(Battery) , sum(SelfMotorAndDinamo) , sum(Total) from `electricalrepairreport`");
    $query3 = mysqli_query($db,"select * from electricalrepairreport;"); 

    
    echo "<table>
    <tr>
    <th colspan='4'>Electrical Repair Report<br>(" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr><tr>
       <th>&emsp;Bus ID&emsp;</th>
       <th>&emsp;Battery Cost&emsp; <br> (in &#8377;)</th>
       <th>&emsp;SelfMotor and Dinamo Cost&emsp; <br> (in &#8377;)</th>
       <th>&emsp;Total&emsp; <br> (in &#8377;)</th>
    </tr> ";

    while($row= mysqli_fetch_array($query3)){
        echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>";
          echo "<td>" . $row['Battery'] . "</td>";
          echo "<td>" . $row['SelfMotorAndDinamo'] . "</td>"; 
          echo "<td>" . $row['Total'] . "</td>";
          echo "</tr>";
    }
    echo "</table>";
}
    $query4 = mysqli_query($db,"delete from electricalrepairreport;");
}

?>
</body>
</html>