<?php 
    session_start();
    require_once("../Configs/db.config.php");

    //Get method
    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['edit'])){
        //Getting the specific character
        $user_id = $_SESSION['user_id'];
        try{
            $id = $_GET['edit'];
            $stmt = $pdo->query("SELECT 
            c.id, 
            c.name, 
            c.race, 
            c.class, 
            c.age, 
            c.level,
            c.background,
            c.campaign,
            a.strength, 
            a.dexterity, 
            a.constitution, 
            a.intelligence, 
            a.wisdom, 
            a.charisma
            FROM 
                characters c
            INNER JOIN 
                attributes a ON c.id = a.character_id
            WHERE id = $id
            ");
        
            $character = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            } catch (PDOException $e){
            echo "Something went wrong: " . $e;
            }
    }

    
    //Post method
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_character'])){

        $id = $_GET['edit'];
        
        try{
        
        //Adding edited character
        $stmt = $pdo->prepare("UPDATE characters
            SET name = ?,
            race = ?,
            age = ?,
            class = ?,
            level = ?,
            campaign = ?,
            background = ?
        WHERE id = $id
        ;");

        $stmt->execute([
            $_POST['name'],
            $_POST['race'],
            $_POST['age'],
            $_POST['class'],
            $_POST['level'],
            $_POST['campaign'],
            $_POST['background'],
        ]);


        // Editing character's attributes (use UPDATE instead of INSERT)
        $stmt = $pdo->prepare("UPDATE attributes 
        SET strength = ?, 
            dexterity = ?, 
            constitution = ?, 
            intelligence = ?, 
            wisdom = ?, 
            charisma = ? 
        WHERE character_id = ?");

        $stmt->execute([
        $_POST['strength'],
        $_POST['dexterity'],
        $_POST['constitution'],
        $_POST['intelligence'],
        $_POST['wisdom'],
        $_POST['charisma'],
        $id // Make sure the attributes belong to the correct character
        ]);

        echo "You finished inserting everything!";

        $_SESSION['message'] = "Character edited successfully!";
        header("Location: /RPG-Character-Management-System/index.php");
        exit();

    } catch (PDOException $e){
        echo "Insertion error; " + $e;
    }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Character</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">  
</head>


<?php require_once("../Templates/navbar.php"); ?>

<body data-bs-theme='dark'>

    <div class="container mt-4">
        
        <!-- Form -->
        <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Edit Character</h5>
                    <form method="POST" id="characterForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Character Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="<?php echo $character[0]['name']?>" value="<?php echo $character[0]['name']?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Race</label>
                                    <input type="text" name="race" class="form-control" placeholder="<?php echo $character[0]['race']?>" value="<?php echo $character[0]['race']?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Age</label>
                                    <input type="text" name="age" class="form-control" placeholder="<?php echo $character[0]['age']?>" value="<?php echo $character[0]['age']?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Level</label>
                                    <input type="text" name="level" class="form-control" placeholder="<?php echo $character[0]['level']?>" value="<?php echo $character[0]['level']?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Class</label>
                                    <select name="class" class="form-control">
                                    <?php 
                                            $classes = [
                                                "Artificer",
                                                "Barbarian",
                                                "Bard",
                                                "Blood Hunter",
                                                "Cleric",
                                                "Druid",
                                                "Fighter",
                                                "Monk",
                                                "Paladin",
                                                "Ranger",
                                                "Rogue",
                                                "Sorcerer",
                                                "Warlock"
                                            ];

                                            foreach ($classes as $class){
                                                echo <<<HTML
                                                <option value=$class>$class</option>
                                                HTML;
                                            }

                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Strength</label>
                                            <input type="number" name="strength" class="form-control" min="1" max="20" placeholder="<?php echo $character[0]['strength']?>" value="<?php echo $character[0]['strength']?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Dexterity</label>
                                            <input type="number" name="dexterity" class="form-control" min="1" max="20" placeholder="<?php echo $character[0]['dexterity']?>" value="<?php echo $character[0]['dexterity']?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Constitution</label>
                                            <input type="number" name="constitution" class="form-control" min="1" max="20" placeholder="<?php echo $character[0]['constitution']?>" value="<?php echo $character[0]['constitution']?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Intelligence</label>
                                            <input type="number" name="intelligence" class="form-control" min="1" max="20" placeholder="<?php echo $character[0]['intelligence']?>" value="<?php echo $character[0]['intelligence']?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Wisdom</label>
                                            <input type="number" name="wisdom" class="form-control" min="1" max="20" placeholder="<?php echo $character[0]['wisdom']?>" value="<?php echo $character[0]['wisdom']?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Charisma</label>
                                            <input type="number" name="charisma" class="form-control" min="1" max="20" placeholder="<?php echo $character[0]['charisma']?>" value="<?php echo $character[0]['charisma']?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Campaign</label>
                                        <input type="text" name="campaign" class="form-control" placeholder="<?php echo $character[0]['campaign']?>" value="<?php echo $character[0]['campaign']?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        

                        <div class="mb-3">
                            <label class="form-label">Background Story</label>
                            <textarea name="background" class="form-control" rows="3" placeholder="<?php echo $character[0]['background']?>"><?php echo $character[0]['background']?></textarea>
                        </div>


                        <button type="submit" name="add_character" class="btn btn-primary">Save Character</button>

                    </form>
                </div>
            </div>

    </div>

    

</body>

<?php require_once("../Templates/footer.php"); ?>

</html>

