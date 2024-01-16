
<html>
<head>
<title>STOPPAGE REPORT</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="reports.css">
<style>

</style>
</head>
<body>
 
<span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
<h1 class="h1" >STOPPAGE REPORT</h1></span> 
<script>
function back()
{
 location.href="Transport.php"
}
</script>

<form action="" method="post" enctype="multipart/form-data">
<div  class="myDiv">
<label for="" style="color:red;">Registration Number</label> 
<input type="text" name="busid" id="txt" /><br><br>
<a>
<button type="submit" name="checkstatus" class="btn"  >check status</button> &emsp;
<button type="submit" name="activestatus" class="btn" >active</button> &emsp;
<button type="submit" name="inactivestatus" class="btn" >inactive</button><br>
</a>
</div>
</form><br><br>
     <section>
        
        </section>
</body>
</html>
<?php
if(isset($_POST['checkstatus']))
{
  $id=$_POST['busid'];
  if($id=="")
  {
    echo '<script> alert("enter registration number"); </script>';
  }
  else{
    $bus=$_POST['busid'];

    $conn=new mysqli("localhost","root","","transport");
    
    if($conn->connect_error)
    {
      die("Connection Failed:".$conn->connect_error);
    }
    else{
      $query1=mysqli_query($conn,"select status from stoppage where busid='$bus';"); 
      $row=mysqli_fetch_array($query1);

      if(mysqli_num_rows($query1)==1){
        if($row['status']=='active'){
        echo '<script> alert("Bus is active");  </script>';
        }
        else{
            echo '<script> alert("Bus is in stoppage");  </script>';
            }
      }
      else{
        echo '<script> alert("please enter valid registration number");  </script>';
      }
    }
  }
}

if(isset($_POST['activestatus'])){
    $conn = mysqli_connect("localhost","root","","transport");
    $query =mysqli_query($conn,"SELECT * FROM stoppage WHERE status='active'");
    echo "<table >
    <tr>
    <th> Bus ID</th>
    <th> Status</th>";
    while($row=mysqli_fetch_array($query)){
        echo '<tr>';
        echo '<td>'.$row['busid'].'</td>';
        echo '<td>'.$row['status'].'</td>';
        echo '</tr>';
    }
    echo '</table>';
}
if(isset($_POST['inactivestatus'])){
    $conn = mysqli_connect("localhost","root","","transport");
    $query =mysqli_query($conn,"SELECT * FROM stoppage WHERE status='inactive'");
    echo "<table >
    <tr>
    <th> Bus ID</th>
    <th> Status</th>";
    while($row=mysqli_fetch_array($query)){
        echo '<tr>';
        echo '<td>'.$row['busid'].'</td>';
        echo '<td>'.$row['status'].'</td>';
        echo '</tr>';
    }
    echo '</table>';
}
?>