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

$query = mysqli_query($db,"INSERT INTO `monthlyreport` select id , COALESCE(Diesel,0) Diesel , COALESCE(Coolent,0)+ COALESCE(GearOil,0)+ COALESCE(StearingOil,0)+COALESCE(Greasing,0) Lubricants , COALESCE(Insurance,0) Insurance , COALESCE(PTaxes,0)+COALESCE(QTaxes,0) Taxes , COALESCE(Battery,0)+COALESCE(SelfMotorAndDinamo,0) ElectricalRepairs , COALESCE(EngineRepairs,0) EngineRepairs , COALESCE(SpringCost,0)+COALESCE(PaintCost,0)+COALESCE(GlassworkCost,0)+COALESCE(SeatsCost,0)+COALESCE(TyresCost,0)+COALESCE(StickeringCost,0) BodyRepairs , COALESCE(Others,0) Others ,
COALESCE(Diesel,0)+  COALESCE(Coolent,0)+ COALESCE(GearOil,0)+ COALESCE(StearingOil,0)+COALESCE(Greasing,0)+COALESCE(Insurance,0)+COALESCE(PTaxes,0)+COALESCE(QTaxes,0)+COALESCE(Battery,0)+COALESCE(SelfMotorAndDinamo,0)+COALESCE(EngineRepairs,0)+COALESCE(SpringCost,0)+COALESCE(PaintCost,0)+COALESCE(GlassworkCost,0)+COALESCE(SeatsCost,0)+COALESCE(TyresCost,0)+COALESCE(StickeringCost,0)+COALESCE(Others,0) Total from(
select m1.busid id , (select sum(d.price) from diesel d WHERE d.date BETWEEN '$fromdate' and '$todate' and d.busid=m1.busid GROUP by d.busid ) Diesel ,  (select sum(c.tcost) from coolent c where  c.dates BETWEEN '$fromdate' and '$todate' and c.id=m1.busid GROUP by c.id) Coolent , (select sum(g.tcost) from gearoil g where g.dates BETWEEN
         '$fromdate' and '$todate' and g.id=m1.busid GROUP by g.id   ) GearOil , (select sum(st.tcost) from stearingoil st WHERE st.dates BETWEEN '$fromdate' and '$todate' and st.id=m1.busid GROUP by st.id) StearingOil , ( select sum(gr.tcost) from greasing gr where gr.dates BETWEEN '$fromdate' and '$todate' and gr.id=m1.busid GROUP by gr.id) Greasing , (select sum(i.totalprem) from insurance i where i.fromdate BETWEEN '$fromdate' and '$todate' and i.id=m1.busid GROUP by i.id ) Insurance ,  (select sum(COALESCE(p.quartertax,0)+COALESCE(p.fixness,0)+COALESCE(p.eibalteration,0)+COALESCE(p.termination,0)+COALESCE(p.rtacon,0)+COALESCE(p.rtapegreen,0)+COALESCE(p.tollway,0)) from periodicaltaxes p where p.FromDate BETWEEN '$fromdate' and '$todate' and p.busid=m1.busid GROUP by p.busid) PTaxes   , (select sum(COALESCE(q.quartertax,0)+COALESCE(q.fixness,0)+COALESCE(q.eibalteration,0)+COALESCE(q.termination,0)+COALESCE(q.rtacon,0)+COALESCE(q.rtapegreen,0)+COALESCE(q.tollway,0)) from quarterlytax q WHERE q.FromDate BETWEEN '$fromdate' and '$todate' and q.busid=m1.busid GROUP by q.busid) Qtaxes ,  (select sum(b.total) from  battery b where b.from BETWEEN '$fromdate' and '$todate' and b.busid=m1.busid GROUP by b.busid) Battery ,
        (select sum(s.cost)  from SelfMotorAndDinamo s  where s.fromdate BETWEEN '$fromdate' and '$todate' and s.busid=m1.busid GROUP by s.busid) SelfMotorAndDinamo ,  (select sum(e.cost) from enginerepairs e where e.fromdate between '$fromdate' and '$todate' and e.busid=m1.busid GROUP by e.busid) EngineRepairs , (SELECT sum(s.totvalue) from springs s where s.fromdate BETWEEN '$fromdate' and '$todate' and s.busid=m1.busid GROUP by s.busid) SpringCost , ( select sum(p.TotalValue) from paints p where p.fromdate BETWEEN '$fromdate' and '$todate' and p.busid=m1.busid GROUP by p.busid) PaintCost , (select sum(g.cost) from glasswork g WHERE g.fromdate BETWEEN '$fromdate' and '$todate' and g.busid=m1.busid GROUP by g.busid ) GlassworkCost , (select sum(se.cost) from seats se where se.fromdate BETWEEN '$fromdate' and '$todate' and se.busid=m1.busid GROUP by se.busid 
           ) SeatsCost ,(select sum(t.TotalValue) from tyres t where t.fromdate BETWEEN '$fromdate' and '$todate' and t.busid=m1.busid GROUP by t.busid) TyresCost , (select sum(st.cost) from stickering st where st.fromdate BETWEEN '$fromdate' and '$todate' and st.busid=m1.busid GROUP by st.busid) StickeringCost , (select sum(o.cost) from others o where o.fromdate BETWEEN '$fromdate' and '$todate' and o.busid=m1.busid GROUP by o.busid ) Others from masterdemo m1 ) as q1; ");  
$query2= mysqli_query($db,"INSERT INTO `monthlyreport` select 'Total' as busid , sum(Diesel), sum(Lubricants) ,sum(Insurance) , sum(Taxes) ,sum(ElectricalRepairs) , sum(EngineRepairs) , sum(BodyRepairs) , sum(Others) ,sum(Total) from `monthlyreport` ");
header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=\"MonthlyReport.xls\"");
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
$fields = array('&emsp;Bus Id&emsp; <br> <br> &emsp;','&emsp;Diesel&emsp; <br> (in &#8377;)','&emsp;Lubricants&emsp; <br> (in &#8377;)','&emsp;Insurance&emsp; <br> (in &#8377;)','&emsp;Taxes&emsp; <br> (in &#8377;)','&emsp;Electrical Repairs&emsp; <br> (in &#8377;)','&emsp;Engine Repairs &emsp;<br> (in &#8377;)','&emsp;Body Repairs&emsp; <br> (in &#8377;)','&emsp;Others&emsp; <br> (in &#8377;)', '&emsp;TOTAL &emsp;<br> (in &#8377;)'); 
 
