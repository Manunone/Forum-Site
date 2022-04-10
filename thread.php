<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <style>
        #ques{
            min-height: 200px;
        }
    </style>
    <title>Welcome to iDiscuss - Coding Forum</title>
</head>

<body>

    <?php include 'partials/_db_connect.php' ?>
    <?php include 'partials/_header.php' ?>

    <?php
        $id = $_GET['threadid'];
        $sql = "SELECT * FROM `threads` WHERE thread_id = $id";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $title = $row['thread_title'];
            $desc = $row['thread_desc'];
            $thread_user_id = $row['thread_user_id'];
            $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'"; 
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
        }
    ?>
    <?php
    $showAlert = false;
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $comment_content = $_POST['comment'];
        $sno = $_POST['sno'];
        $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ('$comment_content', '$id', '$sno', current_timestamp());";
        $result = mysqli_query($conn, $sql);
        $showAlert = true;
        if($showAlert){
            echo'<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your comment has been added! Please wait for community to respond.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
    }
    ?>
    <div class="container my-3">
        <div class="jumbotron">
            <h1 class="display-4"><?php echo $title;?></h1>
            <p class="lead"><?php echo $desc;?> </p>
            <hr class="my-4">

            <p>This is peer to peer. No Spam / Advertising / Self-promote in the forums. Do not post
                copyright-infringing material. Do not post “offensive” posts, links or images. Do not cross post
                questions. Do not PM users asking for help. Remain respectful of other members at all times.
            </p>
            <p>Published By: <b> <?php echo $row2['user_email']?></b></p>
        </div>
    </div>
    <?php
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
            echo '<div class="container">
            <h2>Post a Comment</h2>
            <form action="'. $_SERVER['REQUEST_URI'] .'" method="post">
                <div class="form-group">
                <label for="comment">Type your Comment</label>
                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                <input type="hidden" name="sno" value="'. $_SESSION['sno'] .'">
                </div>
                <button type="submit" class="btn btn-success my-3">Post Comment</button>
            </form>
        </div>';
        }
        else{
            echo '<div class="container">
            <h1 class="py-2">Start a Discussion</h1> 
               <p class="lead">You are not logged in. Please login to be able to start a Discussion</p>
            </div>';
        }
    ?>
    <div class="container" id="ques">
        <h2>Discussion</h2>
        <?php
            $id = $_GET['threadid'];
            $sql = "SELECT * FROM `comments` WHERE thread_id = $id";
            $result = mysqli_query($conn, $sql);
            $noresult = true;
            while($row = mysqli_fetch_assoc($result)){
                $noresult = false;
                $id = $row['comment_id'];
                $content = $row['comment_content'];
                $comment_time = $row['comment_time'];
                $comment_by = $row['comment_by'];
                $sql2 = "SELECT user_email FROM `users` WHERE sno='$comment_by'"; 
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_assoc($result2);
            echo '<div class="d-flex">
                <div class="flex-shrink-0 my-2">
                    <img src="img/userdefault.jpg" class="my-1" width="34px" height="30px" alt="...">
                </div>
                <div class="flex-grow-1 ms-3 my-1">
                '. $content .'
                </div>
                <p class="font-weight-bold my-0"><b>"'. $row2['user_email'] .'" at </b>
                '.$comment_time.'</p>
            </div>';
            }
            if($noresult){
                echo '<div class="jumbotron jumbotron-fluid">
                <div class="container">
                  <p class="display-4">No Threads Found</p>
                  <p class="lead">Be the First to ask a question.</p>
                </div>
              </div>';
            }
        ?>
    </div>
    <?php include 'partials/_footer.php' ?>
        <!-- Optional JavaScript; choose one of the two! -->

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous">
        </script>

        <!-- Option 2: Separate Popper and Bootstrap JS -->
        <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js" integrity="sha384-lpyLfhYuitXl2zRZ5Bn2fqnhNAKOAaM/0Kr9laMspuaMiZfGmfwRNFh8HlMy49eQ" crossorigin="anonymous"></script>
    -->
</body>

</html>