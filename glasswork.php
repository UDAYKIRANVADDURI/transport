<!-- glasswork -->
<?php 
session_start();
?>
<html>

<head>
    <title>GLASS WORK</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1> GLASS WORK </h1>
    </header><br><br>
    <script>
    function back() {
        location.href = "Transport.php"
    }
    </script>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="myDiv">
            <label for="" style="color:red;">Registration Number : </label>
            <input type="text" class="border-solid border-2 my-2 p2" name="bus_id" id="id"> <br><br>
            <label for="">Category</label>
            <select name="sele" id="" class="border-solid border-2 my-2 " id="category">
                <option value="Select">Select Category</option>
                <option value="VVIT">VVIT</option>
                <option value="VIVA">VIVA</option>
            </select> <br><br>
            <label for="">From</label>
            <input type="date" class="border-solid border-2 my-2 " name="from_date" id=""
                value="<?php echo $_POST['from_date'] ?? ''; ?>"> <br><br>
            <label for="">To (optional)</label>
            <input type="date" name="to_date" id="" value="<?php echo $_POST['to_date'] ?? ''; ?>"> <br><br>
            <label for="">Place</label>
            <input type="text" name="place" id="pla"> <br><br>
            <label for="">Company Name</label>
            <input type="text" name="company_name" id="comn"> <br><br>

            <label for="">Quantity</label>
            <input type="number" oninput="Calc()" name="quan" id="qua"> <br><br>
            <label for="">Glass Cost</label>
            <input type="number" oninput="Calc()" name="glass_cost" id="gc"> <br><br>
            <label for="">Total Value</label>
            <input type="number" name="total" id="t"> <br><br>
            <a>
                <button type="submit" value="Submit" class="btn"
                    name="insert">Submit</button><span>&emsp;&emsp;&emsp;</span>
                <button type="submit" value="Display" name="display"
                    class="btn">Display</button><span>&emsp;&emsp;&emsp;</span>
            </a>
        </div>
    </form>

    <section>
        <?php
        ob_start();
    if(isset($_POST['display'])){
$id = $_POST['bus_id'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
 if(!empty($to_date)&&!empty($from_date)){
$conn = mysqli_connect("localhost", "root", "", "transport");
// display when both busid and dates are given
if($id!=""){
    $sql = mysqli_query($conn,"SELECT *  FROM `glasswork` where busid='$id' and fromdate between '$from_date' and '$to_date'");
}
// display when only dates are given
else{
    $sql = mysqli_query($conn,"SELECT *  FROM `glasswork` where fromdate between '$from_date' and '$to_date' ");
}
$rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
            $row=mysqli_fetch_array($rs);
            $username=$row["username"];
            $password=$row["password"];
            // deletion available for only admin
            if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){
            
          //  $query=mysqli_query($conn,"select * from paints");
            echo "<table border='5' style='border: 3px solid black; color:black; padding:3px;background-color:white;border-radius:10px;'>
            <tr>
         <th>Busid</th>
         <th>category</th>
         <th>Place</th>
         <th>Date</th>
         <th>CompanyName</th>
         <th>Quantity</th>
         <th>Cost</th>
         <th>TotalValue</th> 
       <th>DELETION</th> 
         </tr>
         ";
         while($row= mysqli_fetch_array($sql)){
            echo "<tr>";
            echo "<td>" . $row['busid'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>" . $row['place'] . "</td>";
            echo "<td>" . $row['fromdate'] ."</td>";
            echo "<td>" . $row['comname'] . "</td>";
            echo "<td>" . $row['quant'] . "</td>";
            echo "<td>" . $row['cost'] . "</td>";
            echo "<td>" . $row['val'] . "</td>"; 
            echo "<td><a href='?delete=".$row["busid"]."& date=".$row["fromdate"]."'>Delete</a></td>";
            echo "</tr>";
            }
            echo "</table>";
       }
 else{ // for users
 echo "<table border='5' style='border: 3px solid black; color:black; padding:3px;background-color:white;border-radius:10px;'>
            <tr>
            <th>Busid</th>
            <th>category</th>
            <th>Place</th>
            <th>Date</th>
            <th>Company Name</th>
            <th>Quantity</th>
            <th>Cost</th>
            <th>TotalValue</th> 
         </tr>
         ";
         while($row= mysqli_fetch_array($sql)){
            echo "<tr>";
            echo "<td>" . $row['busid'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>" . $row['place'] . "</td>";
            echo "<td>" . $row['fromdate'] ."</td>";
            echo "<td>" . $row['comname'] . "</td>";
            echo "<td>" . $row['quant'] . "</td>";
            echo "<td>" . $row['cost'] . "</td>";
            echo "<td>" . $row['val'] . "</td>";
            echo "</tr>";
            }
            echo "</table>";
}
}
else{
  echo '<script> alert("Data cannot be displayed\nplease fill the fields\nBusid, From Date, and To Date"); </script>';
}

    }

if(isset($_POST['insert'])){
   
    $id = $_POST['bus_id'];
    $cat = $_POST['sele'];
    $from_date = $_POST['from_date'];
    $pla = $_POST['place'];
    $cname = $_POST['company_name'];
    $quant = $_POST['quan'];
    $cos = $_POST['glass_cost'];
    $total = $_POST['total'];
    //details must be filled
 if(!empty($id)&&!empty($cat)&&!empty($from_date)&&!empty($pla)&&!empty($cname)&&!empty($quant)&&!empty($cos)&&!empty($total)){
$conn = mysqli_connect("localhost", "root", "", "transport");
if($conn->connect_error){
        die("connection failed");
    }
 else{
    $sql = "SELECT * FROM `masterdemo` WHERE busid='$id'";
    $qry = mysqli_query($conn, $sql);
    $result = mysqli_num_rows($qry);
    // insert only when busid present in masterdemo table
    if($result == 1) {
    $sql = "INSERT INTO `glasswork` VALUES ('$id','$cat','$pla','$cname','$quant','$cos','$total', '$from_date')";
    $qry = mysqli_query($conn, $sql);
    if ($qry) {
    echo '<script type="text/javascript"> alert("Details Submitted")</script>';
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
if(isset($_GET["delete"])) {
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $delete_id = $_GET["delete"];
    $date=$_GET["date"];
    $res = mysqli_query($conn, "DELETE FROM glasswork WHERE busid='$delete_id' and fromdate='$date'");
    if($res){
        echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'glasswork.php';</script>";
    }else{
        echo '<script type="text/javascript"> alert("Error while deleting")</script>';
    }
}
ob_end_flush();
?>
    </section>
    <script>
    function Calc() {
        var a = document.getElementById('qua').value;
        var b = document.getElementById('gc').value;
        var res = 0
        var res = parseInt(a) * parseInt(b);
        document.getElementById('t').value = res;
    }
    </script>
</body>

</html>