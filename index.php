<?php
    session_start();

    if (!isset($_SESSION['isLogged'])){
        header("Location: http://localhost:801/RPG-Character-Management-System/Pages/login.php");
    }

    //Function to fetch/catch all the characters from DB
    function getCharactersFromDB($filter = "Created At", $order = "asc") {
        require("Configs/db.config.php");
        
        //-----------------Filter validation-------------//
        
        // Accepted filters
        $accepted_columns = [
            "created_at",
            "campaign",
            "class",
            "level",
            "name",
            "race"
        ];
    
        // Translate filter
        $filter = match($filter) {
            "Created At" => "created_at",
            "Campaign" => "campaign",
            "Class" => "class",
            "Level" => "level",
            "Name" => "name",
            "Race" => "race",
            default => "created_at"
        };
    
        // Check if filter is valid, within the accepted columns
        if (!in_array($filter, $accepted_columns)) {
            error_log("Invalid filter: " . $filter);
            return null;
        }

        //-----------------------------------------------//
        


        //--------------Order Validation-----------------//

        // Accepted Orders
        $accepted_orders = ["ASC", "DESC"];

        

        //Translating Orders
        $order = match($order){
            "asc" => "ASC",
            "desc" => "DESC",
            default => "ASC"
        };

        

        //Check if Order is valid, within the accepted orders
        if (!in_array($order, $accepted_orders)){
            error_log("Invalid order: " . $order);
            return null;
        }

        //-----------------------------------------------//

        
        
        
        //--------------DataBase Queries-----------------//
        
        try {
            // Selecting the characters in the DB using provided filter

            $filter = "c." . $filter;
            $user_id = $_SESSION['user_id'];

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
            WHERE 
                c.user_id = $user_id
            ORDER BY " . $filter . " " . $order . ";");
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        } catch (PDOException $e) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }
    //-----------------------------------------------//


    //At the start of the page
    $characters = getCharactersFromDB("Created At");
    
    //Filtering using the GET method
    if ($_SERVER['REQUEST_METHOD'] == "GET"){
        if (isset(($_GET['filter'])) && !empty($_GET['filter'])){
            $order = isset($_GET['order']) ? $_GET['order'] : 'asc';
            $characters = getCharactersFromDB($_GET['filter'], $order);
        } else{
            $characters = getCharactersFromDB("Created At", "asc");
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
    
    <div class="container-fluid mb-3">
    <div class="row align-items-center">
        <div class="col-12">
            <h6 class="mb-2 text-muted">Filter By</h6>
            <form action="" method="get" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <select name="filter" class="form-select">
                        <?php 
                            //Default Options for filtering
                            $options = [
                                "Created At",
                                "Campaign",
                                "Class",
                                "Level",
                                "Name",
                                "Race"
                            ];
                            
                            //If an option is already selected:
                            if (isset($_GET['filter']) && $_GET['filter'] != $options[0]){
                                $oldOption = $options[0]; // Getting the old first option
                                
                                $options = array_diff($options, [$_GET['filter']]); //Removing the filter, that was still in the old position

                                $options[0] = $_GET['filter']; // The first option will be the selected filter!

                                array_push($options, $oldOption); //The old first option will get to the end of the list!
                            }
                            
                            foreach ($options as $option){
                                $selected = (isset($_GET['filter']) && $_GET['filter'] == $option) ? 'selected' : '';
                                echo "<option value=\"$option\" $selected>$option</option>";
                            }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="order" id="ascendingRadio" value="asc">
                            <label class="form-check-label" for="ascendingRadio">
                                Ascending
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="order" id="descendingRadio" value="desc">
                            <label class="form-check-label" for="descendingRadio">
                                Descending
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <button class="btn btn-outline-primary w-100" type="submit">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
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