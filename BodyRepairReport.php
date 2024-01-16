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

$query = mysqli_query($db,"INSERT INTO `bodyrepairreport`  select id , COALESCE(SpringCost,0) SpringCost , COALESCE(PaintCost,0) PaintCost, COALESCE(GlassworkCost,0) GlassworkCost, COALESCE(SeatsCost,0) SeatsCost, COALESCE(TyresCost,0) TyresCost, COALESCE(StickeringCost,0) StickeringCost ,
        COALESCE(SpringCost,0)+COALESCE(PaintCost,0)+COALESCE(GlassworkCost,0)+COALESCE(SeatsCost,0)+COALESCE(TyresCost,0)+COALESCE(StickeringCost,0) Total
        from (
           select m1.busid id , (SELECT sum(s.totvalue) from springs s where s.fromdate BETWEEN '$fromdate' and '$todate' and s.busid=m1.busid GROUP by s.busid) SpringCost , ( select sum(p.TotalValue) from paints p where p.fromdate BETWEEN '$fromdate' and '$todate' and p.busid=m1.busid GROUP by p.busid) PaintCost , (select sum(g.cost) from glasswork g WHERE g.fromdate BETWEEN '$fromdate' and '$todate' and g.busid=m1.busid GROUP by g.busid ) GlassworkCost , (select sum(se.cost) from seats se where se.fromdate BETWEEN '$fromdate' and '$todate' and se.busid=m1.busid GROUP by se.busid 
           ) SeatsCost ,(select sum(t.TotalValue) from tyres t where t.fromdate BETWEEN '$fromdate' and '$todate' and t.busid=m1.busid GROUP by t.busid) TyresCost , (select sum(st.cost) from stickering st where st.fromdate BETWEEN '$fromdate' and '$todate' and st.busid=m1.busid GROUP by st.busid) StickeringCost  from masterdemo m1   ) as q1; ");

$query2=mysqli_query($db,"INSERT into `bodyrepairreport` select 'Total' as busid , sum(SpringCost) , sum(PaintCost) ,sum(GlassworkCost),sum(SeatsCost) , sum(TyresCost) , sum(StickeringCost),sum(Total) from `bodyrepairreport`");

 

header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=\"bodyrepair.xls\"");
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
$fields = array('&emsp;Bus ID&emsp;<br> <br> &emsp;','&emsp;Springs Cost&emsp; <br> (in &#8377;)','&emsp;Paints Cost&emsp; <br> (in &#8377;)','&emsp;Glass work Cost&emsp; <br> (in &#8377;)','&emsp;Seats Cost&emsp; <br> (in &#8377;)','&emsp;Tyres Cost&emsp; <br> (in &#8377;)','&emsp;Stickering Cost&emsp; <br> (in &#8377;)','&emsp;Total&emsp; <br> (in &#8377;)'); 

// Display column names as first row 
$excelData = "<table border=2px solid black;><tr><td colspan='8' align='center'><b>Body Repair Report ( $fromdate - $todate )</b></td></tr>\n";
$excelData .= "<tr><td><b>" . implode("</b></td><td  align='center' ><b>", array_values($fields)) . "</b></td></tr>\n";

// Fetch records from database 
$query = mysqli_query($db,"select * from bodyrepairreport;"); 
if($query->num_rows > 0){ 
    // Output each row of the data 
    while($row = $query->fetch_assoc()){  
        $lineData = array($row['busid'], $row['SpringCost'], $row['PaintCost'], $row['GlassworkCost'], $row['SeatsCost'], $row['TyresCost'], $row['StickeringCost'], $row['Total']); 
        array_walk($lineData, 'filterData'); 
        $excelData .= "<tr><td>" . implode("</td><td>", array_values($lineData)) . "</td></tr>\n"; 
    } 
}else{ 
    $excelData .= "<tr><td colspan='8'>No records found...</td></tr>\n"; 
}
$excelData .= "</table>";

// Output the HTML table 
echo $excelData;

// Headers for download 
// Render excel data 
$query = mysqli_query($db,"delete from bodyrepairreport;");
exit;

ob_end_flush(); 

}
?>

<html>
    <head>
    <title>BODY REPAIR REPORT</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="reports.css">
 </head>
    <body>
    <header>
    <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
        <h1>BODY REPAIR REPORT</h1>
    </header><br><br>
    <script>
function back()
{
 location.href="Transport.php"
}
</script>
    <form action="" method="post">
    <div class="myDiv" style="margin-top:0px;"> 
    <br><br><br>
