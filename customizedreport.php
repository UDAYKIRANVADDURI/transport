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
$busid=$_POST["busid"];
error_reporting(E_ERROR | E_PARSE);
if(!$_POST["Report"]){
  echo '<script> alert("Please select options !!!");  </script>';
}
else{
$opt=$_POST["Report"];
$count=0;
foreach ($opt as $item){ 
    $count+=1;
}
 
// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

$query3 = mysqli_query($db,"INSERT INTO `monthlyreport` select id , COALESCE(Diesel,0) Diesel , COALESCE(Coolent,0)+ COALESCE(GearOil,0)+ COALESCE(StearingOil,0)+COALESCE(Greasing,0) Lubricants , COALESCE(Insurance,0) Insurance , COALESCE(PTaxes,0)+COALESCE(QTaxes,0) Taxes , COALESCE(Battery,0)+COALESCE(SelfMotorAndDinamo,0) ElectricalRepairs , COALESCE(EngineRepairs,0) EngineRepairs , COALESCE(SpringCost,0)+COALESCE(PaintCost,0)+COALESCE(GlassworkCost,0)+COALESCE(SeatsCost,0)+COALESCE(TyresCost,0)+COALESCE(StickeringCost,0) BodyRepairs , COALESCE(Others,0) Others ,
COALESCE(Diesel,0)+  COALESCE(Coolent,0)+ COALESCE(GearOil,0)+ COALESCE(StearingOil,0)+COALESCE(Greasing,0)+COALESCE(Insurance,0)+COALESCE(PTaxes,0)+COALESCE(QTaxes,0)+COALESCE(Battery,0)+COALESCE(SelfMotorAndDinamo,0)+COALESCE(EngineRepairs,0)+COALESCE(SpringCost,0)+COALESCE(PaintCost,0)+COALESCE(GlassworkCost,0)+COALESCE(SeatsCost,0)+COALESCE(TyresCost,0)+COALESCE(StickeringCost,0)+COALESCE(Others,0) Total from(
select m1.busid id , (select sum(d.price) from diesel d WHERE d.date BETWEEN '$fromdate' and '$todate' and d.busid=m1.busid GROUP by d.busid ) Diesel ,  (select sum(c.tcost) from coolent c where  c.dates BETWEEN '$fromdate' and '$todate' and c.id=m1.busid GROUP by c.id) Coolent , (select sum(g.tcost) from gearoil g where g.dates BETWEEN
         '$fromdate' and '$todate' and g.id=m1.busid GROUP by g.id   ) GearOil , (select sum(st.tcost) from stearingoil st WHERE st.dates BETWEEN '$fromdate' and '$todate' and st.id=m1.busid GROUP by st.id) StearingOil , ( select sum(gr.tcost) from greasing gr where gr.dates BETWEEN '$fromdate' and '$todate' and gr.id=m1.busid GROUP by gr.id) Greasing , (select sum(i.totalprem) from insurance i where i.fromdate BETWEEN '$fromdate' and '$todate' and i.id=m1.busid GROUP by i.id ) Insurance ,  (select sum(COALESCE(p.quartertax,0)+COALESCE(p.fixness,0)+COALESCE(p.eibalteration,0)+COALESCE(p.termination,0)+COALESCE(p.rtacon,0)+COALESCE(p.rtapegreen,0)+COALESCE(p.tollway,0)) from periodicaltaxes p where p.FromDate BETWEEN '$fromdate' and '$todate' and p.busid=m1.busid GROUP by p.busid) PTaxes   , (select sum(COALESCE(q.quartertax,0)+COALESCE(q.fixness,0)+COALESCE(q.eibalteration,0)+COALESCE(q.termination,0)+COALESCE(q.rtacon,0)+COALESCE(q.rtapegreen,0)+COALESCE(q.tollway,0)) from quarterlytax q WHERE q.FromDate BETWEEN '$fromdate' and '$todate' and q.busid=m1.busid GROUP by q.busid) Qtaxes ,  (select sum(b.total) from  battery b where b.from BETWEEN '$fromdate' and '$todate' and b.busid=m1.busid GROUP by b.busid) Battery ,
        (select sum(s.cost)  from SelfMotorAndDinamo s  where s.fromdate BETWEEN '$fromdate' and '$todate' and s.busid=m1.busid GROUP by s.busid) SelfMotorAndDinamo ,  (select sum(e.cost) from enginerepairs e where e.fromdate between '$fromdate' and '$todate' and e.busid=m1.busid GROUP by e.busid) EngineRepairs , (SELECT sum(s.totvalue) from springs s where s.fromdate BETWEEN '$fromdate' and '$todate' and s.busid=m1.busid GROUP by s.busid) SpringCost , ( select sum(p.TotalValue) from paints p where p.fromdate BETWEEN '$fromdate' and '$todate' and p.busid=m1.busid GROUP by p.busid) PaintCost , (select sum(g.cost) from glasswork g WHERE g.fromdate BETWEEN '$fromdate' and '$todate' and g.busid=m1.busid GROUP by g.busid ) GlassworkCost , (select sum(se.cost) from seats se where se.fromdate BETWEEN '$fromdate' and '$todate' and se.busid=m1.busid GROUP by se.busid 
           ) SeatsCost ,(select sum(t.TotalValue) from tyres t where t.fromdate BETWEEN '$fromdate' and '$todate' and t.busid=m1.busid GROUP by t.busid) TyresCost , (select sum(st.cost) from stickering st where st.fromdate BETWEEN '$fromdate' and '$todate' and st.busid=m1.busid GROUP by st.busid) StickeringCost , (select sum(o.cost) from others o where o.fromdate BETWEEN '$fromdate' and '$todate' and o.busid=m1.busid GROUP by o.busid ) Others from masterdemo m1 ) as q1;"); 
$query2= mysqli_query($db,"INSERT INTO `monthlyreport` select 'Total' as busid , sum(Diesel), sum(Lubricants) ,sum(Insurance) , sum(Taxes) ,sum(ElectricalRepairs) , sum(EngineRepairs) , sum(BodyRepairs) , sum(Others) ,sum(Total) from `monthlyreport` ");
 
header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=\"cummulativeReport.xls\"");
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
$fields = array("&emsp;Bus ID&emsp;<br> <br> &emsp;"); 
foreach($opt as $item){
  array_push($fields,"&emsp; $item &emsp; <br> (in &#8377;)");
}


array_push($fields,"&emsp;Total&emsp;<br> <br> &emsp;");

// Display column names as first row 
$excelData = "<table border=2px solid black;><tr><td colspan='10' align='center'><b>Cummulative Report ( $fromdate - $todate )</b></td></tr>\n";
$excelData .= "<tr><td  ><b>" . implode("</b></td><td  align='center'><b>", array_values($fields)) . "</b></td></tr>\n"; 
 

// Fetch records from database 
if ($count==1){
  if($busid!="")
  {
      $query=mysqli_query($db,"select busid, $opt[0],$opt[0] Total from (select busid , $opt[0] from `monthlyreport` where busid='$busid') as q1;");
  }
  else
  {
      $query=mysqli_query($db,"select busid, $opt[0],$opt[0] Total from (select busid , $opt[0] from `monthlyreport`) as q1;");
  }
}

if ($count==2){
  if($busid!="")
  {
  $query=mysqli_query($db," select busid, $opt[0] , $opt[1] , $opt[0]+$opt[1] Total from (select busid , $opt[0] , $opt[1] from `monthlyreport` where busid='$busid') as q1;");
  }
  else
  {
      $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[0]+$opt[1] Total from (select busid , $opt[0] , $opt[1] from `monthlyreport`) as q1;");
  }
}
if ($count==3){
  if($busid!="")
  {
  $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2], $opt[0]+$opt[1]+$opt[2] Total from (select busid , $opt[0] , $opt[1] ,$opt[2] from `monthlyreport` where busid='$busid') as q1;");
  }
 else
 {
  $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2], $opt[0]+$opt[1]+$opt[2] Total from (select busid , $opt[0] , $opt[1] ,$opt[2] from `monthlyreport`) as q1;");
 }
}
if ($count==4){
  if($busid!="")
  {
      $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[0]+$opt[1]+$opt[2]+$opt[3] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] from `monthlyreport` where busid='$busid') as q1;");
  }