$excelData = "<table border=2px solid black;><tr><td colspan='10' align='center'><b>Monthly Repair Report ( $fromdate - $todate )</b></td></tr>\n";
// Display column names as first row 
$excelData .= "<tr><td  ><b>" . implode("</b></td><td  align='center'><b>", array_values($fields)) . "</b></td></tr>\n";
 
// Fetch records from database 
$query = mysqli_query($db,"select * from monthlyreport;"); 
if($query->num_rows > 0){ 
    // Output each row of the data 
    while($row = $query->fetch_assoc()){  
        $lineData = array($row['busid'], $row['Diesel'],$row['Lubricants'], $row['Insurance'],$row['Taxes'],$row['ElectricalRepairs'], $row['EngineRepairs'],$row['BodyRepairs'], $row['Others'],  $row['Total']); 
        array_walk($lineData, 'filterData'); 
        $excelData .= "<tr><td>" . implode("</td><td>", array_values($lineData)) . "</td></tr>\n"; 
    } 
}else{ 
    $excelData .= "<tr><td colspan='10'>No records found...</td></tr>\n"; 
}  
$excelData .= "</table>";
echo $excelData;
// Headers for download 
// Render excel data 
$query = mysqli_query($db,"delete from monthlyreport;");
exit;

ob_end_flush(); 

}
?>

<html>
    <head>
    <title>MONTHLY REPORT</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="reports.css">
    <style>
#toalign{
    display:flex;
}
        </style>
