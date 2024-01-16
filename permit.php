<!-- permit.php -->
<html>
<head>
    <title>PERMIT CERTIFICATE</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="project.css">
</head>

<body>
    <header>
    <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
        <h1> PERMIT CERTIFICATE </h1>
    </header><br><br>
    <script>
function back()
{
 location.href="Transport.php"
}
</script>
    <main>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mydiv">
            <h style="margin-left:83px;">
            <label for="bus" style="color:red;">Enter Bus ID  </label>
            <input type="text" id="txt" name = "id" required ><span>&emsp;</span>
            <input id="btn1" type="submit" name="submit" value="Display"> <br> <br></h>
            <label id="" name="permitno" for="bus">Permit No , EIB </label>
            <input type="text" id="txt" name = "permitno"><br> <br> 
            <label id="" name="registration" for="bus">Registration Mark </label>
            <input type="text" id="txt" name = "registrationmark"><br> <br>
            <label id="" name="makers" for="bus">Makers Name</label>
            <input type="text" id="txt" name = "makers"><br> <br> 
            <label>From  </label>
            <input type="date" name="from_date" id="from_date"> <br> <br>
            <label>To </label> 
            <input type="date" name="to_date" id="to_date"> <br> <br>
            <label>Renewed Date </label> 
            <input type="date" name="renew_date" id="renew_date"> 
            <br> <br>
            <input type="file" name="uploadfile" id="f1">
            <input type="submit" id="btn1" name="permitfile" value="Upload">
            </div>
        </form><br><br>
</main>
</body>
</html>
<?php

        // Check if the submit button has been clicked
if(isset($_POST['submit'])){
    $conn = mysqli_connect("localhost", "root", "", "transport");
    // Get the ID input value
    $busid = $_POST['id'];

    // to get the above data from the table
    $query = "select todate, permitno, registration, makers, reneweddate from permit where id = '$busid'";
    $res=mysqli_query($conn,$query);
    
    // Check if the bus ID exists in the table
    if (mysqli_num_rows($res) == 0) {

        // Display an error message if the bus ID is not found
        echo "<script type='text/javascript'> alert('Bus ID not found!!!'); </script>";
    } else {
        // Search for PDF files in the "pdfs" directory with a file name that contains the ID
        $pdfs = glob('permitpdf/*' . $busid . '*.pdf');
        session_start();
        $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
        $row=mysqli_fetch_array($rs);
        $username=$row["username"];
        $password=$row["password"];

        //if it matches the username and password of admin
        if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){

        // Display the PDFs in a table or list
        echo "<table>";
        echo "<tr><th>BUS ID</th><th>FROM DATE</th><th>TO DATE</th><th>PERMIT NO, EIB</th><th>REG MARK</th><th>MAKERS NAME</th><th>RENEWED DATE</th><th>FILE</th><th>DELETION</th></tr>";
        foreach ($pdfs as $pdf ) {
            $row1=mysqli_fetch_array($res);

            // Get the data from the database
            $to_date=$row1['todate'];
            $permitno=$row1['permitno'];
            $registration=$row1['registration'];
            $makers=$row1['makers'];
            $reneweddate=$row1['reneweddate'];
            $filename = basename($pdf);

            // Get the from date from the filename
            $from_date = substr($filename, strpos($filename, '_') + 1, -4);
            $combined_name = basename($pdf);

            // Display the PDF file information in a table row
            echo "<tr><td>" . $busid . "</td><td>" . $from_date . "</td><td>" . $to_date . "</td><td>" . $permitno . "</td><td>" . $registration . "</td><td>" . $makers . "</td><td>" . $reneweddate . "</td><td><a href='" . $pdf . "'>Download</a></td><td> <a href='?delete=" . $combined_name . "'>Delete</a></td></tr>";
        }
        echo "</table>";
    }else{
        // Display the PDF files in a table or list without the delete option - not admin
        echo "<table>";
        echo "<tr><th>BUS ID</th><th>FROM DATE</th><th>TO DATE</th><th>PERMIT NO, EIB</th><th>REG MARK</th><th>MAKERS NAME</th><th>RENEWED DATE</th><th>FILE</th></tr>";
        foreach ($pdfs as $pdf ) {
            $row1=mysqli_fetch_array($res);
            $to_date=$row1['todate'];
            $permitno=$row1['permitno'];
            $registration=$row1['registration'];
            $makers=$row1['makers'];
            $reneweddate=$row1['reneweddate'];
            $filename = basename($pdf);
            $from_date = substr($filename, strpos($filename, '_') + 1, -4);
            $combined_name = basename($pdf);
        
            echo "<tr><td>" . $busid . "</td><td>" . $from_date . "</td><td>" . $to_date . "</td><td>" . $permitno . "</td><td>" . $registration . "</td><td>" . $makers . "</td><td>" . $reneweddate . "</td><td><a href='" . $pdf . "'>Download</a></td></tr>";   
        }
        echo "</table>";
    }
}
}
?>
<?php
if(isset($_GET['delete'])) { //checks if the 'delete' parameter is present in the URL

    // establishes a connection to the database -> 'transport'
    $conn = mysqli_connect("localhost", "root", "", "transport");

    // retrieves the name of the file to be deleted from the 'delete' parameter in the URL
    $delete_file = $_GET['delete'];

    // extracts the bus ID from the file name by getting the substring from beginning of the file till before (_)
    $bus_id = substr($delete_file,0,strpos($delete_file, '_') );

    // date from the file name - from +1(after _) to -4(before .pdf)
    $date = substr($delete_file, strpos($delete_file, '_') + 1, -4);

    // selects the todate of the bus from the 'permit' table for the specified bus ID and date.
    $query = "select todate from permit where id = '$bus_id' and fromdate='$date'";
    $res1 = mysqli_query($conn,$query);

    // to delete the record for the specified bus ID and date from the 'permit' table.
    $qry = "DELETE from permit where id='$bus_id' and fromdate='$date'";
    $res = mysqli_query($conn,$qry);
    if($res) { // If the query to delete the record(s) from the permit table was successful

        //Check if the file associated with the deleted record exists in the permitpdf directory
        if(file_exists('permitpdf/' . $delete_file)) {

            // if file exists delete it from the permitpdf directory
            unlink('permitpdf/' . $delete_file);

            //file and record both deleted successfully
            echo "<script type='text/javascript'> alert('File and record deleted Successfully'); window.location.href = 'permit.php';</script>";
        } else {
            //if the file associated with the deleted record does not exist
            echo "<p style='text-align:center; color:green;'>Record deleted successfully but not file.</p>";
        }
    } else {
        // if not successful
        echo "<p style='text-align:center; color:red;'>Error deleting record: " . mysqli_error($conn) . "</p>";
    } 
}