else{
  $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[0]+$opt[1]+$opt[2]+$opt[3] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] from `monthlyreport`) as q1;");
  }
}
if ($count==5){
  if($busid!="")
  {
  $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4] from `monthlyreport` where busid='$busid') as q1;");
  }
else
{
  $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4] from `monthlyreport`) as q1;");
}
}
if ($count==6){
  if($busid!="")
  {
  $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[5],$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4]+$opt[5] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4], $opt[5] from `monthlyreport` where busid='$busid') as q1;");
  }

else
{
  $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[5],$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4]+$opt[5] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4], $opt[5] from `monthlyreport`) as q1");

}
}

if ($count==7){
if($busid!="")
{
$query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[5],$opt[6] ,$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4]+$opt[5]+$opt[6] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4], $opt[5] , $opt[6] from `monthlyreport` where busid='$busid') as q1;");

}

else
{
$query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[5],$opt[6] ,$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4]+$opt[5]+$opt[6] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4], $opt[5] , $opt[6] from `monthlyreport`) as q1");
}
}

if ($count==8){
if($busid!="")
{
$query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[5],$opt[6] , $opt[7] ,$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4]+$opt[5]+$opt[6]+$opt[7] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4], $opt[5] , $opt[6] , $opt[7] from `monthlyreport` where busid='$busid') as q1;");
}

