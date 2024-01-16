<!-- php code to insert,delete and check the status-->
<?php
if(isset($_POST['insert'])){
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $busid = $_POST['id'];
    $status = $_POST['status'];
    $check_sql = "SELECT * FROM `masterdemo` WHERE `busid`='$busid'";
    $check_query = mysqli_query($conn, $check_sql);
    $num_rows = mysqli_num_rows($check_query);

    if($num_rows == 0){
        // If `busid` does not exist in `masterdemo` table
        echo '<script type="text/javascript"> alert("Cannot upload details because Bus ID doesnot exists")</script>';
    }
    else{

    $sql = "INSERT INTO `stoppage`(`busid`, `status`) VALUES ('$busid','$status')";
    $qry = mysqli_query($conn, $sql);

    if ($qry) {
        echo '<script type="text/javascript"> alert("Bus ID Inserted")</script>';
    } else {
        echo '<script type="text/javascript"> alert("Error!! while inserting or already inserted..")</script>';
    }
}
}

if(isset($_POST['active'])){
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $busid = $_POST['id'];

    $sql = "UPDATE `stoppage` SET `status`='active' WHERE `busid`='$busid'";
    $qry = mysqli_query($conn, $sql);

    if ($qry) {
        $sql_check = "SELECT `busid` FROM `stoppage` WHERE `busid`='$busid' AND `status`='active'";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) > 0) {
            echo '<script type="text/javascript"> alert("Bus ID is in active state")</script>';
        } else {
            echo '<script type="text/javascript"> alert("Error: ID not found or already active")</script>';
        }
    } else {
        echo '<script type="text/javascript"> alert("Error")</script>';
    }
}

if(isset($_POST['inactive'])){
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $busid = $_POST['id'];

    $sql = "UPDATE `stoppage` SET `status`='inactive' WHERE `busid`='$busid'";
    $qry = mysqli_query($conn, $sql);

    if ($qry) {
        $sql_check = "SELECT `busid` FROM `stoppage` WHERE `busid`='$busid' AND `status`='inactive'";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) > 0) {
            echo '<script type="text/javascript"> alert("Bus ID is in inactive state")</script>';
        } else {
            echo '<script type="text/javascript"> alert("Error: ID not found or already inactive")</script>';
        }
    } else {
        echo '<script type="text/javascript"> alert("Error")</script>';
    }
}

if(isset($_POST['delete'])){
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $busid = $_POST['id'];

    $sql = "SELECT `status` FROM `stoppage` WHERE `busid`='$busid'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 0) {
        echo '<script type="text/javascript"> alert("Error: ID not found")</script>';
    } else {
        $row = mysqli_fetch_assoc($result);
        $status = $row['status'];

        if($status == 'active' || $status == 'inactive') {
            $delete_sql = "DELETE FROM `stoppage` WHERE `busid`='$busid'";
            $delete_qry = mysqli_query($conn, $delete_sql);

            if ($delete_qry) {
                echo '<script type="text/javascript"> alert("Bus ID Deleted")</script>';
            } else {
                echo '<script type="text/javascript"> alert("Error while deleting..")</script>';
            }
        } else {
            echo '<script type="text/javascript"> alert("Error: Cannot delete record with status ' . $status . '")</script>';
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>STOPPAGE</title>
    <!--Favicon-->
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <!--css-->
    <link rel="stylesheet" href="styles.css">
</head>
<style>
</style>

<body>
    <!--Home image-->
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1>STOPPAGE </h1>
    </header><br><br>
    <! script to go home-->
        <script>
        function back() {
            location.href = "Transport.php"
        }
        </script>
        <main>
            <!-- form code -->
            <form action="" method="post" enctype="multipart/form-data">
                <div class="myDiv">
                    <label id="l1" name="bus_id" for="bus" style="color:red;">Bus ID : </label>
                    <input type="text" id="txt" name="id" required> <br><br>
                    <label for="">Status : </label>
                    <input type="text" value="inactive" name="status" id="txt" readonly> <br> <br>
                    <a>
                        <button type="submit" class="btn" name="insert" value="Insert">Insert</button>
                        <button type="submit" class="btn" name="active" value="Active">Active </button>
                        <button type="submit" class="btn" name="inactive" value="Inactive">Inactive </button>
                        <button type="submit" class="btn" name="delete" value="Delete">Delete </button>
                    </a>
                </div>
            </form>
        </main>
</body>

</html>