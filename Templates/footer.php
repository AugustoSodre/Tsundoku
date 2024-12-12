<?php 
//Claude told me to use those HTML tags to make it less buggy
echo  <<<HTML
<footer class='bg-dark text-light py-4 mt-5'>
    <div class='container'>
        <div class='row'>
            <div class='col-md-6 mb-3 mb-md-0'>
                <h5>Tsundoku</h5>
                <p class='mb-0'>'The best DnD 5e character manager!'</p>
            </div>
            <div class='col-md-6 text-md-end'>
                <h5>Contact Me</h5>
                <div class='social-links'>
                    <a href='https://github.com/AugustoSodre' target='_blank' class='text-light me-3'>
                        <i class='bi bi-github'></i> GitHub
                    </a>
                    <a href='https://www.linkedin.com/in/augusto-sodr%C3%A9-8167352a6/' target='_blank' class='text-light'>
                        <i class='bi bi-linkedin'></i> LinkedIn
                    </a>
                </div>
            </div>
        </div>
        <hr class='my-3'>
        <div class='text-center'>
            <small>&copy; 2024 Tsundoku RPG Character Manager. All rights reserved.</small>
        </div>
    </div>
</footer>
HTML;
?>