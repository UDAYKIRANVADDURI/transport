<!-- seats.php -->
<?php
if(isset($_POST['insert']))
{
$host = "localhost";
$user = "root";
$password = "";
$database = "transport";
$busid=$_POST['busid'];
$cate=$_POST['cate'];
$date=$_POST['fromdate'];
$desc=$_POST['desc'];
$cost=$_POST['cost'];
// to check all details are filled or not
if(!empty($busid)&&!empty($cate)&&!empty($date)&&!empty($cate)&&!empty($desc)&&!empty($cost)){
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $sql = "SELECT * FROM `masterdemo` WHERE busid='$busid'";
    $qry = mysqli_query($conn, $sql);
    $result = mysqli_num_rows($qry);
    // insert only when busid present in masterdemo table
    if($result == 1) {

 
if($conn->connect_error){
        die("connection failed");
    }
else{
$sql = "INSERT INTO `seats` VALUES ('$busid','$cate','$date','$desc','$cost')";
 $qry = mysqli_query($conn, $sql);
if ($qry) {
    echo '<script type="text/javascript"> alert("Details Submitted")</script>';
}
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
?>
<html>

<head>
    <title>SEATS REPAIRS</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1>SEATS REPAIRS</h1>
    </header><br><br>
    <script>
    function back() {
        location.href = "Transport.php"
    }
    </script>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="myDiv">
            <label style="color:red;">Registration Number</label><input type="text" name="busid" /><br><br>
            <label>Select Category Of Bus</label> <select name="cate">
                <option value="VVIT"> VVIT</option>
                <option value="VIVA">VIVA</option>
            </select><br><br>

            <label for="from">From Date </label>
            <input type="date" name="fromdate" min="2007-01-01" max="2050-12-31" id="txt"
                 /><br><br>
            <label for="to">To Date (optional)</label><input type="date" name="todate" min="2007-01-01" max="2050-12-31"
                id="txt" ><br><br>

            <label>Description</label><textarea name="desc"></textarea><br><br>
            <label for="tcost">Cost</label> <input type="number" name="cost" /><br><br>
            <a>
                <button type="submit" value="Submit" name="insert"
                    class="btn">Submit</button><span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
                <button type="submit" value="Display" name="display" class="btn">Display</button>
            </a>
        </div>
    </form>
    <section>
        <?php
            session_start();
if(isset($_POST['display'])){
$host = "localhost";
$user = "root";
$password = "";
$database = "transport"; 
$busid=$_POST["busid"];
$fromdate=$_POST["fromdate"];
$todate=$_POST["todate"];
 if(!empty($todate)&&!empty($fromdate)){
$conn = mysqli_connect($host, $user, $password, $database);
// display when busid and dates are given
if($busid!=''){
    $qry = mysqli_query($conn,"SELECT * FROM seats where busid='$busid' and fromdate between '$fromdate' and '$todate' ");  
}
// display when only dates are given
else{
    $qry = mysqli_query($conn,"SELECT * FROM seats where fromdate between '$fromdate' and '$todate' ");
}
$rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
    $row=mysqli_fetch_array($rs);
    $username=$row["username"];
    $password=$row["password"];
    // delete option is added for admin login only
    if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){
echo "<table>
<tr>
<th>BUS_ID</th>
<th>CATEGORY</th>
<th>DATE</th>
<th>Description</th>
<th>COST</th>
<th>DELETION</th>
</tr>";

    while($row = mysqli_fetch_assoc($qry)) {
        echo "<tr><td>".$row["busid"]."</td><td>".$row["category"]."</td><td>".$row["fromdate"]."</td><td>".$row["description"]."</td><td>".$row["cost"]."</td><td><a href='?delete=".$row["busid"]."& date=".$row["fromdate"]."'>Delete</a></td></tr>";
    }
echo "</table>";
}else{   // display code for users
    echo "<table>
<tr>
<th>BUS_ID</th>
<th>CATEGORY</th>
<th>DATE</th>
<th>Description</th>
<th>COST</th>
</tr>";

    while($row = mysqli_fetch_assoc($qry)) {
        echo "<tr><td>".$row["busid"]."</td><td>".$row["category"]."</td><td>".$row["fromdate"]."</td><td>".$row["description"]."</td><td>".$row["cost"]."</td></tr>";
    }
echo "</table>";
}}
else{
  echo '<script> alert("Data cannot be displayed\nplease fill the fields\nBusid, From Date, and To Date"); </script>';

}
}

if(isset($_GET["delete"])) {
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $delete_id = $_GET["delete"];
    $date=$_GET["date"];
    $res = mysqli_query($conn, "DELETE FROM seats WHERE busid='$delete_id' and fromdate='$date'");
    if($res){
        echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'seats.php';</script>";
    }else{
        echo '<script type="text/javascript"> alert("Error while deleting")</script>';
    }
}
?>
    </section>
</body>

</html>