<?php
if (isset($_GET["source"])) 
    die(highlight_file(__FILE__));

session_start();

if (!isset($_SESSION["home"])) {
    $_SESSION["home"] = bin2hex(random_bytes(20));
}
$userdir = "images/{$_SESSION["home"]}/";
if (!file_exists($userdir)) {
    mkdir($userdir);
}

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

if (isset($_POST["upload"])) {
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        die("upload failed");
    }

    $tmp_name = $_FILES["image"]["tmp_name"];
    $name = $_FILES["image"]["name"];
    $parts = explode(".", $name);
    $ext = array_pop($parts);

    if (empty($parts[0])) {
        array_shift($parts);
    }

    if (count($parts) === 0) {
        die("filename is empty");
    }

    if (in_array($ext, $disallowed_ext, TRUE)) {
        die("$ext is not allowed");
    }

    $image = file_get_contents($tmp_name);
    if (mb_strpos($image, "<?") !== FALSE) {
        die("wrong image");
    }

    if (!exif_imagetype($tmp_name)) {
        die("not an image");
    }

    $name = implode(".", $parts);
    move_uploaded_file($tmp_name, $userdir . $name . "." . $ext);
}

echo "<h3>Your <a href=$userdir>files</a>:</h3><ul>";
foreach(glob($userdir . "*") as $file) {
    echo "<li><a href='$file'>$file</a></li>";
}
echo "</ul>";

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="h-64 border-solid border-black border-4 text-center" id="dropzone">
        Drop file here
    </div>

<form method="POST" action="?" enctype="multipart/form-data">
    <input type="file" name="image" id="files">
    <input type="submit" name=upload>
</form>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        var $dropzone = $('#dropzone');
        $dropzone.on('drag dragstart dragend dragover dragenter dragleave drop', function (event) {
            event.preventDefault();
            event.stopPropagation();
        }).on('drop', function(event) {
            document.querySelector('#files').files = event.originalEvent.dataTransfer.files;
        });
    </script>
</body>
</html>