else
{
$query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[5],$opt[6] , $opt[7] ,$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4]+$opt[5]+$opt[6]+$opt[7] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4], $opt[5] , $opt[6] , $opt[7] from `monthlyreport`) as q1");
}
} 
//$query = mysqli_query($db,"select * from monthlyreport;"); 
if($query->num_rows > 0){ 
    // Output each row of the data 
    while($row = $query->fetch_assoc()){  
        $lineData = array($row['busid']);
        foreach($opt as $i){
            $a=$row[$i]; 
          array_push($lineData,$a);
        }
        array_push($lineData,$row['Total']);
        array_walk($lineData, 'filterData'); 
        $excelData .= "<tr><td>" . implode("</td><td>", array_values($lineData)) . "</td></tr>\n";  
    } 
}else{ 
  $excelData .= "<tr><td colspan='10'>No records found...</td></tr>\n"; 
}  
echo $excelData;
// Headers for download 
// Render excel data 
$query = mysqli_query($db,"delete from monthlyreport;");
exit;

ob_end_flush(); 

}

}
?>


<html>
    <head>
    <title>CUMMULATIVE REPORT</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
        <style>
            div.labels{ 
  border-radius:3%;
  background-color: white;
  text-align: center;
  width:auto;
  height:auto;
  margin:auto;
  top:10vh;
  bottom:0;
  right:0;
  left:0;
  margin-left:10%;
  margin-right:10%;

  border-width:medium;
  padding-top:5%;
  padding-bottom:5%;
  box-shadow: 1px 9px 30px 9px rgba(0,0,0,0.3);
}
       
            input[type=date],input[type=text],input[type=checkbox]{
                display: inline-block;
                text-align: left;
                width: auto;
	            height: auto;
                font-size: large;
                color: black;
            }
            label{
                display: inline-block;
                text-align: left;
                width: auto;
	            height: auto;
                font-size: large;
                color: black;
            }

            header{
                text-align:center;
            }
            div.container{
                display: grid;
                text-align:left;
                grid-template-columns: auto auto auto auto;
                margin:auto;
            }
            .items{
                padding:5px;
            }
            table{
              border:2px solid #B5A4A3;
              margin:auto;
              margin-bottom:auto;
              text-align:center;
            }
            th{
              border:1px solid #B5A4A3;
        background-color:#B5A4A3;
        color:white;
        padding:10px;
      }   
      td{
        background-color:white;
        padding:10px;
      } 
            body{
              background-color:aliceblue;
            }
            tr td:nth-child(0n+1){
              color:red;
            }
            tr td:nth-child(1n+2){
              color:black;
            }
            span{
              display:flex;
            }
            h1{
              margin:auto;
              color:darkblue;
              margin-bottom:3%;
            }
            #btn1,#btn2{
              margin-left:auto;
            }
            #btn1:hover {
    color: white;
    background-color: blue;
}
#btn2:hover {
    color: white;
    background-color: green;
}
b{
  font-size:150%;
}
button{
  height:4%;
  width:6%;
  font-size:auto;
  margin-top:5%;
}
input[type=checkbox]:hover{
  color:orange;
}
        </style>
    </head>
    <body>
        <header>
        <span> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1>CUMMULATIVE REPORT</h1>
        </header>
        <script>
    function back() {
        location.href = "Transport.php";
    }
    </script>
