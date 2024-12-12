<?php
session_start();


if (isset($_SESSION['user_id'])){
$username = $_SESSION['username'];
//HTML tags to make it less buggy
echo <<<HTML
<nav class='navbar navbar-expand-lg navbar-dark bg-dark'>
  <div class='container-fluid'>

    <!-- Brand Name -->
    <a class='navbar-brand' href='#'>Tsundoku</a>

    <!-- Toggler Button for Mobile View -->
    <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
      <span class='navbar-toggler-icon'></span>
    </button>

    <!-- Collapsible Content -->
    <div class='collapse navbar-collapse' id='navbarSupportedContent'>

      <!-- Navigation Links -->
      <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
        <li class='nav-item'>
          <a class='nav-link active' aria-current='page' href='/RPG-Character-Management-System/index.php'>Home</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link active' href='/RPG-Character-Management-System/Pages/createCharacter.php'>Create</a>
        </li>
      </ul>

      <!-- Username Display -->
      <span class='navbar-text me-3'>
        $username
      </span>

      <!-- Logout Button -->
      <form action="/RPG-Character-Management-System/Configs/logout.php" method="post" class="d-inline-block">
        <button class='btn btn-outline-light btn-sm'>Logout</button>
      </form>

    </div>

  </div>
</nav>
HTML;

} 

else {
//HTML tags to make it less buggy
echo <<<HTML
<nav class='navbar navbar-expand-lg bg-dark' data-bs-theme='dark' style='color: white;'>
  <div class='container-fluid'>

    <a class='navbar-brand text-white' href='#'>Tsundoku</a>
    <button class='navbar-toggler text-white border-0' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
      <span class='navbar-toggler-icon'></span>
    </button>

    <div class='collapse navbar-collapse' id='navbarSupportedContent'>
      <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
        <!--Home button-->
        <li class='nav-item'>
          <a class='nav-link text-white active' aria-current='page' href='/RPG-Character-Management-System/index.php'>Home</a>
        </li>

      </ul>
    </div>

    <a href="http://localhost:801/RPG-Character-Management-System/Pages/login.php">Log in to add Characters!</a>

  </div>

</nav>
HTML;
}