<html>

<head>
    <title>DIESEL DETAILS</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="styles.css">
    <style>
    h1 {
        text-shadow: 1px 2px 1px brown;
        font-weight: bold;
        color: black;
        margin: auto;
        text-align: center;
    }
    </style>
</head>

<body>
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1> DIESEL DETAILS </h1>
    </header>
    <br><br><br>
    <script>
    function back() {
        location.href = "Transport.php";
    }
    </script>

    <div class="myDiv">
        <form action="" method="post">
            <label style="color:red;">Registration Number </label><input type="text" name="busid"> <br><br>
            <label>Vendor</label> <input type="text" name="vendor" id="txt"><br><br>
            <label>Present Meter Reading</label> <input type="number" name="pmeterreading" id="txt"><br><br>
            <label>Filled oil(litres)</label> <input type="number" name="filledoil" id="txt"><br><br>
            <label>From date</label> <input type="date" name="fromdate" id="txt"><br><br>
            <label>To date (Optional)</label> <input type="date" name="todate" id="txt"><br><br>
            <label>Bill No </label><input type="text" name="billno" id="txt"><br><br>
            <label>Price</label> <input type="number" name="price" id="txt"><br><br>
            <label>Address</label> <textarea name="address"></textarea><br><br>
            <a>
                <button type="submit" value="Submit" class="btn"
                    name="insert">Submit</button><span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
                <button type="submit" name="display" class="btn">display</button>
            </a>
        </form>
    </div><br><br>
</body>

</html>
<?php
session_start();
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
          $query=mysqli_query($conn,"select * from diesel where busid='$busid' and date between '$fromdate' and '$todate'");
            }
            else{
                $query=mysqli_query($conn,"select * from diesel where date between '$fromdate' and '$todate'");
            }
$rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
    $row=mysqli_fetch_array($rs);
    $username=$row["username"];
    $password=$row["password"];
    if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){
          echo "<table>
       <tr>
       <th>busno</th>
       <th>vendor</th>
       <th>Present Meter Reading</th>
       <th>filled oil </th>
       <th>filled date</th>
       <th>reciept number</th>
       <th>price</th>
       <th>address</th>
       <th>milage</th>
        <th> Delete</th>
       </tr>
       ";
 while($row= mysqli_fetch_array($query)){
           echo "<tr><td>".$row["busid"]."</td><td>".$row["vendor"]."</td><td>".$row["pmeterreading"]."</td><td>".$row["filledoil"]."</td><td>".$row["date"]."</td><td>".$row["billno"]."</td><td>".$row["price"]."</td><td>".$row["address"]."</td><td>".$row["milage"]."</td><td><a href='?delete=".$row["busid"]."&date=".$row["date"]."'>Delete</a></td></tr>";
          }
          echo "</table>";
        }
        else{
echo "<table>
       <tr>
       <th>busno</th>
       <th>vendor</th>
       <th>Present Meter Reading</th>
       
       <th>filled oil </th>
       <th>filled date</th>
       <th>reciept number</th>
       <th>price</th>
       <th>address</th>
       <th>milage</th>

       </tr>
       ";
 while($row= mysqli_fetch_array($query)){
          echo "<tr>";
          echo "<td>" . $row['busid'] . "</td>";
          echo "<td>" . $row['vendor'] . "</td>";
          echo "<td>" . $row['pmeterreading'] . "</td>";
          
          echo "<td>" . $row['filledoil'] . "</td>";
          echo "<td>" . $row['date'] . "</td>";
          echo "<td>" . $row['billno'] . "</td>";
          echo "<td>" . $row['price'] . "</td>";
          echo "<td>" . $row['address']. "</td>";
          echo "<td>" . $row['milage']. "</td>";
       
          echo "</tr>";
          }
          echo "</table>";
}
}
else{
            echo '<script> alert("Please insert from date and to date");  </script>';
        }
     }
}
if(isset($_GET["delete"])) {
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $delete_id = $_GET["delete"];
    $date=$_GET["date"];
    $res = mysqli_query($conn, "DELETE FROM diesel WHERE busid='$delete_id' and date='$date'");

    $q="select pmeterreading from diesel where busid='$delete_id' order by pmeterreading desc limit 1";
$r=mysqli_query($conn,$q);
$row1=mysqli_fetch_array($r);

$pmrr=$row1['pmeterreading'];
    if($pmrr!=0){
   $res1=mysqli_query($conn,"update masterdemo set presentmr='$pmrr' where busid='$delete_id'");
    }
    else{
        $r1=mysqli_query($conn,"select pmr from rc where regno='$delete_id'");
        $row1=mysqli_fetch_array($r1);
        $pmrr=$row1['pmr'];
        $res1=mysqli_query($conn,"update masterdemo set presentmr='$pmrr' where busid='$delete_id'");
    }
    if($res && $res1){
        echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'diesel.php';</script>";
    }else{
        echo '<script type="text/javascript"> alert("Error while deleting")</script>';
    }
} 

if(isset($_POST['insert'])){
$busid=$_POST["busid"];
$vendor=$_POST["vendor"];
$pmr=$_POST["pmeterreading"];
$filledoil=$_POST["filledoil"];
$date=$_POST["fromdate"];
$reciept=$_POST["billno"];
$price=$_POST["price"];
$addr=$_POST["address"];
$conn=new mysqli("localhost","root","","transport");
if($conn->connect_error)
{
  die("Connection Failed:".$conn->connect_error);
}
else{
    if(!empty($busid) && !empty($vendor) && !empty($pmr) && !empty($filledoil) && !empty($date) && !empty($reciept) && !empty($price) && !empty($addr)){
        $res=mysqli_query($conn,"select presentmr from masterdemo where busid='$busid'"); 
        if(mysqli_num_rows($res) > 0) {
            $r= mysqli_fetch_array($res);
            $pastmr=$r['presentmr']; 
            $milage= ($pmr-$pastmr)/$filledoil;
            $query="INSERT INTO diesel  VALUES ('$busid','$vendor','$pmr','$filledoil','$date','$reciept','$price','$addr','$milage')";
            $rs=mysqli_query($conn,$query);  
            $q="select pmeterreading from diesel where busid='$busid' order by pmeterreading desc limit 1";
            $r=mysqli_query($conn,$q);
            $row1=mysqli_fetch_array($r);
            $pmrr=$row1['pmeterreading'];
            $res1=mysqli_query($conn,"update masterdemo set presentmr='$pmrr' where busid='$busid'");
            if($res1){
                echo '<script> alert("Details submitted successfully");  </script>';
            }
        } else {
            echo '<script> alert("Incorrect bus id");  </script>';
        }
    } else {
        echo '<script> alert("Please insert all the fields");  </script>';
    }    
}
}
?>