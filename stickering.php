<!-- stickering.php updated -->
<?php
session_start();
?>
<html>
<head>
<title>STICKERING COST</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="styles.css">
    
</head>

<body>
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1>STICKERING COST</h1>
    </header>
    <br><br><br>
    <script>
    function back() {
        location.href = "Transport.php";
    }
    </script>
<form action="" method="post">
<div class="myDiv" id="d">
<label style="color:red;">Registration Number</label> <input type="text" name="busid" /> <br><br>
<label for="bus">Select Category</label>
<select name="category" id="bus">
  <option value="vvit">vvit</option>
  <option value="viva">viva</option>

</select><br><br>

<label for="from">From Date</label>
<input type="date" name="fromdate"  min="2007-01-01" max="2050-12-31" value="<?php echo $_POST['fromdate'] ?? ''; ?>"/><br><br>
<label>To Date (Optional)</label>
<input type="date" name="todate"  min="2007-01-01" max="2050-12-31" value="<?php echo $_POST['todate'] ?? ''; ?>"/><br><br>
<label>
        <input type="radio" name="type" class="rd" value="manual paint" onclick="onRadioButtonClick()">
         Manual Paint
      </label>
      <label>
        <input type="radio" name="type" class="rd" value="sticker" onclick="onRadioButtonClick()">
        Stickering
      </label>

      <div id="data1" style="display: none; ">
<p>---------------------------------------------------------------------------------------------------------------------------</p>
        <label for="Company Name" >Worker Name </label>
	 <input type="text" id="companyname" class="btn1" name="workername"/><br><br>
	<label for="costofbattery">Stickering Cost</label> <input type="number" id="costb" name="cost"/><br><br>
	
      </div>
      <div id="data2" style="display: none;">
<p>----------------------------------------------------------------------------------------------------------------------------</p>

          <label for="Company Name">Worker Name </label>
	<input type="text" id="companyname" class="btn1" name="workernamE"/><br><br>
 <label for="totalval">Stickering Cost</label><input type="number" id="totval" class="btn1" name="total"/><br><br>
      </div>
      <br><br>
<a>
<button type="submit" class="btn" value="Submit" name="insert">Submit</button><span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
<button type="submit" class="btn" value="Display" name="display">Display</button>
  </a>
</div>
</form>
<script>
      function onRadioButtonClick() {
        var selectedValue = document.querySelector('input[name="type"]:checked').value;
        var div1=document.getElementById("d");
        div1.style.height="500px";
        if (selectedValue === "manual paint") {
          var data1 = document.getElementById("data1");
          data1.style.display = "block";
          var data2 = document.getElementById("data2");
          data2.style.display = "none";
        } else if (selectedValue === "sticker") {
          var data2 = document.getElementById("data2");
          data2.style.display = "block";
          var data1 = document.getElementById("data1");
          data1.style.display = "none";
        }
      }
 </script>
 <section>