<form action="" method="POST">
<div class="labels">
<label style="color:red;margin-right:-28%"> <b>Bus id :</b></label> <input type="text" style="margin-left:28.5%;" name="busid"><br><br>
<label for="from" ><b style="color:violet;margin-right:-5%;">From Date: </b></label>&emsp;&emsp;&emsp;&emsp;
<input type="date" name="fromdate"  min="2007-01-01" max="2050-12-31" required  value="<?php echo $_POST['fromdate'] ?? ''; ?>">  <br><br>
<label for="to"><b style="color:violet;">To Date:</b></label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
<input type="date" name="todate"  min="2007-01-01"  max="2050-12-31" required value="<?php echo $_POST['todate'] ?? ''; ?>"><br><br>
<br>
<div class="container">
<div class="items"></div>
<div class="items"><label for="selectall"><input type="checkbox" id="selectall" name="selectall" value="selectall" onchange='s(this);'><b style="color:green;">Select All </b>&emsp;</label></div>
<div class="items"><label for="dselect"><input type="checkbox" value="deselectall" id="dselect" onchange='d(this);'><b style="color:brown;">Deselect All</b><br></div>
<div class="items"></div>
<div class="items"><label for="diesel"><input type="checkbox" name="Report[]" id="diesel" value="Diesel"><b>Diesel</b></label> &emsp;</label></div>
<div class="items"><label for="lubricants"><input type="checkbox" name="Report[]" value="Lubricants" id="lubricants"><b>Lubricants</b>&emsp;</label></div>
<div class="items"><label for="insurance"><input type="checkbox" name="Report[]" value="Insurance" id="insurance"><b>Insurance </b>&emsp;</label></div>
<div class="items"><label for="taxes"><input type="checkbox" name="Report[]" value="Taxes" id="taxes"><b>Taxes</b><br></label></div>
<div class="items"><label for="elrepairs"><input type="checkbox" name="Report[]" value="ElectricalRepairs" id="elrepairs"><b>ElectricalRepairs </b>&emsp;</label></div>
<div class="items"><label for="enrepairs"><input type="checkbox" name="Report[]" id="enrepairs" value="EngineRepairs"><b>EngineRepairs</b> &emsp;</label></div>
<div class="items"><label for="bodyrepairs"><input type="checkbox" name="Report[]" value="BodyRepairs" id="bodyrepairs"><b>BodyRepairs </b>&emsp;</label></div>
<div class="items"><label for="others"><input type="checkbox" name="Report[]" id="others" value="Others"><b>Others</b><br><br></label></div>
</div>
<button type="submit" id="btn1" value="Display" name="display"/>Display</button>&emsp;&emsp;&emsp;&emsp;
<button type="submit" id="btn2" value="Export" name="export"/>Export</button>
</div>
</form><br><br>
<script>
  function s(c)
  {
    if(c.checked){ 
      var e=document.getElementsByName("Report[]");
      for(var i=0;i<e.length;i++){
        e[i].checked=true;
      }
      var d=document.getElementsByName("deselectall");
      d[0].checked=false;
    }
  }
  function d(c)
  {
    if(c.checked){ 
      var e=document.getElementsByName("Report[]");
      for(var i=0;i<e.length;i++){
        e[i].checked=false;
      }
      var e1=document.getElementsByName("selectall");
      e1[0].checked=false;
    }
  }
