<?php
session_start();

if (isset($_SESSION["email"]) && $_SESSION["username"]){
    header("Location: http://localhost:801/RPG-Character-Management-System/index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = htmlspecialchars($_POST["email"]);
    $possiblePassword = htmlspecialchars($_POST["pwd"]);


    try{
        //Connecting to DB
        require_once '../Configs/db.config.php';

        $query = 'SELECT username, pwd, id FROM users WHERE email = ?;';

        $statement = $pdo->prepare($query);

        $statement->execute([$email]);

        $result = $statement->fetchAll();

        $query = null;
        $statement = null;

        if (!empty($result)) {
            if (password_verify($possiblePassword, $result[0]['pwd'])){
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $result[0]['username'];
                $_SESSION['user_id'] = $result[0]['id'];
                $_SESSION['isLogged'] = true;
                header("Location: http://localhost:801/RPG-Character-Management-System/index.php");
                exit();
            } else {
                echo "Incorrect password!";
            }
        } else{
            echo "Incorrect Email or Password!";
        }
        
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column" style="min-height: 100vh;">
    <main class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4">Login</h2>

            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="example@gmail.com" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="pwd" id="password" class="form-control" placeholder="Password" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <p class="mb-1">Don't have an account?</p>
                <a href="signUp.php" class="text-decoration-none">Sign up!</a>
            </div>
        </div>
    </main>


<?php require_once("../Templates/footer.php"); ?>

</body>
</html>