<!-- engine repairs code -->

<?php
if(isset($_POST['insert']))
{
$host = "localhost";
$user = "root";
$password = "";
$database = "transport";
$conn = mysqli_connect($host, $user, $password, $database);
$busid=$_POST['busid'];
$cate=$_POST['cate'];
$date=$_POST['fromdate'];
$change=$_POST['partchange'];
$desc=$_POST['desc'];
$cost=$_POST['cost'];
// to check whether all details are filled or not
if(!empty($busid)&&!empty($cate)&&!empty($date)&&!empty($change)&&!empty($desc)&&!empty($cost)){
    $sql = "SELECT * FROM `masterdemo` WHERE busid='$busid'";
    $qry = mysqli_query($conn, $sql);
    $result = mysqli_num_rows($qry);
    // insert only when busid present in masterdemo table
    if($result == 1) {
$sql = "INSERT INTO `enginerepairs` VALUES ('$busid','$cate','$date','$change','$desc','$cost')";
 $qry = mysqli_query($conn, $sql);
if ($qry) {
    echo '<script type="text/javascript"> alert("Details submitted successfully...")</script>';
}
else{
    echo '<script type="text/javascript"> alert("Error while uploading detials...")</script>';
   }
}
else{
    echo '<script type="text/javascript"> alert("Bus ID not found")</script>';
  }
}
else{
    echo '<script> alert("Details not submitted. Please fill all the details"); </script>';  
}
}
?>
<html>

<head>
    <title>ENGINE REPAIRS</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1> ENGINE REPAIRS </h1>
    </header>
    <script>
    function back() {
        location.href = "Transport.php"
    }
    </script>
    <div class="myDiv">
        <form action="" method="post" enctype="multipart/form-data">
            <label>Select Category Of Bus</label>
            <select name="cate" id="txt">
                <option value="VVIT"> VVIT</option>
                <option value="VIVA">VIVA</option>
            </select><br><br>
            <label style="color:red;"> Registration Number</label><input type="text" name="busid" id="txt" /> <br><br>
            <label> From Date</label>
            <input type="date" name="fromdate" min="2007-01-01" max="2050-12-31" id="txt" /><br><br>
            <label for="to">To Date (Optional)</label><input type="date" name="todate" min="2007-01-01" max="2050-12-31"
                id="txt"><br><br>
            <label>Change Of Part</label>
            <input type="text" name="partchange" id="txt" /><br><br>
            <label>Description</label><textarea name="desc" id="txt"></textarea><br><br>
            <label>Cost</label> <input type="number" name="cost" id="txt" /><br><br>
            <a>
                <button type="submit" class="btn" name="insert">Submit</button><span>&emsp;&emsp;&emsp;&emsp;</span>
                <button type="submit" name="display" class="btn">Display</button><span>&emsp;&emsp;</span></a>
    </div>
    </form><br><br>
</body>

</html>
<?php
if(isset($_POST['display'])){
$host = "localhost";
$user = "root";
$password = "";
$database = "transport";
$busid=$_POST["busid"];
$fromdate=$_POST["fromdate"];
$todate=$_POST["todate"];
$conn = mysqli_connect($host, $user, $password, $database);
if(!empty($fromdate)&&!empty($todate))
{
if($busid!=""){
    $sql = "SELECT * FROM enginerepairs where busid='$busid' and fromdate between '$fromdate' and '$todate'";  
}
else{
    // to display all tuples between given dates from user interface
    $sql = "SELECT * FROM enginerepairs where  fromdate between '$fromdate' and '$todate'";  
} 
$qry=mysqli_query($conn,$sql);

    // delete option is enabled for admin , users donnot have any access.
    $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
    $row=mysqli_fetch_array($rs);
    $username=$row["username"];
    $password=$row["password"];
    session_start();
    // using session storage for getting username and password fron login page.
    if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){
        echo "<table>
        <tr>
        <th>BUS_ID</th>
        <th>CATEGORY</th>
        <th>DATE</th>
        <th> Change </th>
        <th>Description</th>
        <th>COST</th>
        <th>DELETION</th>
        </tr>";
        // while loop for displaying data one after the another.
        while($row = mysqli_fetch_assoc($qry)) {
            echo "<tr><td>".$row["busid"]."</td><td>".$row["category"]."</td><td>".$row["fromdate"]."</td><td>".$row["partchange"]."</td><td>".$row["description"]."</td><td>".$row["cost"]."</td><td><a href='?delete=".$row["busid"]."&date=".$row["fromdate"]."'>Delete</a></td></tr>";
        }
    echo "</table>";
    }
    // delete is disabled for users so below code will execute .
    else{
        echo "<table>
        <tr>
        <th>BUS_ID</th>
        <th>CATEGORY</th>
        <th>DATE</th>
        <th> Change </th>
        <th>Description</th>
        <th>COST</th>
        </tr>";
        while($row = mysqli_fetch_assoc($qry)) {
        echo "<tr><td>".$row["busid"]."</td><td>".$row["category"]."</td><td>".$row["fromdate"]."</td><td>".$row["partchange"]."</td><td>".$row["description"]."</td><td>".$row["cost"]."</td></tr>";
    }
echo "</table>";
    }
}
else{
    echo '<script> alert("Please fill fromdate and todate"); </script>';
    }
}

if(isset($_GET["delete"])) {
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $delete_id = $_GET["delete"];
    $date = $_GET["date"];
    $res = mysqli_query($conn, "DELETE FROM enginerepairs WHERE busid='$delete_id' and fromdate='$date'");
    if($res){
        echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'EngineRepairs.php';</script>";
    }else{
        echo '<script type="text/javascript"> alert("Error while deleting")</script>';
    }
}

?>