</script>
</body>
</html>
<?php
if(isset($_POST['display'])){
    $fromdate=$_POST["fromdate"];
    $todate=$_POST["todate"];
    error_reporting(E_ERROR | E_PARSE);
    if(!$_POST["Report"]){
      echo '<script> alert("Please select options !!!");  </script>';
    }
    else{
$opt=$_POST['Report'];
$count=0;
foreach ($opt as $item){ 
    $count+=1;
}
$diesel="hello"; 
$dbHost     = "localhost"; 
$dbUsername = "root"; 
$dbPassword = ""; 
$dbName     = "transport"; 
$fromdate=$_POST["fromdate"];
$todate=$_POST["todate"];
$busid=$_POST["busid"];
 
// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

$query = mysqli_query($db,"INSERT INTO `monthlyreport` select id , COALESCE(Diesel,0) Diesel , COALESCE(Coolent,0)+ COALESCE(GearOil,0)+ COALESCE(StearingOil,0)+COALESCE(Greasing,0) Lubricants , COALESCE(Insurance,0) Insurance , COALESCE(PTaxes,0)+COALESCE(QTaxes,0) Taxes , COALESCE(Battery,0)+COALESCE(SelfMotorAndDinamo,0) ElectricalRepairs , COALESCE(EngineRepairs,0) EngineRepairs , COALESCE(SpringCost,0)+COALESCE(PaintCost,0)+COALESCE(GlassworkCost,0)+COALESCE(SeatsCost,0)+COALESCE(TyresCost,0)+COALESCE(StickeringCost,0) BodyRepairs , COALESCE(Others,0) Others ,
COALESCE(Diesel,0)+  COALESCE(Coolent,0)+ COALESCE(GearOil,0)+ COALESCE(StearingOil,0)+COALESCE(Greasing,0)+COALESCE(Insurance,0)+COALESCE(PTaxes,0)+COALESCE(QTaxes,0)+COALESCE(Battery,0)+COALESCE(SelfMotorAndDinamo,0)+COALESCE(EngineRepairs,0)+COALESCE(SpringCost,0)+COALESCE(PaintCost,0)+COALESCE(GlassworkCost,0)+COALESCE(SeatsCost,0)+COALESCE(TyresCost,0)+COALESCE(StickeringCost,0)+COALESCE(Others,0) Total from(
select m1.busid id , (select sum(d.price) from diesel d WHERE d.date BETWEEN '$fromdate' and '$todate' and d.busid=m1.busid GROUP by d.busid ) Diesel ,  (select sum(c.tcost) from coolent c where  c.dates BETWEEN '$fromdate' and '$todate' and c.id=m1.busid GROUP by c.id) Coolent , (select sum(g.tcost) from gearoil g where g.dates BETWEEN
         '$fromdate' and '$todate' and g.id=m1.busid GROUP by g.id   ) GearOil , (select sum(st.tcost) from stearingoil st WHERE st.dates BETWEEN '$fromdate' and '$todate' and st.id=m1.busid GROUP by st.id) StearingOil , ( select sum(gr.tcost) from greasing gr where gr.dates BETWEEN '$fromdate' and '$todate' and gr.id=m1.busid GROUP by gr.id) Greasing , (select sum(i.totalprem) from insurance i where i.fromdate BETWEEN '$fromdate' and '$todate' and i.id=m1.busid GROUP by i.id ) Insurance ,  (select sum(COALESCE(p.quartertax,0)+COALESCE(p.fixness,0)+COALESCE(p.eibalteration,0)+COALESCE(p.termination,0)+COALESCE(p.rtacon,0)+COALESCE(p.rtapegreen,0)+COALESCE(p.tollway,0)) from periodicaltaxes p where p.FromDate BETWEEN '$fromdate' and '$todate' and p.busid=m1.busid GROUP by p.busid) PTaxes   , (select sum(COALESCE(q.quartertax,0)+COALESCE(q.fixness,0)+COALESCE(q.eibalteration,0)+COALESCE(q.termination,0)+COALESCE(q.rtacon,0)+COALESCE(q.rtapegreen,0) +COALESCE(q.tollway,0)) from quarterlytax q WHERE q.FromDate BETWEEN '$fromdate' and '$todate' and q.busid=m1.busid GROUP by q.busid) Qtaxes ,  (select sum(b.total) from  battery b where b.from BETWEEN '$fromdate' and '$todate' and b.busid=m1.busid GROUP by b.busid) Battery ,
        (select sum(s.cost)  from SelfMotorAndDinamo s  where s.fromdate BETWEEN '$fromdate' and '$todate' and s.busid=m1.busid GROUP by s.busid) SelfMotorAndDinamo ,  (select sum(e.cost) from enginerepairs e where e.fromdate between '$fromdate' and '$todate' and e.busid=m1.busid GROUP by e.busid) EngineRepairs , (SELECT sum(s.totvalue) from springs s where s.fromdate BETWEEN '$fromdate' and '$todate' and s.busid=m1.busid GROUP by s.busid) SpringCost , ( select sum(p.TotalValue) from paints p where p.fromdate BETWEEN '$fromdate' and '$todate' and p.busid=m1.busid GROUP by p.busid) PaintCost , (select sum(g.cost) from glasswork g WHERE g.fromdate BETWEEN '$fromdate' and '$todate' and g.busid=m1.busid GROUP by g.busid ) GlassworkCost , (select sum(se.cost) from seats se where se.fromdate BETWEEN '$fromdate' and '$todate' and se.busid=m1.busid GROUP by se.busid 
           ) SeatsCost ,(select sum(t.TotalValue) from tyres t where t.fromdate BETWEEN '$fromdate' and '$todate' and t.busid=m1.busid GROUP by t.busid) TyresCost , (select sum(st.cost) from stickering st where st.fromdate BETWEEN '$fromdate' and '$todate' and st.busid=m1.busid GROUP by st.busid) StickeringCost , (select sum(o.cost) from others o where o.fromdate BETWEEN '$fromdate' and '$todate' and o.busid=m1.busid GROUP by o.busid ) Others from masterdemo m1 ) as q1;");  
$query2= mysqli_query($db,"INSERT INTO `monthlyreport` select 'Total' as busid , sum(Diesel), sum(Lubricants) ,sum(Insurance) , sum(Taxes) ,sum(ElectricalRepairs) , sum(EngineRepairs) , sum(BodyRepairs) , sum(Others) ,sum(Total) from `monthlyreport` ");

if ($count==1){
    if($busid!="")
    {
        $query=mysqli_query($db,"select busid, $opt[0]  from (select busid , $opt[0] from `monthlyreport` where busid='$busid') as q1;");
        //  echo $query;
          echo "<table>
          <tr>
          <th colspan=3>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
          <tr>
          <th>Busid</th>
          <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
          </tr> ";
      
          while($row= mysqli_fetch_array($query)){
              echo "<tr>";
                echo "<td>" . $row['busid'] . "</td>";    
                echo "<td>" . $row[$opt[0]] . "</td>";  
                echo "</tr>";
          }
          echo "</table>";
    }
    else
    {
        $query=mysqli_query($db,"select busid, $opt[0]  from (select busid , $opt[0] from `monthlyreport`) as q1;");
        //  echo $query;
          echo "<table>
          <tr>
          <th colspan=3>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
          <tr>
          <th>Busid</th>
          <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
          </tr> ";
      
          while($row= mysqli_fetch_array($query)){
              echo "<tr>";
                echo "<td>" . $row['busid'] . "</td>"; 
                echo "<td>" . $row[$opt[0]] . "</td>"; 
                echo "</tr>";
          }
          echo "</table>";
}
}

if ($count==2){
    if($busid!="")
    {
    $query=mysqli_query($db," select busid, $opt[0] , $opt[1] , $opt[0]+$opt[1] Total from (select busid , $opt[0] , $opt[1] from `monthlyreport` where busid='$busid') as q1;");
  //  echo $query;
    echo "<table>
    <tr>
    <th colspan=4>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
    <tr>
    <th>Busid</th>
    <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th> 
    <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
    </tr> ";

    while($row= mysqli_fetch_array($query)){
        echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>"; 
          echo "<td>" . $row[$opt[0]] . "</td>"; 
          echo "<td>" . $row[$opt[1]] . "</td>"; 
          echo "<td>" . $row['Total'] . "</td>"; 
          echo "</tr>";
    }
    echo "</table>";
    }
    else
    {
        $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[0]+$opt[1] Total from (select busid , $opt[0] , $opt[1] from `monthlyreport`) as q1;");
  //  echo $query;
    echo "<table>
    <tr>
    <th colspan=4>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
    <tr>
    <th>Busid</th>
    <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
    <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
    </tr> ";

    while($row= mysqli_fetch_array($query)){
        echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>"; 
          echo "<td>" . $row[$opt[0]] . "</td>"; 
          echo "<td>" . $row[$opt[1]] . "</td>"; 
          echo "<td>" . $row['Total'] . "</td>";
          echo "</tr>";
    }
    echo "</table>";
        
    }
}
if ($count==3){
    if($busid!="")
    {
    $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2], $opt[0]+$opt[1]+$opt[2] Total from (select busid , $opt[0] , $opt[1] ,$opt[2] from `monthlyreport` where busid='$busid') as q1;");
  //  echo $query;
    echo "<table>
    <tr>
    <th colspan=5>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
    <tr>
    <th>Busid</th>
    <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[2] &emsp; <br> (in &#8377;)</th>
    <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
    </tr> ";

    while($row= mysqli_fetch_array($query)){
        echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>"; 
          echo "<td>" . $row[$opt[0]] . "</td>"; 
          echo "<td>" . $row[$opt[1]] . "</td>";
          echo "<td>" . $row[$opt[2]] . "</td>";
          echo "<td>" . $row['Total'] . "</td>"; 
          echo "</tr>";
    }
    echo "</table>";
}
else
{
    $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2], $opt[0]+$opt[1]+$opt[2] Total from (select busid , $opt[0] , $opt[1] ,$opt[2] from `monthlyreport`) as q1;");
  //  echo $query;
    echo "<table>
    <tr>
    <th colspan=5>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
    <tr>
    <th>Busid</th>
    <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[2] &emsp; <br> (in &#8377;)</th>
    <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
    </tr> ";

    while($row= mysqli_fetch_array($query)){
        echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>"; 
          echo "<td>" . $row[$opt[0]] . "</td>"; 
          echo "<td>" . $row[$opt[1]] . "</td>";
          echo "<td>" . $row[$opt[2]] . "</td>"; 
          echo "<td>" . $row['Total'] . "</td>"; 
          echo "</tr>";
    }
    echo "</table>";
}
}
if ($count==4){
    if($busid!="")
    {
        $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[0]+$opt[1]+$opt[2]+$opt[3] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] from `monthlyreport` where busid='$busid') as q1;");
  //  echo $query;
    echo "<table>
    <tr>
    <th colspan=6>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
    <tr>
    <th>Busid</th>
    <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[2] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[3] &emsp; <br> (in &#8377;)</th>
    <th> &emsp;TOTAL &emsp;<br> (in &#8377;)</th>
    </tr> ";

    while($row= mysqli_fetch_array($query)){
        echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>"; 
          echo "<td>" . $row[$opt[0]] . "</td>"; 
          echo "<td>" . $row[$opt[1]] . "</td>";
          echo "<td>" . $row[$opt[2]] . "</td>";
          echo "<td>" . $row[$opt[3]] . "</td>"; 
          echo "<td>" . $row['Total'] . "</td>";
          echo "</tr>";
    }
    echo "</table>";
}
else{
    $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[0]+$opt[1]+$opt[2]+$opt[3] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] from `monthlyreport`) as q1;");
  //  echo $query;
    echo "<table>
    <tr>
    <th colspan=6>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
    <tr>
    <th>Busid</th>
    <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[2] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[3] &emsp; <br> (in &#8377;)</th>
    <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
    </tr> ";

    while($row= mysqli_fetch_array($query)){
        echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>"; 
          echo "<td>" . $row[$opt[0]] . "</td>"; 
          echo "<td>" . $row[$opt[1]] . "</td>";
          echo "<td>" . $row[$opt[2]] . "</td>";
          echo "<td>" . $row[$opt[3]] . "</td>"; 
          echo "<td>" . $row['Total'] . "</td>";
          echo "</tr>";
    }
    echo "</table>";
}

}
if ($count==5){
    if($busid!="")
    {
    $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4] from `monthlyreport` where busid='$busid') as q1;");
  //  echo $query;
    echo "<table>
    <tr>
    <th colspan=7>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
    <tr>
    <th>Busid</th>
    <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[2] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[3] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[4] &emsp; <br> (in &#8377;)</th>
    <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
    </tr> ";

    while($row= mysqli_fetch_array($query)){
        echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>"; 
          echo "<td>" . $row[$opt[0]] . "</td>"; 
          echo "<td>" . $row[$opt[1]] . "</td>";
          echo "<td>" . $row[$opt[2]] . "</td>";
          echo "<td>" . $row[$opt[3]] . "</td>";
          echo "<td>" . $row[$opt[4]] . "</td>"; 
          echo "<td>" . $row['Total'] . "</td>"; 
          echo "</tr>";
    }
    echo "</table>";
}
else
{
    $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4] from `monthlyreport`) as q1;");
    //  echo $query;
      echo "<table>
      <tr>
      <th colspan=7>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
      <tr>
      <th>Busid</th>
      <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[2] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[3] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[4] &emsp; <br> (in &#8377;)</th>
      <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
      </tr> ";
  
      while($row= mysqli_fetch_array($query)){
          echo "<tr>";
            echo "<td>" . $row['busid'] . "</td>"; 
            echo "<td>" . $row[$opt[0]] . "</td>"; 
            echo "<td>" . $row[$opt[1]] . "</td>";
            echo "<td>" . $row[$opt[2]] . "</td>";
            echo "<td>" . $row[$opt[3]] . "</td>";
            echo "<td>" . $row[$opt[4]] . "</td>"; 
            echo "<td>" . $row['Total'] . "</td>"; 
            echo "</tr>";
      }
      echo "</table>"; 
}
}
if ($count==6){
    if($busid!="")
    {
    $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[5],$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4]+$opt[5] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4], $opt[5] from `monthlyreport` where busid='$busid') as q1;");
  //  echo $query;
    echo "<table>
    <tr>
    <th colspan=8>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
    <tr>
    <th>Busid</th>
    <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[2] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[3] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[4] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[5] &emsp; <br> (in &#8377;)</th>
    <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
    </tr> ";

    while($row= mysqli_fetch_array($query)){
        echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>"; 
          echo "<td>" . $row[$opt[0]] . "</td>"; 
          echo "<td>" . $row[$opt[1]] . "</td>";
          echo "<td>" . $row[$opt[2]] . "</td>";
          echo "<td>" . $row[$opt[3]] . "</td>";
          echo "<td>" . $row[$opt[4]] . "</td>"; 
          echo "<td>" . $row[$opt[5]] . "</td>";
          echo "<td>" . $row['Total'] . "</td>";
          echo "</tr>";
    }
    echo "</table>";

}

