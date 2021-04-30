<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo "$title"; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/mystyle.css">
    <script type="text/javascript" href="js/bootstrap.bundle.js"></script>
    <script type="text/javascript" href="js/jquery-3.5.1.slim.min.js"></script>
    <script>
        var topBtn = document.getElementById("topBtn");
        function topFunction() {
          document.documentElement.scrollTo({
            top: 0,
            behavior: "smooth"
          })
        }
        topBtn.addEventListener("click",topFunction);
    </script>
</head>
<body>