<?php
if(isset($_POST['display'])){
    $conn=new mysqli("localhost","root","","transport");
    if($conn->connect_error){
        die("connection failed");
    }
        else{
          $busid=$_POST["busid"];
          $fromdate=$_POST["fromdate"];
          $todate=$_POST["todate"];
          if(!empty($fromdate) && !empty($todate)){
          if($busid!=""){
            // to display all tuples between given dates from user interface
            $query=mysqli_query($conn,"select * from stickering where busid='$busid' and fromdate between '$fromdate' and '$todate' ");
          }
          else{
            $query=mysqli_query($conn,"select * from stickering where fromdate between '$fromdate' and '$todate' "); 
          }

          // delete option is enabled for admin , users donnot have any access.
          $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
            $row=mysqli_fetch_array($rs);
            $username=$row["username"];
            $password=$row["password"];
            // using session storage for getting username and password fron login page .
            if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){
          echo "<table>
          <tr>
         <th>busid</th>
         <th>category</th>
         <th>Date</th>
         <th>type</th>
         <th>worker name</th>
         <th>cost</th> 
         <th>Deletion</th> 
         </tr>
         ";
         // while loop for displaying data one after the another.
         while($row= mysqli_fetch_array($query)){
            echo "<tr>";
            echo "<td>" . $row['busid'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>" . $row['fromdate'] . "</td>";
            echo "<td>" . $row['type'] . "</td>";
            echo "<td>" . $row['workername'] . "</td>";
           echo "<td>" . $row['cost'] . "</td>"; 
           echo "<td><a href='?delete=".$row["busid"]."& date=".$row["fromdate"]."'>Delete</a></td>"; 
            echo "</tr>";
            }
            echo "</table>";
          }else{

            // delete is disabled for users so below code will execute .
            echo "<table>
          <tr>
         <th>busid</th>
         <th>category</th>
         <th>Date</th>
         <th>type</th>
         <th>worker name</th>
         <th>cost</th> 
         </tr>
         ";
         while($row= mysqli_fetch_array($query)){
            echo "<tr>";
            echo "<td>" . $row['busid'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>" . $row['fromdate'] . "</td>";
            echo "<td>" . $row['type'] . "</td>";
            echo "<td>" . $row['workername'] . "</td>";
           echo "<td>" . $row['cost'] . "</td>"; 
            echo "</tr>";
            }
            echo "</table>";
        } }
          else{
            echo '<script> alert("Please insert from date and to date");  </script>';
          }
       }
  } 
  if(isset($_GET["delete"])) {
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $delete_id = $_GET["delete"];
    $date=$_GET["date"];
    $res = mysqli_query($conn, "DELETE FROM stickering WHERE busid='$delete_id' and fromdate='$date'");
    if($res){
        echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'stickering.php';</script>";
    }else{
        echo '<script type="text/javascript"> alert("Error while deleting")</script>';
    }
}
?>
</section>
</body>
</html>
<?php
    if(isset($_POST['insert'])){
      // for type manual 
      if(isset($_POST['type']) && $_POST['type']=="manual paint")
       {
        $_s=$_POST["category"];
        $busid=$_POST["busid"];
        $date=$_POST["fromdate"];
        $category=$_s;
        $type=$_POST["type"];
        $workername=$_POST["workername"];
        $cost=$_POST["cost"];
        $conn=new mysqli("localhost","root","","transport");
if($conn->connect_error)
{
  die("Connection Failed:".$conn->connect_error);
}
else{
  // to check all details are filled or not
  if(!empty($busid) && !empty($_s) && !empty($date) && !empty($type) && !empty($workername) && !empty($cost)){
    $sql = "SELECT * FROM `masterdemo` WHERE busid='$busid'";
    $qry = mysqli_query($conn, $sql);
    $result = mysqli_num_rows($qry);
    // insert only when busid present in masterdemo table
    if($result == 1) {

  $query="INSERT INTO stickering VALUES ('$busid','$category','$date','$type','$workername','$cost')";
  $rs=mysqli_query($conn,$query);  
   if($rs){
      echo '<script> alert("data Inserted");  </script>';
   }
  }
  else{
    echo '<script type="text/javascript"> alert("Bus ID not found")</script>';
  }
}
  else{
    echo '<script> alert("Please insert all the fields");  </script>';
  }
    }
  
}
// for type sticker
else if (isset($_POST['type']) && $_POST['type']=="sticker")
{
        $_s=$_POST["category"];
        $busid=$_POST["busid"];
        $category=$_s;
        $date=$_POST["fromdate"];
        $type=$_POST["type"];
        $workername=$_POST["workernamE"];
        $cost=$_POST["total"];
        $conn=new mysqli("localhost","root","","transport");
if($conn->connect_error)
{
  die("Connection Failed:".$conn->connect_error);
}
else{
  // to validate all fields are filled or not
  if(!empty($busid) && !empty($_s) && !empty($date) && !empty($type) && !empty($workername) && !empty($cost)){
    $sql = "SELECT * FROM `masterdemo` WHERE busid='$busid'";
    $qry = mysqli_query($conn, $sql);
    $result = mysqli_num_rows($qry);
    // busid must be present in masterdemo .
    if($result == 1) {
  $query="INSERT INTO stickering VALUES ('$busid','$category','$date','$type','$workername','$cost')";
  $rs=mysqli_query($conn,$query);  
   if($rs){
      echo '<script> alert("data Inserted");  </script>';
   }
  }
  else{
    echo '<script type="text/javascript"> alert("Bus ID not found")</script>';
  }
  }
  else{
    echo '<script> alert("Please insert all the fields");  </script>';
  }
    }
  
}else{
  echo '<script> alert("Please insert all the fields including type..");  </script>';
}
  }
  ?>