else
{
    $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[5],$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4]+$opt[5] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4], $opt[5] from `monthlyreport`) as q1");
  //  echo $query;
    echo "<table>
    <tr>
    <th colspan=8>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
    <tr>
    <th>Busid</th>
    <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[2] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[3] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[4] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[5] &emsp; <br> (in &#8377;)</th>
    <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
    </tr> ";

    while($row= mysqli_fetch_array($query)){
        echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>"; 
          echo "<td>" . $row[$opt[0]] . "</td>"; 
          echo "<td>" . $row[$opt[1]] . "</td>";
          echo "<td>" . $row[$opt[2]] . "</td>";
          echo "<td>" . $row[$opt[3]] . "</td>";
          echo "<td>" . $row[$opt[4]] . "</td>"; 
          echo "<td>" . $row[$opt[5]] . "</td>";
          echo "<td>" . $row['Total'] . "</td>";
          echo "</tr>";
    }
    echo "</table>";
}
}

if ($count==7){
  if($busid!="")
  {
  $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[5],$opt[6] ,$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4]+$opt[5]+$opt[6] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4], $opt[5] , $opt[6] from `monthlyreport` where busid='$busid') as q1;");
//  echo $query;
  echo "<table>
  <tr>
  <th colspan=9>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
  <tr>
  <th>Busid</th>
  <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[2] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[3] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[4] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[5] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[6] &emsp; <br> (in &#8377;)</th>
  <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
  </tr> ";

  while($row= mysqli_fetch_array($query)){
      echo "<tr>";
        echo "<td>" . $row['busid'] . "</td>"; 
        echo "<td>" . $row[$opt[0]] . "</td>"; 
        echo "<td>" . $row[$opt[1]] . "</td>";
        echo "<td>" . $row[$opt[2]] . "</td>";
        echo "<td>" . $row[$opt[3]] . "</td>";
        echo "<td>" . $row[$opt[4]] . "</td>"; 
        echo "<td>" . $row[$opt[5]] . "</td>";
        echo "<td>" . $row[$opt[6]] . "</td>";
        echo "<td>" . $row['Total'] . "</td>";
        echo "</tr>";
  }
  echo "</table>";

}

