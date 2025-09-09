<main class="app-main">
    <div class="app-content">
        <div class="container-fluid my-4">
            <h2><?= $data['title'] ?></h2>
            <form id="<?= $data["formId"] ?>">
                <?php if (isset($data['product'])): ?>
                    <input type="hidden" id="id" name="id" value="<?= $data['product']['id'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label" for="status_code">Status</label>
                    <select name="status_code" id="status_code" class="form-select" required>
                        <option value="1" <?= (isset($data['product']) && $data['product']['status_code'] == 1) ? 'selected' : '' ?>>Active</option>
                        <option value="9" <?= (isset($data['product']) && $data['product']['status_code'] == 9) ? 'selected' : '' ?>>Inactive</option>
                        <option value="0" <?= (isset($data['product']) && $data['product']['status_code'] == 0) ? 'selected' : '' ?>>Pending</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="sku">SKU</label>
                    <input class="form-control" type="text" id="sku" name="sku" value="<?= $data['product']['sku'] ?? '' ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="<?= $data['product']['name'] ?? '' ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= $data['product']['description'] ?? '' ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="price">Price</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">Rp</span>
                        <input class="form-control" type="text" id="price" name="price" value="<?= $data['product']['price'] ?? '' ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class=" form-label" for="unit">Unit</label>
                    <input class="form-control" type="text" id="unit" name="unit" value="<?= $data['product']['unit'] ?? '' ?>">
                </div>
                <div class="mb-3">
                    <label class=" form-label" for="stock">Stock</label>
                    <input class="form-control" type="number" id="stock" name="stock" value="<?= $data['product']['stock'] ?? '' ?>">
                </div>

                <button type="submit" class="btn btn-primary"><?= $data['btnSubmit'] ?></button>
                <a href="<?= BASEURL ?>/Dashboard/product" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</main>

<script src="<?= BASEURL ?>/js/jquery-3.7.1.min.js"></script>
<script src="<?= BASEURL ?>/js/currency-formatter.js"></script>
<script>
    $(document).ready(function() {
        // Foramt Pada Awal Load
        const priceVal = $("#price").val();
        if (priceVal) {
            $("#price").val(currencyFormat(parseFloat(priceVal)));
        }

        //Create Handler
        $("#addProduct").on("submit", function(e) {
            e.preventDefault();
            const priceInput = $("#price");
            const price = priceInput.val();
            $("#price").val(currencyUnformat(price));
            const formData = $(this).serialize();
            $("#price").val(price);

            $.ajax({
                url: '<?= BASEURL ?>/Product/addProduct',
                method: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: response.status === 'success' ? "Success!" : "Error!",
                        text: response.message,
                        icon: response.status,
                        timer: 2500
                    });
                    $("#addProduct")[0].reset();
                    setTimeout(function() {
                        window.location.href = "<?= BASEURL ?>/Dashboard/product";
                    }, 2500)
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
        $("#editProduct").on("submit", function(e) {
            e.preventDefault();
            const priceInput = $("#price");
            const price = currencyUnformat(priceInput.val());
            $("#price").val(price);
            const formData = $(this).serialize();
            $("#price").val(currencyFormat(price));

            $.ajax({
                url: '<?= BASEURL ?>/Product/updateProduct',
                method: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: response.status === 'success' ? "Success!" : "Error!",
                        text: response.message,
                        icon: response.status,
                        timer: 2500
                    });
                    setTimeout(function() {
                        window.location.href = "<?= BASEURL ?>/Dashboard/product";
                    }, 2500)
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    alert('Terjadi Kesalahan Pada Server');
                }
            })
        })
    });
</script>