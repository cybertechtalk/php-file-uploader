<?PHP
    if(!empty($_FILES['uploaded_file']))
    {   
        $disallowed_ext = array(
            "php",
            "php3",
            "php4",
            "php5",
            "php7",
            "pht",
            "phtm",
            "phtml",
            "phar",
            "phps",
            "exe",
            "ps1"
        );
        $path = "images/";
        $path = $path . basename( $_FILES['uploaded_file']['name']);

        $tmp_name = $_FILES['uploaded_file']['tmp_name'];
        $name = $_FILES['uploaded_file']['name'];
        $parts = explode(".", $name);
        $ext = array_pop($parts);

        if (in_array($ext, $disallowed_ext, TRUE)) {
            die("$ext is not allowed");
        }

        if(move_uploaded_file($tmp_name, $path)) {
        echo "The file ".  basename($tmp_name). 
        " has been uploaded";
        } else{
            echo "There was an error uploading the file, please try again!";
        }
    }
?>


<!DOCTYPE html>
<html>
<head>
  <title>Upload your files</title>
</head>
<body>
  <form enctype="multipart/form-data" action="index.php" method="POST">
    <p>Upload your file</p>
    <input type="file" name="uploaded_file"></input><br />
    <input type="submit" value="Upload"></input>
  </form>
</body>
</html>
