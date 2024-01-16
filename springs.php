<!-- springs.php updated -->
<?php
session_start();
?>
<html>

<head>
    <title>SPRINGS COST</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1> SPRINGS COST</h1>
    </header>
    <script>
    function back() {
        location.href = "Transport.php";
    }
    </script>
    <form action="springs.php" method="post">
        <div class="myDiv" style="text-align:center;">
            <label style="color:red;">Registration Number </label><input type="text" name="busid" id="txt" /><br><br>
            <label for="bus">Select Category</label>
            <select name="category" id="txt">
                <option value="vvit">vvit</option>
                <option value="viva">viva</option>
            </select><br><br>
            <label for="from">From Date </label>
            <input type="date" name="fromdate" min="2007-01-01" max="2050-12-31" id="txt"> <br><br>
            <label for="to">To Date (Optional)</label><input type="date" name="todate" min="2007-01-01" max="2050-12-31"
                id="txt"><br><br>

            <label>Mechanic name</label> <input type="text" name="mechanic" id="mech" id="txt" /></br><br>
            <label>Company </label><input type="text" name="company" id="txt" /><br><br>
            <label>Springs Cost</label><input type="number" name="sprcost" id="cost" oninput="calculate()" style="padding: auto;
    font-size: large;
    cursor: pointer;" /><br><br>
            <label>Quantity</label><input type="number" max="20" min="0" name="sprquantity" id="qt" style="padding: auto;
    font-size: large;
    cursor: pointer;" oninput="calculate()" /><br><br>
            <label for="totalval"> Total Value</label><input type="number" id="totval" name="totval" style="padding: auto;
    font-size: large;
    cursor: pointer;" /><br><br>

            <a>
                <button type="submit" value="Submit" name="insert"
                    class="btn">Submit</button><span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
                <button type="submit" class="btn" value="Display" name="display">Display</button></a>
        </div>

    </form>
    <script>
    function calculate() {
        var cost = document.getElementById("cost").value;
        var qty = document.getElementById("qt").value;
        var total = parseFloat(cost) * parseInt(qty);
        document.getElementById("totval").value = total;
    }
    </script>
</body>

<?php
if(isset($_POST['display'])){
  $busid=$_POST["busid"];
  $fromdate=$_POST["fromdate"];
  $todate=$_POST["todate"];
  if(!empty($todate)&&!empty($fromdate)){
  $conn=new mysqli("localhost","root","","transport");
  if($conn->connect_error){
      die("connection failed");
  }
      else{
        // when both busid and dates are given
        if($busid!=""){
          $query=mysqli_query($conn,"select * from springs where busid='$busid' and fromdate between '$fromdate' and '$todate'");
        }
        // when only dates are given
        else{
          $query=mysqli_query($conn,"select * from springs where   fromdate between '$fromdate' and '$todate'");
        }
        $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
        $row=mysqli_fetch_array($rs);
        $username=$row["username"];
        $password=$row["password"];

        //delete option only available to admin
        if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){
          echo "<table>
       <tr>
       <th>category</th>
       <th>busid</th>
       <th>fromdate</th>
       
       <th>mechanic</th>
       <th>company</th>
       <th>spring cost</th>
       <th>springs qty</th>
       <th>total</th>
       <th>Deletion</th> 
       </tr>
       ";
 while($row= mysqli_fetch_array($query)){
          echo "<tr>";
          echo "<td>" . $row['category'] . "</td>";
          echo "<td>" . $row['busid'] . "</td>";
          echo "<td>" . $row['fromdate'] . "</td>";
         
          echo "<td>" . $row['mechanic'] . "</td>";
          echo "<td>" . $row['company'] . "</td>";
          echo "<td>" . $row['sprcost'] . "</td>";
          echo "<td>" . $row['sprquantity'] . "</td>";
          echo "<td>" . $row['totvalue'] . "</td>";
          echo "<td><a href='?delete=".$row["busid"]."& date=".$row["fromdate"]."'>Delete</a></td>"; 
          echo "</tr>";
          }
          echo "</table>";
     } // for users
     else{
        echo "<table>
       <tr>
       <th>category</th>
       <th>busid</th>
       <th>fromdate</th>
       
       <th>mechanic</th>
       <th>company</th>
       <th>spring cost</th>
       <th>springs qty</th>
       <th>total</th>
       </tr>
       ";
 while($row= mysqli_fetch_array($query)){
          echo "<tr>";
          echo "<td>" . $row['category'] . "</td>";
          echo "<td>" . $row['busid'] . "</td>";
          echo "<td>" . $row['fromdate'] . "</td>";
         
          echo "<td>" . $row['mechanic'] . "</td>";
          echo "<td>" . $row['company'] . "</td>";
          echo "<td>" . $row['sprcost'] . "</td>";
          echo "<td>" . $row['sprquantity'] . "</td>";
          echo "<td>" . $row['totvalue'] . "</td>";
          echo "</tr>";
          }
          echo "</table>";
    }}}
else{
    echo '<script> alert("Please insert from date and to date");  </script>';
  }
} // to delete a particular tuple 
if(isset($_GET["delete"])) {
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $delete_id = $_GET["delete"];
    $date=$_GET["date"];
    $res = mysqli_query($conn, "DELETE FROM springs WHERE busid='$delete_id' and fromdate='$date'");
    if($res){
        echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'springs.php';</script>";
    }else{
        echo '<script type="text/javascript"> alert("Error while deleting")</script>';
    }
}
 
?>

</html>

<?php
if(isset($_POST['insert'])){ 
$category=$_POST["category"];
$busid=$_POST["busid"];
$fromdate=$_POST["fromdate"];
$todate=$_POST["todate"];
$mechanic=$_POST["mechanic"];
$company=$_POST["company"];
$sprcost=$_POST["sprcost"];
$sprquantity=$_POST["sprquantity"];
$totvalue=$_POST["totval"];
// check all details are filled or not
if(!empty($category)&&!empty($busid)&&!empty($fromdate) &&!empty($mechanic)&&!empty($company)&&!empty($sprcost)&&!empty($sprquantity)&&!empty($totvalue)){
$conn=new mysqli("localhost","root","","transport");
if($conn->connect_error)
{
  die("Connection Failed:".$conn->connect_error);
}
else{ 
    $sql = "SELECT * FROM `masterdemo` WHERE busid='$busid'";
    $qry = mysqli_query($conn, $sql);
    $result = mysqli_num_rows($qry);
    // insert only when busid present in masterdemo table
    if($result == 1) {  // busid present in masterdemo or not
   $query="INSERT INTO springs  VALUES ('$category','$busid','$fromdate','$mechanic','$company','$sprcost','$sprquantity','$totvalue')";
   $rs=mysqli_query($conn,$query);  
   if($rs){
      echo '<script> alert("data Inserted");  </script>';
   }
}
else{
    echo '<script type="text/javascript"> alert("Bus ID not found")</script>';
}
}
}
else{
  echo '<script> alert("Please insert all the fields");  </script>';
}
}
?>