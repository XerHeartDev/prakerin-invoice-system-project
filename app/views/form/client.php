<main class="app-main">
    <div class="app-content">
        <div class="container-fluid my-4">
            <h2><?= $data['title'] ?></h2>
            <form id="<?= $data["formId"] ?>">
                <?php if (isset($data['client'])): ?>
                    <input type="hidden" id="id" name="id" value="<?= $data['client']['id'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="status_code" class="form-label">Status</label>
                    <select name="status_code" id="status_code" class="form-select" required>
                        <option value="1" <?= (isset($data['client']) && $data['client']['status_code'] == 1) ? 'selected' : '' ?>>Active</option>
                        <option value="9" <?= (isset($data['client']) && $data['client']['status_code'] == 9) ? 'selected' : '' ?>>Inactive</option>
                        <option value="0" <?= (isset($data['client']) && $data['client']['status_code'] == 0) ? 'selected' : '' ?>>Pending</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="<?= $data['client']['name'] ?? '' ?>">
                </div>
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input class="form-control" type="email" id="email" name="email" value="<?= $data['client']['email'] ?? '' ?>">
                </div>
                <div class="mb-3">
                    <label for="phone">Phone</label>
                    <input class="form-control" type="tel" id="phone" name="phone" value="<?= $data['client']['phone'] ?? '' ?>">
                </div>
                <div class="mb-3">
                    <label for="balance">Balance</label>
                    <input class="form-control" type="number" id="balance" name="balance" value="<?= $data['client']['balance'] ?? '' ?>">
                </div>

                <button type="submit" class="btn btn-primary"><?= $data['btnSubmit'] ?></button>
                <a href="<?= BASEURL ?>/Dashboard/client" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</main>

<script src="<?= BASEURL ?>/js/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        //Create Handler
        $("#addClient").on("submit", function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: '<?= BASEURL ?>/Client/addClient',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status == 'success') {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            timer: 2500
                        });
                        $("#addClient")[0].reset();
                        setTimeout(function() {
                            window.location.href = "<?= BASEURL ?>/Dashboard/client";
                        }, 2500)
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message,
                            icon: "error",
                            timer: 2500
                        });
                    }
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

        //Edit Handler
        $("#editClient").on("submit", function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: '<?= BASEURL ?>/Client/updateClient',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            timer: 2500
                        });
                        setTimeout(function() {
                            window.location.href = "<?= BASEURL ?>/Dashboard/client";
                        }, 2500)
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message,
                            icon: "error",
                            timer: 2500
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    alert('Terjadi Kesalahan Pada Server');
                }
            })
        })
    });
</script>