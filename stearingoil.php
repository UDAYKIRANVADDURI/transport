<!--Submit button code to insert the data into the database-->

<?php

//if submit button is clicked

//if submit button is clicked
if(isset($_POST['insert']))
{
    //Database connection
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "transport";
    $conn = mysqli_connect($host, $user, $password, $database);

    //assigning values to the variables
    $busid = $_POST['busid'];
    $cate = $_POST['cate']; 
    $date = $_POST['date'];
    $to_date = $_POST['todate'];
    $cost = $_POST['tcost'];

    //condition to insert 
    if(!empty($busid) && !empty($cate) && !empty($date) && !empty($cost))
    {
        //sql query to check if busid exists in masterdemo table
        $check_query = "SELECT COUNT(*) as count FROM masterdemo WHERE busid = '$busid'";
        $check_result = mysqli_query($conn, $check_query);
        $check_row = mysqli_fetch_assoc($check_result);

        //if busid exists in masterdemo table
        if($check_row['count'] > 0)
        {
            //sql query to insert values into stearingoil table
            $sql = "INSERT INTO `stearingoil`(`id`, `category`, `dates`, `tcost`) VALUES ('$busid','$cate','$date','$cost')";
            $qry = mysqli_query($conn, $sql);
 
            //condition if details are submitted
            if ($qry) {
                echo '<script type="text/javascript"> alert("Details Submitted")</script>';
            }
            else{
                echo '<script> alert("Failed to insert details. Please try again later.");  </script>';
            }
        }
        //if the registration number not present in the masterdemo table. Pop Up window
        else{
            echo '<script> alert("Enter a valid Registration Number!!!");  </script>';
        }
    }
    //if all the fields are not inserted then the pop up message 
    else{
        echo '<script type="text/javascript"> alert("Details not Submitted. Please fill all the details")</script>';
    }
}
?>

<!--HTML code-->

<html>

<head>
    <title>STEERING OIL</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">

    <!--styles.css-->

    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1> STEERING OIL </h1>
    </header>
    <script>
    function back() {
        location.href = "Transport.php";
    }
    </script>

    <body>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="myDiv">
                <label style="color:red;">Registration Number</label><input type="text" name="busid" class="btn1" />
                <br><br>
                <label>Select Category Of Bus</label> <select name="cate">
                    <option value="VVIT"> VVIT</option>
                    <option value="VIVA">VIVA</option>
                </select><br><br>
                <label for="from">From Date </label>
                <input type="date" name="date" min="2007-01-01" max="2050-12-31" /><br><br>
                <label for="from">To Date (Optional)</label>
                <input type="date" name="todate" min="2007-01-01" max="2050-12-31" /><br><br>
                <label for="tcost">Cost</label> <input type="text" name="tcost" /><br><br>
                <a>
                    <button type="submit" class="btn" value="Submit"
                        name="insert">Submit</button><span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
                    <button type="submit" class="btn" value="Display" name="display">Display</button>
                </a>
            </div>
        </form><br><br>
        <section>

            <!---php code to display the report-->

            <?php
    
//session started

session_start();

//if display button is clicked

if(isset($_POST['display'])){
$host = "localhost";
$user = "root";
$password = "";
$database = "transport";
$busid=$_POST['busid'];
$to_date=$_POST['todate'];
$date=$_POST['date'];
$conn = mysqli_connect($host, $user, $password, $database);

//checking the condition whether the dates are filled or not

if(!empty($date) && !empty($to_date)){
    if($busid==''){
        
        //query if input is dates.
       
        $qry=mysqli_query($conn,"SELECT * from stearingoil WHERE dates BETWEEN '$date' and '$to_date'");
    }else{
        
        //query if input is busid and dates.

        $qry=mysqli_query($conn,"SELECT * from stearingoil WHERE id='$busid' and dates BETWEEN '$date' and '$to_date'");
    }
    
    //checking the login details.

    $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
    $row=mysqli_fetch_array($rs);
    $username=$row["username"];
    $password=$row["password"];
    
     //conditions to check username and password

    if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){

        //table displaying code if the login is admin with deletion option
//table headings

        echo "<table>
        <tr>
        <th>BUS_ID</th>
        <th>CATEGORY</th>
        <th>DATE</th>
        <th>COST</th>
        <th>DELETION</th>
        </tr>";

    while($row = mysqli_fetch_assoc($qry)) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["category"]."</td><td>".$row["dates"]."</td><td>".$row["tcost"]."</td><td><a href='?delete=".$row["id"]."&date=".$row["dates"]."'>Delete</a></td></tr>";
    }
echo "</table>";
}
else{
    
        //table displaying code if the login is user.

    echo "<table>
<tr>
<th>BUS_ID</th>
<th>CATEGORY</th>
<th>DATE</th>
<th>COST</th>
</tr>";
while($row = mysqli_fetch_assoc($qry)) {
    echo "<tr><td>".$row["id"]."</td><td>".$row["category"]."</td><td>".$row["dates"]."</td><td>".$row["tcost"]."</td></tr>";
}
echo "</table>";
}
}
else{
    
    //checkimg if details are inserted properly or not

    echo '<script type="text/javascript"> alert("Details are not inserted. Please fill fromdate and todate")</script>';
}}

//delete code.

if(isset($_GET["delete"])) {
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $delete_id = $_GET["delete"];
    $date = $_GET["date"];
    $res = mysqli_query($conn, "DELETE FROM stearingoil WHERE id='$delete_id' and dates='$date'");
    if($res){
        echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'stearingoil.php';</script>";
    }else{
        echo '<script type="text/javascript"> alert("Error while deleting")</script>';
    }
}
?>
        </section>
    </body>

</html>

<!--end of the code-->