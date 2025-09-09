<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="text-center my-4">Invoice List</h1>
                <a href="<?= BASEURL ?>/Invoice/form/" class="btn btn-success">
                    Create Invoice
                </a>
            </div>

            <!-- Table Invoice -->
            <div class="table-responsive">
                <table id="tableData" class="display table table-responsive table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Invoice Code</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="<?= BASEURL ?>/js/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="<?= BASEURL ?>/js/currency-formatter.js"></script>
<script>
    $(document).ready(function() {
        // Read Handler
        const table = new DataTable("#tableData", {
            ajax: {
                url: '<?= BASEURL ?>/Invoice/getInvoiceHeader',
                method: 'POST',
                dataSrc: 'data',
            },
            processing: true,
            serverSide: true,
            columns: [{
                    data: 'status_invoice',
                    render: function(data, type, row) {
                        if (data == "Published") {
                            return `<span class="badge bg-success">Published</span>`;
                        } else if (data == "Void") {
                            return `<span class="badge bg-danger">Void</span>`;
                        } else {
                            return `<span class="badge bg-secondary">Draft</span>`;
                        }
                    }
                },
                {
                    data: "invoice_code"
                },
                {
                    data: "created_at"
                },
                {
                    data: "updated_at"
                },
                {
                    data: "client_name"
                },
                {
                    data: "total",
                    render: function(data, type, row) {
                        return currencyRupiah(data);
                    }
                },
                {
                    data: "btn_handler",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (data["status_invoice"] === "Draft") {
                            return `
                            <a href="<?= BASEURL ?>/Invoice/detail/${data["id"]}" class="btn btn-primary btn-sm">View</a>
                            <a href="<?= BASEURL ?>/Invoice/form/${data["id"]}" class="btn btn-secondary btn-sm">Edit</a>
                            <button class="btn btn-success btn-sm btn-publish" data-id="${data["id"]}">Publish</button>
                            <button class="btn btn-danger btn-sm btn-void" data-id="${data["id"]}">Void</button>
                            `;
                        } else if (data["status_invoice"] === "Published") {
                            return `
                            <a href="<?= BASEURL ?>/Invoice/detail/${data["id"]}" class="btn btn-primary btn-sm">View</a>
                            <button class="btn btn-danger btn-sm btn-void" data-id="${data["id"]}">Void</button>
                            `;
                        } else {
                            return `
                            <a href="<?= BASEURL ?>/Invoice/detail/${data["id"]}" class="btn btn-primary btn-sm">View</a>
                            `;
                        }
                    }
                }
            ]
        });

        // Publish Handler
        $(document).on("click", ".btn-publish", function() {
            const id = $(this).data('id');

            Swal.fire({
                title: "Ingin Mempublish Invoice?",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Publish",
                denyButtonText: `Batalkan`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= BASEURL ?>/Invoice/publishInvoiceHeader',
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
                        text: "Publish Dibatalkan.",
                        icon: "info",
                        timer: 2500
                    });
                }
            });
        })

        // Void Handler
        $(document).on("click", ".btn-void", function() {
            const id = $(this).data('id');

            Swal.fire({
                title: "Ingin Mengubah Invoice Menjadi Void?",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Void",
                denyButtonText: `Batalkan`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= BASEURL ?>/Invoice/voidInvoiceHeader',
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
    });
</script>