<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <title>Coding Forums!</title>
  </head>
  <body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>
   


<!-- jumbotron starts here -->
<?php 
$id = $_GET['threadid'];
$sql="SELECT * FROM `threads` WHERE thread_id = $id";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result))
{
  $title = $row['thread_title'];
  $desc = $row['thread_description'];
  $thread_user_id = $row['thread_user_id'];
  // Query the users table to find out the name of Original Poster
  $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
  $result2 = mysqli_query($conn, $sql2);
  $row2 = mysqli_fetch_assoc($result2);
  $posted_by = $row2['user_email'];
}
?>
<?php
$showAlert = false;
$method = $_SERVER['REQUEST_METHOD'];
if ($method=='POST'){
  // Insert into comment db
  $comment= $_POST['comment'];
  // prevent XSS attack
  $comment = str_replace("<", "&lt;", $comment);
  $comment = str_replace(">", "&gt;", $comment);
  $sno= $_POST['sno'];
  $sql = "INSERT INTO `comments` ( `comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ( '$comment', '$id', '$sno', current_timestamp())";
  $result = mysqli_query($conn, $sql);
  $showAlert = true;
  if($showAlert){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Your comment has been added! Please wait for respond.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>';
  }
}
?>
<!-- jumbotron starts here -->
  <div class="container my-4">
  <div class="jumbotron">
  <h1 class="display-4"><?php echo $title;?> Forums</h1>
  <p class="lead"><?php echo $desc ?>.</p>
  <hr class="my-4">
  <p>Be polite and open-minded, even if you disagree with others
  <br>Focus on the content of your post, not on the person who posted it</br>
      Don't post anything which is sexually explicit and avoid offensive, abusive, or hate speech.
  <br>Keep language, links, and images safe for family and friends</br></p>
  <p>POST BY: <b><?php echo $posted_by; ?></b>
  </p>
</div>
</div>
<?php
// check if the user is logged in and display the form to post a comment
 if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
echo '<div class="container">
  <h1 class="my py-2">Post a Comment</h1>
<form action="' . $_SERVER['REQUEST_URI'] .'" method="post">
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Type Your comment</label>
    <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
    <input type="hidden" name="sno" value="'. $_SESSION["sno"].'">
  </div>
  <button type="submit" class="btn btn-success">Post Comment</button>
</form>
</div>';
 }
  else
  //If the user is not logged in, display a message
    {
    echo '<div class="container">
    <h1 class="my py-2">Post a Comment</h1>
    <p class="lead">You are not logged in. Please login to be able to post comment</p>
    </div>';
  }
?>
<div class="container">
  <h1 class="my py-2">Discussions</h1>
<?php 
// Fetch the comments
  $id = $_GET['threadid'];
  $sql = "SELECT * FROM `comments` WHERE thread_id = $id";
  $result = mysqli_query($conn, $sql);
  $noResult = true;
      while ($row = mysqli_fetch_assoc($result)) {
          $noResult = false;
          $id = $row['comment_id'];
          $content = $row['comment_content'];
          $comment_time = $row['comment_time'];
          $thread_user_id = $row['comment_by'];
          $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
          $result2 = mysqli_query($conn, $sql2);
          $row2 = mysqli_fetch_assoc($result2);
          echo '<div class="media my-3">
          <img class="mr-2" src="user.png" height="100px" width="100px" alt="Generic placeholder image">
          <div class="media-body">
          <p class="font-weight-bold my-0">'. $row2['user_email'].' at '. $comment_time. '</p>
            '. $content.'
          </div>
        </div>';
      }
if ($noResult){
  echo '<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-4">No Comments Found</h1>
    <p class="lead"><b>Be the first person to ask a question</p>
  </div>
</div>';
}
?>
    <?php include 'partials/_footer.php'; ?>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    -->
  </body>
</html>