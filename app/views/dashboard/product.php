<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="text-center my-4">Dashboard Product</h1>
                <a href="<?= BASEURL ?>/Product/form/" class="btn btn-success">
                    Add Product
                </a>
            </div>

            <!-- Table Product -->
            <div class="table-responsive">
                <table id="tabelData" class="display table table-responsive table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>SKU</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Unit</th>
                            <th>Stock</th>
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
        const table = new DataTable('#tabelData', {
            ajax: {
                url: '<?= BASEURL ?>/Product/getProduct',
                method: 'POST',
                dataSrc: 'data',
            },
            processing: true,
            serverSide: true,
            columns: [{
                    data: 'status_code',
                    render: function(data, type, row) {
                        if (data == 1) {
                            return `<span class="badge bg-success">Active</span>`;
                        } else if (data == 9) {
                            return `<span class="badge bg-danger">Inactive</span>`;
                        } else {
                            return `<span class="badge bg-secondary">Pending</span>`;
                        }
                    }
                },
                {
                    data: 'sku'
                },
                {
                    data: 'name'
                },
                {
                    data: 'description'
                },
                {
                    data: 'price',
                    render: function(data, type, row) {
                        return currencyRupiah(data);
                    }
                },
                {
                    data: 'unit'
                },
                {
                    data: 'stock'
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                                <a href="<?= BASEURL ?>/Product/form/${data}" class="btn btn-primary btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="${data}">Delete</button>
                                `;
                    }
                }
            ]
        });

        // Delete Handler
        $(document).on("click", ".btn-delete", function() {
            const id = $(this).data('id');

            Swal.fire({
                title: "Ingin Menghapus Product?",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Hapus",
                denyButtonText: `Batalkan`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= BASEURL ?>/Product/deleteProduct',
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
                        text: "Penghapusan Dibatalkan.",
                        icon: "info",
                        timer: 2500
                    });
                }
            });
        })
    });
</script>