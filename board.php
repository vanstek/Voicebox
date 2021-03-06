<!--php code to check for logged in session-->
<?php
session_start();

// if (!isset($_SESSION['username'])) {
//     echo 'You must log in to visit this page. Returning to log in page.';
//     header('Refresh: 3; URL = login.php');
//     exit();
// }
include('config.php');

$image = '';
$board = '';
$error = 0;
if (htmlspecialchars($_GET["board_id"]) == 1) {
    $board = 'Eyesight Test Messageboard';
    $image = 'img\penguinglasses.gif';
} else if (htmlspecialchars($_GET["board_id"]) == 2) {
    $board = 'Pirate TTS Messageboard';
    $image = 'img\pirate.gif';
} else if (htmlspecialchars($_GET["board_id"]) == 3) {
    $board = 'Off-topic Messageboard';
    $image = 'img\random.gif';
} else {
    $error = 1;
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $board ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" type="image/png" href="img\cursor.ico" />
    <!-- bootstrap and fontawesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/4067afb655.js" crossorigin="anonymous"></script>


</head>

<body>

    <div class="container-fluid h-100">
        <div class="container-fluid h-100">
            <div class="row pt-md-0 pt-5">
                <div class="home-link col-2"><a class="home-link" href="index.php">Voicebox</a></div>
                <div class="game-header col-8 .game-header">Messageboards</div>
                <div class="col-2">
                    <!-- prints login/out button-->
                    <?php
                    if (!isset($_SESSION['username']) && empty($_SESSION['username'])) {
                        echo  "<button class='loginbutton' onClick=\"location.href='./login.php';\">
                        <span>Log in</span>
                    </button>";
                    } else if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                        echo  "<button class='loginbutton' onClick=\"location.href='./logout.php';\">
                        <span>Log out</span>
                    </button>";
                    }
                    ?>

                </div>
            </div>
            <div class="container d-flex justify-content-center flex-column mt-5 p-5 w-75 align-items-center">
                <?php
                //redirects if somehow navigated to nonexistent board
                if ($error == 1) {
                    header('Refresh: 0; URL = ./messageboards.php');
                }
                ?>
                <table class="table table-hover table-dark mt-5">
                    <h1 class="board-header">
                        <?php
                        echo $board;
                        ?>
                    </h1>
                    <thead class="thead-dark">
                        <tr>
                            <th>Thread</th>
                            <th>Posts</th>
                            <th>Author</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // for loop to create new rows in table. 
                        // queries for number of threads of board_id, then loops that many tyimes
                        // posts  # queries sum of posts of same idea_id (an idea is a thread; didnt want to delete thread table from homework 4)

                        $sql = "SELECT COUNT(*)
                        FROM ideas
                        WHERE board = '$board'";
                        $result = $con->query($sql);

                        //$count is the number of threads for a particular topic
                        $row = mysqli_fetch_row($result);
                        $count = $row[0];

                        //fetches rows of particular topic
                        $sql = "SELECT *
                FROM ideas
                WHERE board = '$board'";
                        $result = $con->query($sql);

                        //keeps threads in array $threads
                        while ($ideas = mysqli_fetch_assoc($result)) {

                            $sql = 'SELECT COUNT(*) FROM replies WHERE idea_id = ' . $ideas['idea_id'] . ';';
                            $count_result = $con->query($sql);
                            $count = mysqli_fetch_row($count_result);

                            echo '<tr>
                        <td><a href="./thread.php?threadID=' . $ideas['idea_id'] . '">' . $ideas['title'] . '</a></td>
                        <td>' . $count[0] . '</td>
                        <td>' . $ideas['username'] . '</td>
                        <td>' . $ideas['date'] . '</td>
                        </tr>';
                        }

                        $con->close();
                        ?>
                    </tbody>
                </table>
                <div class="container-fluid d-flex  flex-row justify-content-center p-4 my-5">
                    <a class="btn btn-primary mx-2" role="button" href="messageboards.php">Boards</a>
                    <?php
                    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                        echo '
                        <a class="mx-2 btn btn-primary" role="button" href="newthread.php?board_id=' . $_GET["board_id"] . '" title="new thread">Create new thread</a>';
                    }
                    ?>
                </div>




            </div>
        </div>

        <!-- bootstrap js -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
        </script>
        <!-- tilt.js -->
        <script src="./js/tilt.jquery.js"></script>
        <script>
            $('.gamecard').tilt({
                scale: 1.05,
                perspective: 2750,

            });
        </script>

</body>

</html>