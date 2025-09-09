<div class="container">
    <div class="jumbotron mt-4">
        <h1>Selamat Datang <?php echo $_SESSION["user"]["name"]; ?></h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Amet, voluptates?</p>
        <hr class="my-4">
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsum sapiente minima porro minus. Ducimus itaque sequi alias enim saepe fugiat?</p>
        <a href="<?= BASEURL ?>/Auth/logout" class="btn btn-primary" role="button">Log Out</a>
    </div>
</div>