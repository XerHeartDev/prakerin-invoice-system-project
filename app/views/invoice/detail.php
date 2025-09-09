<main class="app-main">
    <div class="app-content">
        <div class="container-fluid my-4">
            <div class="d-flex justify-content-between">
                <h2 class="fw-bold">Invoice Code: <?= $data['invoice']['invoice_code'] ?></h2>
                <!-- Action Button -->
                <div class="d-flex align-items-center gap-2">
                    <a href="<?= BASEURL ?>/Invoice" class="btn btn-secondary">Back</a>
                    <button id="btnSaveChanges" class="btn btn-primary <?= $data["invoice"]["status_invoice"] === "Draft" ? "d-block" : "d-none" ?>">Save Changes</button>
                    <button id="btnPrintInvoice" class="btn btn-warning btn-print">Print Invoice</button>
                </div>
            </div>
            <h2 class="fw-bold">Client: <?= $data['client']['name'] ?></h2>

            <!-- Table Detail Invoice -->
            <div class="table-responsive mt-3">
                <table id="tableData" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <i class="bi bi-trash3"></i>
                            </th>
                            <th>Added At</th>
                            <th>Updated At</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <th class="border-end-0"></th>
                        <th class="border-0"></th>
                        <th class="border-0"></th>
                        <th class="border-0"></th>
                        <th class="border-start-0"></th>
                        <th>Grand Total:</th>
                        <th id="grandTotal"></th>
                    </tfoot>
                </table>
            </div>

            <hr>

            <!-- Form Produk -->
            <form id="formInvDetail" class="<?= $data["invoice"]["status_invoice"] === "Draft" ? "d-block" : "d-none" ?>">
                <input type="hidden" name="invoice_id" value="<?= $data['invoice']['id'] ?>">

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="product_id" class="form-label">Produk</label>
                        <select class="form-select select2" id="productForm" name="product_id" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($data['product'] as $product): ?>
                                <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>">
                                    <?= $product['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="price" class="form-label">Harga</label>
                        <input type="text" id="priceForm" name="price" class="form-control" value="<?= currencyRupiah(0) ?>" readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="quantity" class="form-label">Jumlah</label>
                        <input type="number" id="quantityForm" name="quantity" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="col-md-2">
                        <label for="subtotal" class="form-label">Subtotal</label>
                        <input type="text" id="subtotalForm" name="subtotal" class="form-control" value="<?= currencyRupiah(0) ?>" readonly>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-plus-lg"></i>
                            Add Product
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="<?= BASEURL ?>/js/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="<?= BASEURL ?>/js/currency-formatter.js"></script>
<script>
    $(document).ready(function() {
        // Table Handler
        const products = <?= json_encode($data['product']) ?>;
        //  Read Table Row
        const table = new DataTable("#tableData", {
            ajax: {
                url: "<?= BASEURL ?>/Invoice/getInvoiceDetailByHeaderId/<?= $data["invoice"]["id"] ?>",
                method: 'POST',
                dataSrc: 'data'
            },
            processing: true,
            serverSide: true,
            order: [
                [1, "asc"]
            ],
            createdRow: function(row, data, dataIndex) {
                $(row).attr('data-id', data.id);
                $(row).attr('data-product_id', data.product_id);
                $(row).attr('data-price', data.price);
                $(row).attr('data-quantity', data.quantity);
                $(row).attr('data-subtotal', data.total);
            },
            columns: [{
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-danger btn-sm btn-del p-2" data-id="${data}" <?= $data["invoice"]["status_invoice"] === "Draft" ? "" : "disabled" ?>>
                                <i class="bi bi-x-lg d-flex align-items-center"></i>
                            </button>
                        `
                    }
                },
                {
                    data: "created_at"
                },
                {
                    data: "updated_at"
                },
                {
                    data: "product_id",
                    render: function(data, type, row) {
                        let select = `<select class="form-select" name="product_id" required ${'<?= $data["invoice"]["status_invoice"] ?>' === "Draft" ? "" : "disabled"}>`;
                        select += `<option value="">-- Pilih Produk --</option>`;

                        products.forEach(products => {
                            const selected = products.id == data ? "selected" : "";
                            select += `<option value="${products.id}" data-price="${products.price}" ${selected}>${products.name}</option>`;
                        });

                        select += `</select>`;
                        return select;
                    }
                },
                {
                    data: "price",
                    render: function(data, type, row) {
                        const formatted = currencyRupiah(data);
                        return `
                            <input type="text" name="price" class="form-control" value="${formatted}" readonly <?= $data["invoice"]["status_invoice"] === "Draft" ? "" : "disabled" ?>>
                        `
                    }
                },
                {
                    data: "quantity",
                    render: function(data, type, row) {
                        return `
                            <input type="number" name="quantity" class="form-control" min="1" value="${data}" required <?= $data["invoice"]["status_invoice"] === "Draft" ? "" : "disabled" ?>>
                        `
                    }
                },
                {
                    data: "total",
                    render: function(data, type, row) {
                        const formatted = currencyRupiah(data);
                        return `
                            <input type="text" name="subtotal" class="form-control" value="${formatted}" readonly <?= $data["invoice"]["status_invoice"] === "Draft" ? "" : "disabled" ?>>
                        `
                    }
                }
            ]
        });
        //  Edit Table Row
        $("#btnSaveChanges").on("click", function() {
            const updates = [];

            $("#tableData tbody tr").each(function() {
                const row = $(this);
                const id = row.data("id");

                // Value Input Asli
                const initialProduct = parseInt(row.data("product_id"));
                const initialPrice = parseFloat(row.data("price"));
                const initialQuantity = parseFloat(row.data("quantity"));
                const initialSubtotal = parseFloat(row.data("subtotal"));

                // Value Input Terbaru
                const product = parseInt(row.find("select[name='product_id']").val());
                const price = currencyUnformat(row.find("input[name='price']").val());
                const quantity = parseFloat(row.find("input[name='quantity']").val());
                const subtotal = currencyUnformat(row.find("input[name='subtotal']").val());

                // Validasi Apakah Ada Perubahan Input
                const isChanged = (
                    product !== initialProduct ||
                    price !== initialPrice ||
                    quantity !== initialQuantity ||
                    subtotal !== initialSubtotal
                );
                if (!isChanged) return;

                // Bungkus Value Data Untuk Dikirim Ke Backend
                updates.push({
                    id: id,
                    product: product,
                    price: price,
                    quantity: quantity,
                    subtotal: subtotal
                });
            });

            if (updates.length === 0) {
                Swal.fire({
                    title: "Tidak Ada Perubahan",
                    text: "Tidak ada data yang berubah untuk disimpan.",
                    icon: "info",
                    timer: 2500
                });
                return;
            }

            $.ajax({
                url: '<?= BASEURL ?>/Invoice/updateInvoiceDetail',
                method: 'POST',
                data: {
                    data: updates
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            timer: 2500
                        });
                        table.ajax.reload(null, false);
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
        });
        //  Delete Table Row
        $(document).on("click", ".btn-del", function() {
            const id = $(this).data('id');

            Swal.fire({
                title: "Ingin Menghapus Baris Invoice?",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Delete",
                denyButtonText: `Batalkan`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= BASEURL ?>/Invoice/deleteInvoiceDetail',
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    timer: 2500
                                });
                                table.ajax.reload(null, false);
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
                } else if (result.isDenied) {
                    Swal.fire({
                        title: "Canceled!",
                        text: "Void Dibatalkan.",
                        icon: "info",
                        timer: 2500
                    });
                }
            });
        })
        //  Fungsi Menghitung Subtotal
        function updateSubtotal() {
            $("#tableData tbody tr").each(function() {
                const row = $(this);
                const productInput = row.find("select[name='product_id']")
                const priceInput = row.find("input[name='price']")
                const quantityInput = row.find("input[name='quantity']")
                const subtotalInput = row.find("input[name='subtotal']")

                productInput.on("change", function() {
                    const selected = productInput.find("option:selected");
                    priceInput.val(currencyRupiah(parseFloat(selected.data("price")) || 0));
                    const price = currencyUnformat(priceInput.val()) || 0;
                    const quantity = parseInt(quantityInput.val()) || 0;
                    subtotalInput.val(currencyRupiah(price * quantity));
                    updateGrandTotal();
                })

                quantityInput.on("input", function() {
                    const price = currencyUnformat(priceInput.val()) || 0;
                    const quantity = parseInt($(this).val()) || 0;
                    subtotalInput.val(currencyRupiah(price * quantity));
                    updateGrandTotal();
                })
            })
        }
        //  Fungsi Menghitung Grand Total
        function updateGrandTotal() {
            let grandTotal = 0;
            $("#tableData tbody tr").each(function() {
                const subtotal = currencyUnformat($(this).find("input[name='subtotal']").val()) || 0;
                grandTotal += subtotal;
            });
            $("#grandTotal").text(currencyRupiah(grandTotal));
        };
        //  Fungsi Yang Dijalankan Setelah Draw Table
        table.on("draw", function() {
            $('.form-select').select2({
                theme: "bootstrap-5",
            });
            updateSubtotal();
            updateGrandTotal();
        })

        // Form Handler
        //  Inisialisasi Select2
        $('.select2').select2({
            theme: "bootstrap-5",
        });
        //  Harga Berubah Sesuai Produk Yang Dipilih
        $("#productForm").on("change", function() {
            const selected = $(this).find("option:selected");
            const price = selected.data("price") || 0;
            $("#priceForm").val(currencyRupiah(price));
            updateFormSubtotal();
        });
        //  Menghitung Subtotal
        function updateFormSubtotal() {
            const price = currencyUnformat($("#priceForm").val()) || 0;
            const quantity = currencyUnformat($("#quantityForm").val()) || 0;
            $("#subtotalForm").val(currencyRupiah(price * quantity));
        }
        $("#quantityForm").on("input", updateFormSubtotal);
        //  Create Table Row
        $("#formInvDetail").on("submit", function(e) {
            e.preventDefault();

            // Input harga dan subtotal
            const priceInput = $(this).find("input[name='price']");
            const subtotalInput = $(this).find("input[name='subtotal']");

            // Simpan Value Input Asli
            const price = priceInput.val();
            const subtotal = subtotalInput.val();

            // Mengubah Value Input Menjadi Angka Agar Dapat Diproses Backend
            priceInput.val(currencyUnformat(price));
            subtotalInput.val(currencyUnformat(subtotal));

            // Serialize Form
            const formData = $(this).serialize();

            // Mengembalikan Value Input Ke Kondisi Awal
            priceInput.val(price);
            subtotalInput.val(subtotal);

            $.ajax({
                url: '<?= BASEURL ?>/Invoice/addInvoiceDetail',
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
                        $("#formInvDetail")[0].reset();
                        table.ajax.reload(null, false);
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
        });
    });
</script>