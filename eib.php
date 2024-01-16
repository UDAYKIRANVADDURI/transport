<!-- eib.php -->
<html>
<head>
    <title>EIB CERTIFICATE</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="project.css">
   
</head>

<body>
    <header>
    <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
       <h1> EIB CERTIFICATE </h1>
    </header>
    <script>
function back()
{
 location.href="Transport.php"
}
</script>
    <main>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="myDiv">
            <h style="margin-left:85px;">
            <label id="l1" name="bus_id" for="bus" style="color:red;">Registration Number </label>
            <input type="text" id="txt" name = "id" required><span>&emsp;</span>
            <input id="btn3" type="submit" name="submit" value="Display"> <br> <br></h>
            <label id="" name="bus_id" for="bus">Date of Registration </label>
            <input type="date" id="txt" name = "date_of_reg"> <br> <br>
            <label id="" name="" for="bus">Registration Valid Upto  </label>
            <input type="date" id="txt" name = "reg_valid_upto"> <br><br>
            <label id="" name="" for="bus">Tax Valid Upto  </label>
            <input type="date" id="txt" name = "tax_valid_upto"> <br><br>
            <input type="file" name="uploadfile" id="f1">
            <input type="submit" id="btn2" name="eibfile" value="Upload">
            </div>
        </form>
</main>
</body>
</html>
<?php
 // Check if the submit button has been clicked
    if(isset($_POST['submit'])){

    // connection establishment to database transport
    $conn = mysqli_connect("localhost", "root", "", "transport");
    // Get the ID input value
    $busid = $_POST['id'];

    // to get the above data from the table
    $query = "select regvalidupto, taxvalidupto from eib where id = '$busid'";
    $res=mysqli_query($conn,$query);

    // Check if the bus ID exists in the table
    if(mysqli_num_rows($res) == 0){

        // Display an error message if the bus ID is not found
        echo "<script type='text/javascript'> alert('Bus ID not found!!!'); </script>";
    } else {
        // Search for PDF files in the "pdfs" directory with a file name that contains the ID
        $pdfs = glob('eibpdf/*' . $busid . '*.pdf');
        session_start();
        $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
        $row=mysqli_fetch_array($rs);
        $username=$row["username"];
        $password=$row["password"];

        //if it matches the username and password of admin
        if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){

        // Display the PDFs in a table or list
        echo "<table>";
        echo "<tr><th>BUS ID</th><th>DATE OF REG</th><th>REG VALID UPTO</th><th>TAX VALID UPTO</th><th>FILE</th><th>DELETION</th></tr>";
        foreach ($pdfs as $pdf ) {
            $row1=mysqli_fetch_array($res);

            // Get the data from the database
            $to_date=$row1['regvalidupto'];
            $taxvalidity=$row1['taxvalidupto'];
            $filename = basename($pdf);
            // Get the from date from the filename
            $from_date = substr($filename, strpos($filename, '_') + 1, -4);
            $combined_name = basename($pdf);

             // Display the PDF file information in a table row
            echo "<tr><td>" . $busid . "</td><td>" . $from_date . "</td><td>" . $to_date . "</td><td>" . $taxvalidity . "</td><td><a href='" . $pdf . "'>Download</a></td><td> <a href='?delete=" . $combined_name . "'>Delete</a></td></tr>";
        }
        echo "</table>";
        }else{
            // Display the PDF files in a table or list without the delete option - not admin
            echo "<table>";
            echo "<tr><th>BUS ID</th><th>DATE OF REG</th><th>REG VALID UPTO</th><th>TAX VALID UPTO</th><th>FILE</th></tr>";
            foreach ($pdfs as $pdf ) {
                $row1=mysqli_fetch_array($res);
                $to_date=$row1['regvalidupto'];
                $taxvalidity=$row1['taxvalidupto'];
                $filename = basename($pdf);
                $from_date = substr($filename, strpos($filename, '_') + 1, -4);
                $combined_name = basename($pdf);
            
                echo "<tr><td>" . $busid . "</td><td>". $from_date ."</td><td>". $to_date . "</td><td>". $taxvalidity . "</td><td><a href='" . $pdf . "'>Download</a></td></tr>";   
            }
            echo "</table>";
        }}
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

    // selects the regvalidupto of the bus from the 'eib' table for the specified bus ID and date.
    $query = "select regvalidupto from eib where id = '$bus_id' and dateofreg='$date'";
    $res1 = mysqli_query($conn,$query);

    // to delete the record for the specified bus ID and date from the 'eib' table.
    $qry = "DELETE from eib where id='$bus_id' and dateofreg='$date'";
    $res = mysqli_query($conn,$qry);
    if($res) { // If the query to delete the record(s) from the eib table was successful

        //Check if the file associated with the deleted record exists in the eibpdf directory
        if(file_exists('eibpdf/' . $delete_file)) {

            // if file exists delete it from the eibpdf directory
            unlink('eibpdf/' . $delete_file);

            //file and record both deleted successfully
            echo "<script type='text/javascript'> alert('File and record deleted Successfully'); window.location.href = 'eib.php';</script>";
        } else {
            //if the file associated with the deleted record does not exist
            echo "<p style='text-align:center; color:green;'>Record deleted successfully but not file.</p>";
        }
    } else {
        // if not successful
        echo "<p style='text-align:center; color:red;'>Error deleting record: " . mysqli_error($conn) . "</p>";
    } 
}

