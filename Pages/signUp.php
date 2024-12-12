<?php
    session_start();
    //Preparing SQL
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        //Getting the parameters from POST method
        $form_username =  htmlspecialchars($_POST['username']);
        
        $email = htmlspecialchars($_POST['email']);
        $pwd = htmlspecialchars($_POST['pwd']);

        if ($form_username == ""){
            echo "You must have a Username!";
        } else if ($email == ""){
            echo "You must insert an Email!";
        } else if ($pwd == ""){
            echo "You must insert a Password!";
        } else if (substr_compare($pwd, $form_username,0, strlen($pwd)) == 0){
            echo "The Username and Password have to be different!";
        } else if (substr_compare($pwd, $email,0, strlen($pwd)) == 0){
            echo "The Email and Password have to be different!";
        } 
        else{
            $pwd = password_hash($pwd, PASSWORD_BCRYPT);
            

            try {
                //Connecting to the DB
                require_once ("../Configs/db.config.php");

                // Add more robust validation
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
                $stmt->execute([$form_username]);

                
                
                if ($stmt->fetchColumn() > 0) {
                    echo "Username already exists. Please choose another.";
                    exit();
                }

                //SQL Query
                $query = "INSERT INTO users(email, pwd, username) VALUE(?, ?, ?);";

                //Preparing to launch it to the DB safely
                $statement = $pdo->prepare($query);

                // Add this right before the execute() method
                

                //Putting the treated variables into the placeholders
                $statement->execute([$email, $pwd, $form_username]);

                $query = null;
                $statement = null;

                header("Location: http://localhost:801/RPG-Character-Management-System/index.php");

                exit();

            } catch (Exception $e) {
                
                echo "Querry failed:" . $e->getMessage();
            }
        }

        
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column" style="min-height: 100vh;">
    <main class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4">Let's Sign Up!</h2>

            <form action="signUp.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="example@gmail.com" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="pwd" id="password" class="form-control" placeholder="Password" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Sign Up!</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <p class="mb-1">Already have an account?</p>
                <a href="login.php" class="text-decoration-none">Login!</a>
            </div>
        </div>
    </main>


<?php require_once("../Templates/footer.php"); ?>

</body>
</html>


    