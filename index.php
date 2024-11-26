<?php
    session_start();

    function getCharactersFromDB($filter = null) {
        require("Configs/db.config.php");
    
        // Accepted filters
        $accepted_columns = [
            "created_at",
            "campaign",
            "class",
            "level",
            "name",
            "race"
        ];
    
        // If no filter, return all characters
        if ($filter === null) {
            try {
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
                    attributes a ON c.id = a.character_id");
            
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                return null;
            }
        }
    
        // Translate filter
        $filter = match($filter) {
            "Created at" => "created_at",
            "Campaign" => "campaign",
            "Class" => "class",
            "Level" => "level",
            "Name" => "name",
            "Race" => "race",
            default => null
        };
    
        // Check if filter is valid
        if (!in_array($filter, $accepted_columns)) {
            error_log("Invalid filter: " . $filter);
            return null;
        }

        
    
        try {
            // Selectng the characters in the DB using provided filter
            $filter = "c." . $filter;

            $stmt = $pdo->prepare("SELECT 
                c.id, 
                c.name, 
                c.race, 
                c.class, 
                c.age, 
                c.level,
                c.background,
                c.campaign,
                c.created_at,
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
            ORDER BY " . $filter . " ASC");
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }

    //At the start of the page
    $characters = getCharactersFromDB(null);
    
    //Filtering using the GET method
    if ($_SERVER['REQUEST_METHOD'] == "GET"){
        if (isset(($_GET['filter'])) && !empty($_GET['filter'])){
            $characters = getCharactersFromDB($_GET['filter']);
        } else{
            $characters = getCharactersFromDB(null);
        }
    }


    //Deleting a character
    if (isset($_GET['delete'])){
      $stmt = $pdo->prepare("
      DELETE FROM attributes WHERE character_id = ?;
      DELETE FROM characters WHERE id = ?;
      ");

      $stmt->execute([$_GET['delete'], $_GET['delete']]);
      
      $_SESSION['message'] = "Character deleted successfully!";
      header("Location: index.php");
      exit();
    }

    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPG Characters Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">  
    <link rel="stylesheet" href="Static/style.css">
</head>

<?php require_once("Templates/navbar.php"); ?>


<body data-bs-theme='dark'>
  <div class="container mt-4">
    <h2>Your Characters</h2>

    <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
                ?>
            </div>
    <?php endif; ?>
    
        <div class="mb-3">
            Filter By
            <form action="" method="get" class="d-flex justify-content-between align-items-center" >

                <!--- Check boxes for the specific filter --->
                <div class="mb-3">
                        <select name="filter" class="form-select d-flex justify-content-between align-items-center">
                            <?php 
                                $options = [
                                    "Created at",
                                    "Campaign",
                                    "Class",
                                    "Level",
                                    "Name",
                                    "Race"
                                ];
                                
                                foreach ($options as $option){
                                    echo <<<HTML
                                    <option value="$option">$option</option>
                                    HTML;
                                }
                            ?>
                        </select>

                        <button class="btn btn-outline-secondary" type="submit">Filter!</button>
                </div>
                
                <!--- Radios for ascending or descending order 
                <div class="d-flex">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ascending" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Ascending
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="descending" id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Descending
                        </label>
                    </div>
                </div>
                --->

            </form>
            
        </div>

    

    <div class="row">
    <?php foreach ($characters as $character): ?>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="mb-0"><?php echo htmlspecialchars($character['name']); ?></h5>

                    <div>
                        <span class="badge bg-secondary"><?php echo htmlspecialchars($character['race']); ?></span>
                        <span class="badge bg-primary"><?php echo htmlspecialchars($character['class']); ?></span>
                        <span class="badge bg-info">Level <?php echo $character['level']; ?></span>
                        <span class="badge bg-light text-dark">Age <?php echo $character['age']; ?></span>
                    </div>

                    <div class="text-edit">
                                <a href="/RPG-Character-Management-System/Pages/editCharacter.php?edit=<?php echo $character['id'];?>"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="?delete=<?php echo $character['id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this character?')">
                                    Delete
                                </a>
                    </div>

                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-4">
                            <div class="stat-box">
                                <div class="stat-label">STR</div>
                                <div class="stat-value"><?php echo $character['strength']; ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <div class="stat-label">DEX</div>
                                <div class="stat-value"><?php echo $character['dexterity']; ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <div class="stat-label">CON</div>
                                <div class="stat-value"><?php echo $character['constitution']; ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <div class="stat-label">INT</div>
                                <div class="stat-value"><?php echo $character['intelligence']; ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <div class="stat-label">WIS</div>
                                <div class="stat-value"><?php echo $character['wisdom']; ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <div class="stat-label">CHA</div>
                                <div class="stat-value"><?php echo $character['charisma']; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                            <h6>Campaign</h6>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($character['campaign'])); ?></p>
                    </div>

                    <?php if ($character['background']): ?>
                        <div class="mb-3">
                            <h6>Background</h6>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($character['background'])); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    

                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

<?php require_once("Templates/footer.php"); ?>


</html>