else
{
  $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[5],$opt[6] ,$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4]+$opt[5]+$opt[6] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4], $opt[5] , $opt[6] from `monthlyreport`) as q1");
//  echo $query;
  echo "<table>
  <tr>
  <th colspan=9>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
  <tr>
  <th>Busid</th>
  <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[2] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[3] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[4] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[5] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[6] &emsp; <br> (in &#8377;)</th>
  <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
  </tr> ";

  while($row= mysqli_fetch_array($query)){
      echo "<tr>";
        echo "<td>" . $row['busid'] . "</td>"; 
        echo "<td>" . $row[$opt[0]] . "</td>"; 
        echo "<td>" . $row[$opt[1]] . "</td>";
        echo "<td>" . $row[$opt[2]] . "</td>";
        echo "<td>" . $row[$opt[3]] . "</td>";
        echo "<td>" . $row[$opt[4]] . "</td>"; 
        echo "<td>" . $row[$opt[5]] . "</td>";
        echo "<td>" . $row[$opt[6]] . "</td>";
        echo "<td>" . $row['Total'] . "</td>";
        echo "</tr>";
  }
  echo "</table>";
}
}

if ($count==8){
  if($busid!="")
  {
  $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[5],$opt[6] , $opt[7] ,$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4]+$opt[5]+$opt[6]+$opt[7] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4], $opt[5] , $opt[6] , $opt[7] from `monthlyreport` where busid='$busid') as q1;");
