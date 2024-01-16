<!-- status.php -->
<html>
<head>
<title>STATUS PAGE</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="reports.css">
</head>
<body>

<span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
<h1 class="h1"  >STATUS PAGE</h1></span> 
<!-- on clicking home image go back to home page -->
<script>
function back()
{
 location.href="Transport.php"
}
</script>
<!-- html form for taking input from user -->
<form action="" method="post" enctype="multipart/form-data">
<div  class="myDiv">
<label for="" style="color:red;">Registration Number</label> 
<input type="text" name="busid" id="txt" /><br><br>
<a>
<button type="submit" name="checkstatus" class="btn"  >check status</button> &emsp;
<button type="submit" name="display" class="btn" >active</button> &emsp;
<button type="submit" name="Display" class="btn" >inactive</button><br></a>
</div>
</form>
</body>
</html>
<?php
// Check if the 'checkstatus' button is clicked
if(isset($_POST['checkstatus']))
{
  // Get the registration number of the bus from the form
  $id=$_POST['busid'];

  // If the registration number is not entered, display an alert message
  if($id=="")
  {
    echo '<script> alert("enter registration number"); </script>';
  }
  else{
    // If the registration number is entered, store it in a variable and connect to the database
    $bus=$_POST['busid'];
    $conn=new mysqli("localhost","root","","transport");
    
    // Check if the connection to the database is successful
    if($conn->connect_error)
    {
      die("Connection Failed:".$conn->connect_error);
    }
    else{
      // Query to check if the bus is active in the 'status' table
      $query1 = mysqli_query($conn, "SELECT busid FROM status WHERE busid = '$bus'");

      // Query to check if the bus is inactive in the 'scrap' table
      $query2=mysqli_query($conn,"select busid from scrap where busid='$bus';"); 

      // If the bus is active, display an alert message
      if(mysqli_num_rows($query1)==1){
        echo '<script> alert("Bus is active");  </script>';
      }
      // If the bus is inactive, display an alert message
      else if(mysqli_num_rows($query2)==1){
          echo '<script> alert("Bus is inactive");  </script>';
      }
      // If the registration number is not valid, display an alert message
      else{
        echo '<script> alert("please enter valid registration number");  </script>';
      }
    }
  }
}
// Check if the 'display' button is clicked
if(isset($_POST['display']))
{
 $conn=new mysqli("localhost","root","","transport");
 if($conn->connect_error)
{
  die("Connection Failed:".$conn->connect_error);
}
else
{
  // Query to select all the data from the 'status' table
 $query=mysqli_query($conn,"select * from status");

 // Create a table to display the data
 echo "<table>
 <tr>
 <th> busid </th>
 <th> engine number </th>
 <th> chassis number </th>
 <th> status </th>
 <th> Expiry date </th>
 </tr>
 ";

 // Loop through the results of the query and display each row in the table
 while($row=mysqli_fetch_array($query))
{
 echo "<tr>";
 echo "<td>" . $row['busid'] . "</td>";
 echo "<td>" . $row['enginenumber'] ."</td>";
 echo "<td>" . $row['chassis'] ."</td>";
 echo "<td>" .$row['status'] ."</td>";
 echo "<td>" .$row['expirydate'] ."</td>";
 echo "</tr>";
}
 echo "</table>";
 }
}

if(isset($_POST['Display']))
{
 $conn=new mysqli("localhost","root","","transport");
 if($conn->connect_error)
{
  die("Connection Failed:".$conn->connect_error);
}
else
{
  // Query to select all the data from the 'scrap' table
 $query=mysqli_query($conn,"select * from scrap");

 // Create a table to display the data
 echo "<table>
 <tr>
 <th> busid </th>
 <th> engine number </th>
 <th> chassis number </th>
 <th> status </th>
 <th> Expiry date </th>
 </tr>
 ";

 // Loop through the results of the query and display each row in the table
 while($row=mysqli_fetch_array($query))
{
 echo "<tr>";
 echo "<td>" . $row['busid'] . "</td>";
 echo "<td>" . $row['engine'] ."</td>";
 echo "<td>" . $row['chassisnumber'] ."</td>";
 echo "<td>" .$row['status'] ."</td>";
 echo "<td>" .$row['expirydate'] ."</td>";
 echo "</tr>";
}
 echo "</table>";
 }
}
?>