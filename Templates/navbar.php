<?php
//Claude told me to use those HTML tags to make it less buggy
echo <<<HTML
<nav class='navbar navbar-expand-lg bg-dark' data-bs-theme='dark' style='color: white;'>
  <div class='container-fluid'>
    <a class='navbar-brand text-white' href='#'>RPG Characters Manager</a>
    <button class='navbar-toggler text-white border-0' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
      <span class='navbar-toggler-icon'></span>
    </button>

    <div class='collapse navbar-collapse' id='navbarSupportedContent'>
      <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
        <li class='nav-item'>
          <a class='nav-link text-white active' aria-current='page' href='/RPG-Character-Management-System/index.php'>Home</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link text-white active' href='/RPG-Character-Management-System/Pages/createCharacter.php'>Create</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
HTML;