</head>
    <body>
    <header>
    <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">  
    <h1>MONTHLY REPORT</h1> 
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
<button class="btn" type="submit"  name="display">DISPLAY</button>
<button class="btn" type="submit" name="export" >EXPORT</button>
</a>
</div>
</form>

<?php
if(isset($_POST['display']))
{
$fromdate=$_POST["fromdate"];
$todate=$_POST["todate"];

$conn=new mysqli("localhost","root","","transport");
  if($conn->connect_error){
      die("connection failed");
  }

else{
    $fromdate=$_POST["fromdate"];
    $todate=$_POST["todate"];
    
    $query = mysqli_query($conn,"INSERT INTO `monthlyreport` select id , COALESCE(Diesel,0) Diesel , COALESCE(Coolent,0)+ COALESCE(GearOil,0)+ COALESCE(StearingOil,0)+COALESCE(Greasing,0) Lubricants , COALESCE(Insurance,0) Insurance , COALESCE(PTaxes,0)+COALESCE(QTaxes,0) Taxes , COALESCE(Battery,0)+COALESCE(SelfMotorAndDinamo,0) ElectricalRepairs , COALESCE(EngineRepairs,0) EngineRepairs , COALESCE(SpringCost,0)+COALESCE(PaintCost,0)+COALESCE(GlassworkCost,0)+COALESCE(SeatsCost,0)+COALESCE(TyresCost,0)+COALESCE(StickeringCost,0) BodyRepairs , COALESCE(Others,0) Others ,
    COALESCE(Diesel,0)+  COALESCE(Coolent,0)+ COALESCE(GearOil,0)+ COALESCE(StearingOil,0)+COALESCE(Greasing,0)+COALESCE(Insurance,0)+COALESCE(PTaxes,0)+COALESCE(QTaxes,0)+COALESCE(Battery,0)+COALESCE(SelfMotorAndDinamo,0)+COALESCE(EngineRepairs,0)+COALESCE(SpringCost,0)+COALESCE(PaintCost,0)+COALESCE(GlassworkCost,0)+COALESCE(SeatsCost,0)+COALESCE(TyresCost,0)+COALESCE(StickeringCost,0)+COALESCE(Others,0) Total from(
    select m1.busid id , (select sum(d.price) from diesel d WHERE d.date BETWEEN '$fromdate' and '$todate' and d.busid=m1.busid GROUP by d.busid ) Diesel ,  (select sum(c.tcost) from coolent c where  c.dates BETWEEN '$fromdate' and '$todate' and c.id=m1.busid GROUP by c.id) Coolent , (select sum(g.tcost) from gearoil g where g.dates BETWEEN
             '$fromdate' and '$todate' and g.id=m1.busid GROUP by g.id   ) GearOil , (select sum(st.tcost) from stearingoil st WHERE st.dates BETWEEN '$fromdate' and '$todate' and st.id=m1.busid GROUP by st.id) StearingOil , ( select sum(gr.tcost) from greasing gr where gr.dates BETWEEN '$fromdate' and '$todate' and gr.id=m1.busid GROUP by gr.id) Greasing , (select sum(i.totalprem) from insurance i where i.fromdate BETWEEN '$fromdate' and '$todate' and i.id=m1.busid GROUP by i.id ) Insurance ,  (select sum(COALESCE(p.quartertax,0)+COALESCE(p.fixness,0)+COALESCE(p.eibalteration,0)+COALESCE(p.termination,0)+COALESCE(p.rtacon,0)+COALESCE(p.rtapegreen,0)+COALESCE(p.tollway,0)) from periodicaltaxes p where p.FromDate BETWEEN '$fromdate' and '$todate' and p.busid=m1.busid GROUP by p.busid) PTaxes   , (select sum(COALESCE(q.quartertax,0)+COALESCE(q.fixness,0)+COALESCE(q.eibalteration,0)+COALESCE(q.termination,0)+COALESCE(q.rtacon,0)+COALESCE(q.rtapegreen,0)+COALESCE(q.tollway,0)) from quarterlytax q WHERE q.FromDate BETWEEN '$fromdate' and '$todate' and q.busid=m1.busid GROUP by q.busid) Qtaxes ,  (select sum(b.total) from  battery b where b.from BETWEEN '$fromdate' and '$todate' and b.busid=m1.busid GROUP by b.busid) Battery ,
            (select sum(s.cost)  from SelfMotorAndDinamo s  where s.fromdate BETWEEN '$fromdate' and '$todate' and s.busid=m1.busid GROUP by s.busid) SelfMotorAndDinamo ,  (select sum(e.cost) from enginerepairs e where e.fromdate between '$fromdate' and '$todate' and e.busid=m1.busid GROUP by e.busid) EngineRepairs , (SELECT sum(s.totvalue) from springs s where s.fromdate BETWEEN '$fromdate' and '$todate' and s.busid=m1.busid GROUP by s.busid) SpringCost , ( select sum(p.TotalValue) from paints p where p.fromdate BETWEEN '$fromdate' and '$todate' and p.busid=m1.busid GROUP by p.busid) PaintCost , (select sum(g.cost) from glasswork g WHERE g.fromdate BETWEEN '$fromdate' and '$todate' and g.busid=m1.busid GROUP by g.busid ) GlassworkCost , (select sum(se.cost) from seats se where se.fromdate BETWEEN '$fromdate' and '$todate' and se.busid=m1.busid GROUP by se.busid 
               ) SeatsCost ,(select sum(t.TotalValue) from tyres t where t.fromdate BETWEEN '$fromdate' and '$todate' and t.busid=m1.busid GROUP by t.busid) TyresCost , (select sum(st.cost) from stickering st where st.fromdate BETWEEN '$fromdate' and '$todate' and st.busid=m1.busid GROUP by st.busid) StickeringCost , (select sum(o.cost) from others o where o.fromdate BETWEEN '$fromdate' and '$todate' and o.busid=m1.busid GROUP by o.busid ) Others from masterdemo m1 ) as q1; ");  

$query2= mysqli_query($conn,"INSERT INTO `monthlyreport` select 'Total' as busid , sum(Diesel), sum(Lubricants) ,sum(Insurance) , sum(Taxes) ,sum(ElectricalRepairs) , sum(EngineRepairs) , sum(BodyRepairs) , sum(Others) ,sum(Total) from `monthlyreport` ");

$query3 = mysqli_query($conn,"select * from monthlyreport;"); 
    
    echo "<table border='2' style='border: 2px solid black; color:black; background-color:white;'>
    <tr>
        <th colspan='10'>Monthly Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th>
    </tr>
    <tr>
        <th>BUSID</th>
        <th>&emsp;Diesel&emsp; <br> (in &#8377;)</th>
        <th>&emsp;Lubricants&emsp; <br> (in &#8377;)</th>
        <th>&emsp;Insurance&emsp; <br> (in &#8377;)</th>
        <th>&emsp;Taxes&emsp; <br> (in &#8377;)</th>
        <th>&emsp;Electrical Repairs&emsp; <br> (in &#8377;)</th>
        <th>&emsp;Engine Repairs &emsp;<br> (in &#8377;)</th>
        <th>&emsp;Body Repairs&emsp; <br> (in &#8377;)</th> 
        <th>&emsp;Others&emsp; <br> (in &#8377;)</th>
        <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
    </tr>";
    while($row= mysqli_fetch_array($query3)){
        echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>";
          echo "<td>" . $row['Diesel'] . "</td>";
          echo "<td>" . $row['Lubricants'] . "</td>";
          echo "<td>" . $row['Insurance'] . "</td>";
          echo "<td>" . $row['Taxes'] . "</td>";
          echo "<td>" . $row['ElectricalRepairs'] . "</td>"; 
          echo "<td>" . $row['EngineRepairs'] . "</td>"; 
          echo "<td>" . $row['BodyRepairs'] . "</td>"; 
          echo "<td>" . $row['Others'] . "</td>"; 
          echo "<td>" . $row['Total'] . "</td>";
          echo "</tr>";
    }
    echo "</table>";
}
$query4 = mysqli_query($conn,"delete from monthlyreport;");
}

?>
</body>
</html>