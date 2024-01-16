<!--others code-->
<?php
session_start();
if(isset($_POST['insert']))
{
$host = "localhost";
$user = "root";
$password = "";
$database = "transport";
$busid=$_POST['busid'];
$conn = mysqli_connect($host, $user, $password, $database);
$check_sql = "SELECT * FROM `masterdemo` WHERE `busid`='$busid'";
    $check_query = mysqli_query($conn, $check_sql);
    $num_rows = mysqli_num_rows($check_query);

    if($num_rows == 0){
        // If `busid` does not exist in `masterdemo` table
        echo '<script type="text/javascript"> alert("Cannot upload details because Bus ID doesnot exists")</script>';
    }
    else{
$cate=$_POST['cate'];
$date=$_POST['date'];
$desc=$_POST['desc'];
$invoice=$_POST['invoice'];
$cost=$_POST['cost'];
if(!empty($busid)&&!empty($cate)&&!empty($date)&&!empty($desc)&&!empty($invoice)&&!empty($cost)){
$sql = "INSERT INTO `others` VALUES ('$busid','$cate','$date','$desc','$invoice','$cost')";
 $qry = mysqli_query($conn, $sql);
if ($qry) {
    echo '<script type="text/javascript"> alert("Details Submitted")</script>';
}
else{
    echo '<script> alert("Enter a valid registration number!!!");  </script>';
   }
}
else{
    echo '<script type="text/javascript"> alert("Enter all the details")</script>';
}
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <!--Favicon-->
    <title>OTHERS</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <form action="" method="post" enctype="multipart/form-data">
        <header>
            <!--Home image-->
            <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
                <h1>OTHERS</h1>
        </header><br><br>
        <script>
        function back() {
            location.href = "Transport.php"
        }
        </script>
        <div class="myDiv">

            <label style="color:red;">Registration Number </label><input type="text" name="busid"
                class="btn1" /><br><br>
            <label>Select Category Of Bus</label> <select name="cate">
                <option value="VVIT"> VVIT</option>
                <option value="VIVA">VIVA</option>
            </select><br><br>
            <label for="from">From Date </label>
            <input type="date" name="date" min="2007-01-01" max="2050-12-31" /><br><br>
            <label for="invoice">Invoice No</label> <input type="text" name="invoice" /><br><br>
            <label>Description</label><textarea name="desc"></textarea><br><br>
            <label for="tcost">Cost</label> <input type="number" name="cost" /><br><br>
            <a>
                <button type="submit" class="btn" name="insert">Submit</button>
                <button type="submit" name="display" class="btn">Display</button>
            </a>
    </form>
    </div> <br><br>
    <section>
        <!-- display and delete code-->
        <?php
if(isset($_POST['display'])){
$host = "localhost";
$user = "root";
$password = "";
$database = "transport";
$date=$_POST['date'];
$busid=$_POST['busid'];
$conn = mysqli_connect($host, $user, $password, $database);
if(!empty($busid)){
 $conn=new mysqli("localhost","root","","transport");
  if($conn->connect_error){
      die("connection failed");
  }else{
if($busid!=''){
$query=mysqli_query($conn,"SELECT * FROM others where busid='$busid'");  
}
else{
     $query=mysqli_query($conn, "SELECT * FROM others where busid='$busid'");
}

$rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
    $row=mysqli_fetch_array($rs);
    $username=$row["username"];
    $password=$row["password"];
    if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){
echo "<table>
<tr>
<th>BUS_ID</th>
<th>CATEGORY</th>
<th>DATE</th>
<th>Description</th>
<th>COST</th>
<th>deletion</th>
</tr>";

    while($row = mysqli_fetch_assoc($query)) {
        echo "<tr><td>".$row["busid"]."</td><td>".$row["category"]."</td><td>".$row["fromdate"]."</td><td>".$row["description"]."</td><td>".$row["cost"]."</td><td><a href='?delete=".$row["busid"]."'>Delete</a></td></tr>";
    }
echo "</table>";
}
else{
echo "<table>
<tr>
<th>BUS_ID</th>
<th>CATEGORY</th>
<th>DATE</th>
<th>Description</th>
<th>COST</th>
</tr>";
   while($row = mysqli_fetch_assoc($query)) {
        echo "<tr><td>".$row["busid"]."</td><td>".$row["category"]."</td><td>".$row["fromdate"]."</td><td>".$row["description"]."</td><td>".$row["cost"]."</td></tr>";
    }
echo "</table>";
}
}
}
else{
    echo '<script type="text/javascript"> alert("enter bus id")</script>';
}}
if(isset($_GET["delete"])) {
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $delete_id = $_GET["delete"];
    $res = mysqli_query($conn, "DELETE FROM others WHERE busid='$delete_id'");
    if($res){
        echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'http://localhost/form123/others.php';</script>";
    }else{
        echo '<script type="text/javascript"> alert("Error while deleting")</script>';
    }
}

?>
    </section>
</body>

</html>