// Check if the form for adding a new eib record is submitted
if (isset($_POST['eibfile'])) {

    // Establish a connection to the transport database
    $conn = mysqli_connect("localhost", "root", "", "transport");

    // Retrieve values from the form
    $file = $_FILES['uploadfile']['name'];
    $pdf_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); // Get the file extension
    $allowed_ext = array("pdf"); // Allowed file extensions
    $regno = $_POST['id'];
    $dateofreg = $_POST['date_of_reg'];
    $regvalidity = $_POST['reg_valid_upto'];
    $taxvalidity = $_POST['tax_valid_upto'];
    $filetmpname = $_FILES['uploadfile']['tmp_name'];
    $folder = 'eibpdf/'; // Folder

    // Check if any of the form fields are empty
    if(empty($regno) || empty($dateofreg) || empty($regvalidity) || empty($taxvalidity) || empty($file)){
        echo '<script type="text/javascript"> alert("Details not inserted. Please fill all the details")</script>';
    } else {
        // Check if the uploaded file is a PDF file
        if(in_array($pdf_ext, $allowed_ext)) {

            // Check if the busid exists in the masterdemo table
            $check_sql = "SELECT * FROM `masterdemo` WHERE `busid`='$regno'";
            $check_qry = mysqli_query($conn, $check_sql);
            if(mysqli_num_rows($check_qry) == 0) {
                // If busid does not exist in the masterdemo table
                echo '<script type="text/javascript"> alert("Enter valid Bus ID!!!")</script>';
            }else{
                // Move the uploaded file to a folder 
                move_uploaded_file($filetmpname, $folder.$regno.'_'.$dateofreg.'.pdf');

                // Insert the form values into the `eib` table
                $sql = "INSERT INTO `eib`(`id`, `dateofreg`, `regvalidupto`, `taxvalidupto`, `pdf`) VALUES ('$regno','$dateofreg','$regvalidity','$taxvalidity','$file')";
                $qry = mysqli_query($conn, $sql);
                if ($qry) {
                    echo '<script type="text/javascript"> alert("Details uploaded successfully")</script>';
                } else {
                    echo '<script type="text/javascript"> alert("Error")</script>';
                }

            }

        } else {
            // If uploaded file is not in PDF format
            echo '<script type="text/javascript"> alert("Invalid file type. Only PDF files are allowed.")</script>';
        }
    }
}

?>