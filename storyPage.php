 <?php
    session_start();
    if (!isset($_SESSION['mysql'])) {
        $mysqli = mysqli_connect('localhost', 'homer', 'harambe', 'crowd_stories');   
    }



    function ragequit() {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        header('Location: /');
        $mysqli->close();
        exit;
    }
    if (isset($_GET["id"])) {
        $story_id = $_GET["id"];
                         
                $query = "SELECT * FROM story WHERE story_id=?";

                if ($stmt = $mysqli->prepare($query))
                {
                    $stmt->bind_param("i", $story_id);
                    if (!$stmt->execute()) {
                        ragequit();
                    }
                    
                    $stmt->store_result();
                    $stmt->bind_result($id, $title, $first, $text, $create_date, $max_votes_per, $max_num_sentences, $total_sentences, $total_votes);
                    if (!$stmt->fetch()) {
                        ragequit();
                    }
                    $stmt->close(); // close prepare statement
                } else {
                    ragequit();
                }
                
    }

    if (isset($_POST["upboat"]) && !isset($_SESSION['voted'])) {
        $query = "UPDATE sentence SET vote_count = (vote_count + 1) WHERE id = ",  ";"
    }
    
    if (isset($_POST['nextSentence'])) {
        $query = "INSERT INTO sentence (story_id, text) VALUES(?, ?)";
        if ($stmt = $mysqli->prepare($query)) {
            echo $_GET['id'];
            $stmt->bind_param("is", $story_id, $_POST['nextSentence']);
            if (!$stmt->execute()) {
                ragequit();
            }
            
            $stmt->close();
        } else {
            echo 'bk';
        }
    } else
        echo 'you are so bad';
        ?>
<html>
    <head>
  <title>Crowd Stories</title>

        
        
        
        
   <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
     
    <style>
        
        
        
        body {
            background-color: dimgrey;
            color: white;
        }
        
        #comment {
            opacity: 50%;
            font-style: italic;
        }
        
        tr td{
            display: block; 
            opacity: 70%;
            
        }
        
    </style>
        
         
</head>
    
    <body>
        
        
        <nav class="navbar navbar-inverse">
        <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="/">Crowd Stories</a>
        </div>
        <ul class="nav navbar-nav">
          <li class="active"><a href="/">Home</a></li>
          <li><a href="createStory.php">Start a Story</a></li>
        </div>
    </nav>

        <form action="storyPage.php?id=<?php echo $id;?>" method="post">
    <div class=" container-fixed container">
            
    
        <br>
        <div style="font-weight:normal;color:#EBEBEB;background-color:#2B3436;border: 5px solid #666E6A;letter-spacing:0pt;word-spacing:2pt;font-size:25px;text-align:center;font-family:arial, helvetica, sans-serif;line-height:4;">Crowd Stories</div>
        <br>            <br>
        <div class="md-4"><h1><?php echo $title;?></h1></div> <div class="md-4"></div> <div class="md-4"></div>
        
        <br>            <br>
        <div class="md-4"></div> <div class="md-4"><h4></h4><?php echo $text;?></h4></div> <div class="md-4"></div>
        <br>
        <br>            
        <label for="comment">Your sentence:</label>
        <input class="form-control" rows="5" name="nextSentence" type="text">

    </div>
    <br>
    <br>
    
    <div class="col-md-4"></div><div class="col-md-6"></div><div class="col-md-2 center-block"><input type="submit" class="btn btn-primary center-block"></div>
    
    <table class="table table-inverse">
              <thead>
                <tr>
                  <th>#</th>
                  <th>User Submitted Sentences</th>
                </tr>
              </thead>
              <tbody>
            <?php
                if (!isset($mysqli))
                    $mysqli = mysqli_connect('localhost', 'homer', 'harambe', 'crowd_stories');     
                $query = "SELECT max_num_sentences FROM story WHERE story_id=?";
                if ($stmt = $mysqli->prepare($query)) {
                    $stmt->bind_param("i", $story_id);
                    if (!$stmt->execute()) {
                        ragequit();
                    }
                    
                    $stmt->store_result();
                    
                    $stmt->bind_result($max_num_sentences);
                    $stmt->fetch();
                    $stmt->close();
                }
                
                       
                $query = "SELECT * FROM sentence WHERE story_id=? ORDER BY vote_count DESC";

                if ($stmt = $mysqli->prepare($query))
                {
                    $stmt->bind_param("i", $_GET['id']);
                    if (!$stmt->execute())
                        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            
                    //  echo "hello2";
                    $stmt->store_result();
                    //  echo "hello3";
                    $stmt->bind_result($id, $story_id, $first, $vote_count);
            
                    //   echo "hello4";
                    for ($i=0, $arr=[]; $stmt->fetch(); $i++) {
                        $row = new stdClass();
                        $row->id = $story_id;
                        $row->title = $title;
                        $row->first = $first;
                        $row->total_votes = $total_votes;
                        $arr[$i] = $row;
                    }
            
                    $stmt->close(); // close prepare statement
                }
        ?>
                <?php
                  foreach($arr as $i => $sentence) {?>
                <tr>
                  <th scope="row"><?php echo $i+1;?></th>
                    <td>
                        <h6><?php echo $sentence->first;?></h6>
                        <div style="float:right;" type="button">+<?php echo $story->total_votes;?>
                            <input name="upboat" class="btn glyphicon glyphicon-circle-arrow-up">
                        </div>
                    </td>
                </tr>
                <?php }?>
              </tbody>
            </table>
        </form>
        
    </body>
    
</html>

<?php $mysqli->close();?>