//  echo $query;
  echo "<table>
  <tr>
  <th colspan=10>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
  <tr>
  <th>Busid</th>
  <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[2] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[3] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[4] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[5] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[6] &emsp; <br> (in &#8377;)</th>
    <th>&emsp; $opt[7] &emsp; <br> (in &#8377;)</th>
  <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
  </tr> ";

  while($row= mysqli_fetch_array($query)){
      echo "<tr>";
        echo "<td>" . $row['busid'] . "</td>"; 
        echo "<td>" . $row[$opt[0]] . "</td>"; 
        echo "<td>" . $row[$opt[1]] . "</td>";
        echo "<td>" . $row[$opt[2]] . "</td>";
        echo "<td>" . $row[$opt[3]] . "</td>";
        echo "<td>" . $row[$opt[4]] . "</td>"; 
        echo "<td>" . $row[$opt[5]] . "</td>";
        echo "<td>" . $row[$opt[6]] . "</td>";
        echo "<td>" . $row[$opt[7]] . "</td>";
        echo "<td>" . $row['Total'] . "</td>";
        echo "</tr>";
  }
  echo "</table>";

}

else
{
  $query=mysqli_query($db,"select busid, $opt[0] , $opt[1] , $opt[2],$opt[3], $opt[4] ,$opt[5],$opt[6] , $opt[7] ,$opt[0]+$opt[1]+$opt[2]+$opt[3]+$opt[4]+$opt[5]+$opt[6]+$opt[7] Total from (select busid , $opt[0] , $opt[1] ,$opt[2],$opt[3] ,$opt[4], $opt[5] , $opt[6] , $opt[7] from `monthlyreport`) as q1");
//  echo $query;
  echo "<table border='2' style='  color:black; background-color:white;'>
  <tr>
  <th colspan=10>Cummulative Report from (" . date("j-F-Y", strtotime($fromdate)) . " to " . date("j-F-Y", strtotime($todate)) . ")</th></tr>
  <tr>
  <th>Busid</th>
  <th>&emsp; $opt[0] &emsp; <br> (in &#8377;)</th>
  <th>&emsp; $opt[1] &emsp; <br> (in &#8377;)</th>
  <th>&emsp; $opt[2] &emsp; <br> (in &#8377;)</th>
  <th>&emsp; $opt[3] &emsp; <br> (in &#8377;)</th>
  <th>&emsp; $opt[4] &emsp; <br> (in &#8377;)</th>
  <th>&emsp; $opt[5] &emsp; <br> (in &#8377;)</th>
  <th>&emsp; $opt[6] &emsp; <br> (in &#8377;)</th>
  <th>&emsp; $opt[7] &emsp; <br> (in &#8377;)</th>
  <th>&emsp;TOTAL &emsp;<br> (in &#8377;)</th>
  </tr> ";

  while($row= mysqli_fetch_array($query)){
      echo "<tr>";
        echo "<td>" . $row['busid'] . "</td>"; 
        echo "<td>" . $row[$opt[0]] . "</td>"; 
        echo "<td>" . $row[$opt[1]] . "</td>";
        echo "<td>" . $row[$opt[2]] . "</td>";
        echo "<td>" . $row[$opt[3]] . "</td>";
        echo "<td>" . $row[$opt[4]] . "</td>"; 
        echo "<td>" . $row[$opt[5]] . "</td>";
        echo "<td>" . $row[$opt[6]] . "</td>";
        echo "<td>" . $row[$opt[7]] . "</td>";
        echo "<td>" . $row['Total'] . "</td>";
        echo "</tr>";
  }
  echo "</table>";
}
}

$query = mysqli_query($db,"delete from monthlyreport;"); 
    }
 
}

?>