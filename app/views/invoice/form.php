<main class="app-main">
    <div class="app-content">
        <div class="container-fluid my-4">
            <h2><?= $data['title'] ?></h2>
            <form id="<?= $data['formId'] ?>">
                <?php if (isset($data['invoice'])): ?>
                    <input type="hidden" id="id" name="id" value="<?= $data['invoice']['id'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select select2" <?= empty($data['invoice']) ? 'disabled' : '' ?> required>
                        <option value="Draft" <?= (isset($data['invoice']) && $data['invoice']['status_invoice'] == "Draft") ? 'selected' : '' ?>>Draft</option>
                        <option value="Published" <?= (isset($data['invoice']) && $data['invoice']['status_invoice'] == "Published") ? 'selected' : '' ?>>Published</option>
                        <option value="Void" <?= (isset($data['invoice']) && $data['invoice']['status_invoice'] == "Void") ? 'selected' : '' ?>>Void</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="code" class="form-label">Invoice Code</label>
                    <input class="form-control" type="text" id="code" name="code" required value="<?= $data['invoice']['invoice_code'] ?? '' ?>">
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Client</label>
                    <select class="form-select select2" id="name" name="name">
                        <option value="">-- Pilih Client --</option>
                        <?php foreach ($data['clients'] as $client): ?>
                            <option value="<?= $client['id'] ?>"
                                <?= (isset($data['invoice']) && $data['invoice']['client_id'] == $client['id']) ? 'selected' : '' ?>>
                                <?= $client['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary"><?= $data['btnSubmit'] ?></button>
                <a href="<?= BASEURL ?>/Invoice" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</main>

<script src="<?= BASEURL ?>/js/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: "bootstrap-5",
        });

        // Create Handler
        $("#addInvoice").on("submit", function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: '<?= BASEURL ?>/Invoice/addInvoiceHeader',
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
                        $("#addInvoice")[0].reset();
                        setTimeout(function() {
                            window.location.href = "<?= BASEURL ?>/Invoice";
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

        // Edit Handler
        $("#editInvoice").on("submit", function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: '<?= BASEURL ?>/Invoice/updateInvoiceHeader',
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
                            window.location.href = "<?= BASEURL ?>/Invoice";
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