if (isset($_POST['permitfile'])) {

    // Establish a connection to the transport database
    $conn = mysqli_connect("localhost", "root", "", "transport");

    // Retrieve values from the form
    $file = $_FILES['uploadfile']['name'];
    $busid = $_POST['id'];
    $permitno = $_POST['permitno'];
    $registration = $_POST['registrationmark'];
    $makers = $_POST['makers'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $renewed = $_POST['renew_date'];
    $pdf_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); // get the file extension
    $allowed_ext = array("pdf"); // allowed file extensions
    $filetmpname = $_FILES['uploadfile']['tmp_name'];
    $folder = 'permitpdf/'; // folder

    // Check if any of the form fields are empty
    if(empty($busid) || empty($permitno) || empty($registration) || empty($makers) || empty($from_date) || empty($to_date) || empty($renewed) || empty($file)){
        echo '<script type="text/javascript"> alert("Details not inserted. Please fill all the details")</script>';
    }
    else if(in_array($pdf_ext, $allowed_ext)) {
        // Check if the busid exists in the `masterdemo` table
        $sql = "SELECT * FROM `masterdemo` WHERE busid='$busid'";
        $qry = mysqli_query($conn, $sql);
        $result = mysqli_num_rows($qry);

        if($result == 0) {
             // If busid not found in the masterdemo table
             echo '<script type="text/javascript"> alert("Enter valid Bus ID!!")</script>';
            }
            else{
            // Move the uploaded file to a folder 
            move_uploaded_file($filetmpname, $folder.$busid.'_'.$from_date.'.pdf');

            // Insert the form values into the `permit` table
            $sql = "INSERT INTO `permit`(`id`, `pdf`, `fromdate`, `todate`, `permitno`, `registration`, `makers`, `reneweddate`) VALUES ('$busid','$file','$from_date','$to_date','$permitno','$registration','$makers','$renewed')";
            $qry = mysqli_query($conn, $sql);
            if ($qry) {
                echo '<script type="text/javascript"> alert("Details uploaded successfully")</script>';
            } else {
                echo '<script type="text/javascript"> alert("Error")</script>';
            }
        } 
    } else {
        // If uploaded file is not in pdf format
        echo '<script type="text/javascript"> alert("Invalid file type. Only PDF files are allowed.")</script>';
    }
}


?>