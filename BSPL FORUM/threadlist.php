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
 
    <?php 
$id = $_GET['catid'];
$sql = "SELECT * FROM `categories` where category_id = $id";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result))
{
  $id = $row['category_id'];
  $cat = $row['category_name'];
  $desc = $row['category_description'];
}
  ?>
<?php
$showAlert = false;
// check if the request method is post
$method = $_SERVER['REQUEST_METHOD'];
if ($method=='POST'){
  // Insert into thread db
  $th_title = $_POST['title'];
  $th_desc = $_POST['desc'];

  // prevent XSS attack
  $th_title = str_replace("<", "&lt;", $th_title);
  $th_title = str_replace(">", "&gt;", $th_title);
  $th_desc = str_replace("<", "&lt;", $th_desc);
  $th_desc = str_replace(">", "&gt;", $th_desc);
  $sno= $_POST['sno'];
  $sql = "INSERT INTO `threads` ( `thread_title`, `thread_description`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ( '$th_title', '$th_desc', '$id', '$sno', current_timestamp())";
  $result = mysqli_query($conn, $sql);
  $showAlert = true;
  if($showAlert){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Your thread has been added! Please wait for community to respond.
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
  <h1 class="display-4">Welcome to <?php echo $cat;?> Forums</h1>
  <p class="lead"><?php echo $desc ?>.</p>
  <hr class="my-4">
  <p>Be polite and open-minded, even if you disagree with others
  <br>Focus on the content of your post, not on the person who posted it</br>
  Don't post anything which is sexually explicit and avoid offensive, abusive, or hate speech.
  <br>Keep language, links, and images safe for family and friends</br></p>
  <p class="lead">
    <a class="btn btn-success btn-lg" href="#" role="button">Learn more</a></p>
</div>
</div>
<!--The form action attribute is set to the current URL using PHP -->
 <?php
 if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
echo '<div class="container">
  <h1 class="my py-2">Start a Discussion</h1>
  <!--The form is created with the action attribute set to the current URL using PHP-->
<form action="' . $_SERVER["REQUEST_URI"] .'" method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">Problem Title</label>
    <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
    <small id="emailHelp" class="form-text text-muted">Keep your Title crisp and clear</small>
  </div>
  <input type="hidden" name="sno" value="'. $_SESSION["sno"].'">
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Elaborate your concern</label>
    <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
  </div>
  <button type="submit" class="btn btn-success">Submit</button>
</form>
</div>';
 }
  else
  //If the user is not logged in, display a message
    {
    echo '<div class="container">
    <h1 class="my py-2">Start a Discussion</h1>
    <p class="lead">You are not logged in. Please login to be able to start a Discussion</p>
    </div>';
  }
?>
<div class="container" id="ques">
  <h1 class="my py-2">Browse Questions</h1>

<!-- Fetch all the threads -->
<?php 
$id = $_GET['catid'];
$sql = "SELECT * FROM `threads` where thread_cat_id = $id";
$result = mysqli_query($conn, $sql);
$noResult = true;
while($row = mysqli_fetch_assoc($result))
{
  $noResult = false;
  $id = $row['thread_id'];
  $title = $row['thread_title'];
  $desc = $row['thread_description'];  
  $thread_time = $row['timestamp'];
  // Fetch the user id from the threads table
  $thread_user_id = $row['thread_user_id'];
  // Fetch the user email from the users table
  $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
  $result2 = mysqli_query($conn, $sql2);
  $row2 = mysqli_fetch_assoc($result2);

  echo '<div class="media my-3">
  <img class="mr-2" src="user.png" height="100px" width="100px" alt="Generic placeholder image">
  <div class="media-body">' .
  '<h5 class="mt-0"> <a class="text-dark" href="thread.php?threadid=' . $id .'">' . $title. '</a></h5>
    '. $desc.' </div>'.'<div class="font-weight-bold my-0">Asked by: '. $row2['user_email'] .' at '. $thread_time. '</div>'.
  '</div>';
}
if ($noResult){
  echo '<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-4">No Threads Found</h1>
    <p class="lead"><b>Be the first person to ask a question</p>
  </div>
</div> </b>';
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