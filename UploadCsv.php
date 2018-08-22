<title>Upload page</title>
<style type="text/css">
body {
    background: #E3F4FC;
    font: normal 14px/30px Helvetica, Arial, sans-serif;
    color: #2b2b2b;
}
p{
	font-size:24px;
	font-family:Tahoma, Geneva, sans-serif;
	color:#006;
 }
a {
    color:#898989;
    font-size:14px;
    font-weight:bold;
    text-decoration:none;
}
a:hover {
    color:#CC0033;
}
h1 {
    font: bold 14px Helvetica, Arial, sans-serif;
    color: #CC0033;
}
h2 {
    font: bold 14px Helvetica, Arial, sans-serif;
    color: #898989;
}
#container {
    background: #CCC;
    margin: 100px auto;
    width: 945px;
}
#form           {padding: 20px 150px;}
#form input     {margin-bottom: 20px;}
</style>
</head>
<body>

<div id="container">
<div id="form">
<?php
//Database Connection
//Connection variables
$host = 'localhost';  //The name of database host
$user = 'root';  //The Username of databse server
$pass = '';  //The Password of databse server
$db = 'safelybuy';    //Database name
$table_name = 'shipping';   //Database table to insert data into 
$conn = new mysqli($host,$user,$pass,$db);

if ($conn->connect_error) 
    die($conn->connect_error);

//Upload File
if (isset($_POST['submit'])) {
    $mimes = array('application/vnd.ms-excel','text/csv','text/tsv');
   
    if (is_uploaded_file($_FILES['filename']['tmp_name']) && in_array($_FILES['filename']['type'],$mimes)) {
        echo "<h1>" . "File ". $_FILES['filename']['name'] ." is being inserted to database, please wait..." . "</h1>";
        //Import uploaded file to Database
        $handle = fopen($_FILES['filename']['tmp_name'], "r");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Adjust the number of columns you want to insert to database here
            $import="INSERT into $table_name VALUES('','$data[0]','$data[1]','$data[2]','$data[3]')";
            $sqlresult = $conn->query($import);
        }

        if ($sqlresult === TRUE) {
        echo "New record created successfully";
        } 
        else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }
        echo "<h2>Displaying contents:</h2>";
        readfile($_FILES['filename']['tmp_name']);
        fclose($handle);
        print "Import done";
        //view upload form
    }
    else{
        echo "Error: Only csv files are allowed, please try again with a csv file";
    }
    
}
else {
    print "Upload new csv by browsing to file and clicking on Upload<br />\n";
    print "<form enctype='multipart/form-data' action='UploadCsv.php' method='post'>";
    print "File name to import:<br />\n";
    print "<input size='50' type='file' name='filename'><br />\n";
    print "<input type='submit' name='submit' value='Upload'></form>";
}
?>
</div>
</div>
</body>
