<!-- quarterly taxes code-->
<!-- php code to insert -->
<?php
ob_start();
if(isset($_POST['insert'])){
$category=$_POST["category"];
$busid=$_POST["busid"];
$from=$_POST["from"];
$to=$_POST["to"];
$quartertax=$_POST["quartertax"];
$fixness=$_POST["fixness"];
$eibalteration=$_POST["eibalteration"];
$termination=$_POST["termination"];
$rtacon=$_POST["rtacon"];
$rtapegreen=$_POST["rtapegreen"];
$tollway=$_POST["tollway"];
if(!empty($category)&&!empty($busid)&&!empty($from)&&!empty($to) && ( $_POST["quartertax"] || 
    $_POST["fixness"] || $_POST["eibalteration"] || $_POST["termination"] || $_POST["rtacon"] || 
    $_POST["rtapegreen"] || $_POST["tollway"] ) )
    {
        $conn=new mysqli("localhost","root","","transport");
if($conn->connect_error)
{
  die("Connection Failed:".$conn->connect_error);
}
else{
    //code to check if bus id exists or not
    $check_sql = "SELECT * FROM `masterdemo` WHERE `busid`='$busid'";
    $check_query = mysqli_query($conn, $check_sql);
    $num_rows = mysqli_num_rows($check_query);

    if($num_rows == 0){
        // If `busid` does not exist in `masterdemo` table
        echo '<script type="text/javascript"> alert("Cannot upload details because Bus ID doesnot exists")</script>';
    }
    else{
   $query="INSERT INTO quarterlytax  VALUES ('$category','$busid','$from','$to','$quartertax','$fixness','$eibalteration','$termination','$rtacon','$rtapegreen','$tollway')";
   $rs=mysqli_query($conn,$query);  
   if($rs){
      echo '<script> alert("Details submitted successfully");  </script>';
   }
   else{
    echo '<script> alert("Error! while uploading details");  </script>';
   }
}
}
}
else{
  echo '<script> alert("Details are not submitted. Please fill all the details");  </script>';
}
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>QUARTERLY TAXES</title>
    <!--Favicon-->
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <!--css-->
    <link rel="stylesheet" type="" href="styles.css">
</head>

<body>
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1> QUARTERLY TAXES </h1>
    </header><br><br>
    <script>
    function back() {
        location.href = "Transport.php"
    }
    </script>
    <div class="myDiv">
        <!--form code -->
        <form action="" method="post">
            <label align="left">Select Category</label>
            <select name="category" id="txt">
                <option value="vvit">VVIT</option>
                <option value="viva">VIVA</option>
            </select></br></br>
            <label style="color:red;">Registration Number</label>
            <input type="text" name="busid" id="txt" /></br></br>
            <label>From</label>
            <input type="date" name="from" min="2007-01-01" id="txt"></br></br>
            <label>To</label>
            <input type="date" name="to" max="2090-01-01" id="txt"></br></br>
            <label>Quarter tax</label>
            <input type="number" name="quartertax" id="txt" /></br></br>
            <label>Fixness</label>
            <input type="number" name="fixness" id="txt" /></br></br>
            <label>EIB Alteration</label>
            <input type="number" name="eibalteration" id="txt" /></br></br>
            <label>termination</label>
            <input type="number" name="termination" id="txt" /></br></br>
            <label>RTA CON & transfer</label>
            <input type="number" name="rtacon" id="txt" /></br></br>
            <label>RTA pe & green</label>
            <input type="number" name="rtapegreen" id="txt" /></br></br>
            <label>Toll Way</label>
            <input type="number" name="tollway" id="txt" /></br></br></br>
            <a>
                <button type="submit" class="btn" name="insert">Submit</button><span>&emsp;&emsp;</span>
                <button type="submit" class="btn" name="display">display</button><span>&emsp;&emsp;</span>
            </a>
    </div>
    </form><br><br>

</body>

</html>
<!-- php code to display and delete-->
<?php
    session_start();
if(isset($_POST['display'])){
$busid=$_POST["busid"];
$fromdate=$_POST["from"];
  $todate=$_POST["to"];
  $conn=new mysqli("localhost","root","","transport");
  if($conn->connect_error){
      die("connection failed");
  }
      else{
        if(!empty($todate)&&!empty($fromdate)){
    if($busid!=""){
          $query=mysqli_query($conn,"select * from quarterlytax where busid='$busid' and `FromDate` between '$fromdate' and '$todate'");
        }
    else{
          $query=mysqli_query($conn,"select * from quarterlytax where `FromDate` between '$fromdate' and '$todate'");
        }
    $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
    $row=mysqli_fetch_array($rs);
    $username=$row["username"];
    $password=$row["password"];
    if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){

          echo "<table border='1'>
       <tr>
       <th>category</th>
       <th>Busid</th>
       <th>From</th>
      <th>To Date</th>
       <th>quartertax</th>
       <th>fixness</th>
       <th>eibalteration</th>
       <th>termination</th>
       <th>rtacon</th>
       <th>rtapegreen</th>
       <th>tollway</th>
       <th>DELETION</th>
       </tr>";
  while($row = mysqli_fetch_assoc($query)) {
        echo "<tr><td>".$row["busid"]."</td><td>".$row["category"]."</td><td>".$row["FromDate"]."</td><td>".$row["to"]."</td><td>".$row["quartertax"]."</td><td>".$row["fixness"]."</td><td>".$row["eibalteration"]."</td><td>".$row["termination"]."</td><td>".$row["rtacon"]."</td><td>".$row["rtapegreen"]."</td><td>".$row["tollway"]."</td><td><a href='?delete=".$row["busid"]."&date=".$row["FromDate"]."'>Delete</a></td></tr>";
    }
echo "</table>";
    }
else{
 echo "<table>
<tr>
       <th>category</th>
       <th>Busid</th>
       <th>From</th>
      <th>To Date</th>
       <th>quartertax</th>
       <th>fixness</th>
       <th>eibalteration</th>
       <th>termination</th>
       <th>rtacon</th>
       <th>rtapegreen</th>
       <th>tollway</th>
</tr>";

       while($row= mysqli_fetch_array($query)){
          echo "<tr>";
          echo "<td>" . $row['category'] . "</td>";
          echo "<td>" . $row['busid'] . "</td>";
          echo "<td>" . $row['FromDate'] . "</td>";
        echo "<td>" . $row['to'] . "</td>";
          echo "<td>" . $row['quartertax'] . "</td>";
          echo "<td>" . $row['fixness'] . "</td>";
          echo "<td>" . $row['eibalteration'] . "</td>";
          echo "<td>" . $row['termination'] . "</td>";
          echo "<td>" . $row['rtacon'] . "</td>";
          echo "<td>" . $row['rtapegreen'] . "</td>";
          echo "<td>" . $row['tollway'] . "</td>";
          echo "</tr>";
          }
          echo "</table>";
     }
}
else{
    echo '<script type="text/javascript"> alert("Please fill from date and to date")</script>';
}}
}
if(isset($_GET["delete"])) {
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $delete_id = $_GET["delete"];
    $date = $_GET["date"];
    $res = mysqli_query($conn, "DELETE FROM quarterlytax WHERE busid='$delete_id' and `FromDate`='$date'");
    if($res){
        echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'quarterlytaxes.php';</script>";
    }else{
        echo '<script type="text/javascript"> alert("Error while deleting")</script>';
    }
}
?>