<label for="from" >from date: </label><input type="date" name="fromdate"  min="2007-01-01" max="2050-12-31" required value="<?php echo $_POST['fromdate'] ?? ''; ?>">  <br><br>
<label for="to">to date:</label><input type="date" name="todate"  min="2007-01-01" max="2050-12-31" required value="<?php echo $_POST['todate'] ?? ''; ?>"><br><br>

<a>
<button type="submit" class="btn" name="display">Display</button>
<button type="submit" name="export" class="btn">Export</button>
</a>
</div>
</form>
<section>
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
        $query = mysqli_query($db,"INSERT INTO `bodyrepairreport`  select id , COALESCE(SpringCost,0) SpringCost , 
        COALESCE(PaintCost,0) PaintCost, COALESCE(GlassworkCost,0) GlassworkCost, COALESCE(SeatsCost,0) SeatsCost, 
        COALESCE(TyresCost,0) TyresCost, COALESCE(StickeringCost,0) StickeringCost ,
        COALESCE(SpringCost,0)+COALESCE(PaintCost,0)+COALESCE(GlassworkCost,0)+COALESCE(SeatsCost,0)+
        COALESCE(TyresCost,0)+COALESCE(StickeringCost,0) Total
        from (
           select m1.busid id , (SELECT sum(s.totvalue) from springs s where s.fromdate BETWEEN '$fromdate' and
            '$todate' and s.busid=m1.busid GROUP by s.busid) SpringCost , ( select sum(p.TotalValue) from paints p 
            where p.fromdate BETWEEN '$fromdate' and '$todate' and p.busid=m1.busid GROUP by p.busid) PaintCost , 
            (select sum(g.cost) from glasswork g WHERE g.fromdate BETWEEN '$fromdate' and '$todate' and g.busid=m1.busid 
            GROUP by g.busid ) GlassworkCost , (select sum(se.cost) from seats se where se.fromdate BETWEEN '$fromdate' 
            and '$todate' and se.busid=m1.busid GROUP by se.busid 
           ) SeatsCost ,(select sum(t.TotalValue) from tyres t where t.fromdate BETWEEN '$fromdate' and '$todate' 
           and t.busid=m1.busid GROUP by t.busid) TyresCost , (select sum(st.cost) from stickering st where st.fromdate 
           BETWEEN '$fromdate' and '$todate' and st.busid=m1.busid GROUP by st.busid) StickeringCost  from masterdemo m1 )
            as q1; ");


$query2=mysqli_query($db,"INSERT into `bodyrepairreport` select 'Total' as busid , sum(SpringCost) , sum(PaintCost) ,sum(GlassworkCost),sum(SeatsCost) , sum(TyresCost) , sum(StickeringCost),sum(Total) from `bodyrepairreport`");

$query3 = mysqli_query($db,"select * from bodyrepairreport;"); 
        echo "<table>
        <tr>
        
        <th colspan='8'>Body Repair Report from <br>(" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr><tr>
       <th>&emsp;Bus ID&emsp;</th>
       <th>&emsp;Springs Cost&emsp; <br> (in &#8377;)</th>
       <th>&emsp;Paints Cost&emsp; <br> (in &#8377;)</th>
       <th>&emsp;Glass work Cost&emsp; <br> (in &#8377;)</th>
       <th>&emsp;Seats Cost&emsp; <br> (in &#8377;)</th>
       <th>&emsp;Tyres Cost&emsp; <br> (in &#8377;)</th>
       <th>&emsp;Stickering Cost&emsp; <br> (in &#8377;)</th>
       <th>&emsp;Total&emsp; <br> (in &#8377;)</th>
        
        </tr> ";
    
        while($row= mysqli_fetch_array($query3)){
            echo "<tr>";
              echo "<td>" . $row['busid'] . "</td>";
              echo "<td>" . $row['SpringCost'] . "</td>";
              echo "<td>" . $row['PaintCost'] . "</td>";
              echo "<td>" . $row['GlassworkCost'] . "</td>";
              echo "<td>" . $row['SeatsCost'] . "</td>";
              echo "<td>" . $row['TyresCost'] . "</td>";
              echo "<td>" . $row['StickeringCost'] . "</td>";
              echo "<td>" . $row['Total'] . "</td>";
              echo "</tr>";
        }
        echo "</table>";
    }
    $query4 = mysqli_query($db,"delete from bodyrepairreport;");
    }
    
    ?>
</section>
</body>
</html>