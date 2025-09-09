<div class="card card-outline card-primary my-5">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <a
                href="../index2.html"
                class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                <h1 class="mb-0"><b>Xer</b>Admin</h1>
            </a>
        </div>
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form id="loginUser">
                <div class="input-group mb-1">
                    <div class="form-floating">
                        <input id="email" name="email" type="email" class="form-control" value="" placeholder="" required />
                        <label for="email">Email</label>
                    </div>
                    <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                </div>
                <div class="input-group mb-1">
                    <div class="form-floating">
                        <input id="password" name="password" type="password" class="form-control" placeholder="" required />
                        <label for="password">Password</label>
                    </div>
                    <div class="input-group-text"><span class="bi bi-lock"></span></div>
                </div>
                <div class="row">
                    <div class="col-8 d-inline-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                            <label class="form-check-label" for="flexCheckDefault"> Remember Me </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Sign In</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="social-auth-links text-center mb-3 d-grid gap-2">
                <p>- OR -</p>
                <a href="#" class="btn btn-primary">
                    <i class="bi bi-facebook me-2"></i> Sign in using Facebook
                </a>
                <a href="#" class="btn btn-danger">
                    <i class="bi bi-google me-2"></i> Sign in using Google+
                </a>
            </div>
            <p class="mb-1"><a href="">I forgot my password</a></p>
            <p class="mb-0">
                <a href="<?= BASEURL ?>/Auth/registerPage" class="text-center"> Register a new account </a>
            </p>
        </div>
    </div>
</div>

<script src="<?= BASEURL ?>/js/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $("#loginUser").on("submit", function(e) {
            e.preventDefault();
            const dataForm = $(this).serialize();

            $.ajax({
                url: "<?= BASEURL ?>/auth/login",
                method: "POST",
                data: dataForm,
                success: function(res) {
                    if (res.status === "success") {
                        window.location.href = "<?= BASEURL ?>";
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: res.message,
                            icon: "error",
                            timer: 2500
                        })
                    }
                    $("#loginUser")[0].reset();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    Swal.fire({
                        title: "Error!",
                        text: "Terjadi Kesalahan Pada Server",
                        icon: "error",
                        timer: 2500
                    })
                }
            })
        })
    })
</script>