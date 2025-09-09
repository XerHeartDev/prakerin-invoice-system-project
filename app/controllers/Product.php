<?php

class Product extends Controller
{
    public function index()
    {
        header('Location: ' . BASEURL);
        exit;
    }

    public function getProduct()
    {
        if (!isset($_SESSION["user"])) {
            header('Location: ' . BASEURL);
            exit;
        }

        $data = $this->model("Product_model")->getAllProduct();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function addProduct()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $result = $this->model('Product_model')->insert($_POST);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Menambah Produk. \nRow Affected: $result" : "Gagal Menambah Produk."
            ]);
            exit;
        } else {
            header("Location: " . BASEURL);
            exit;
        }
    }

    public function updateProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->model('Product_model')->update($_POST);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Mengubah Informasi Produk. \nRow Affected: $result" : "Gagal Mengubah Informasi Produk."
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    public function deleteProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $result = $this->model('Product_model')->delete($id);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Menghapus Produk. \nRow Affected: $result" : "Gagal Menghapus Produk."
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    public function form($id = [])
    {
        if (!isset($_SESSION["user"])) {
            header('Location: ' . BASEURL);
            exit;
        }

        $data = [
            // set class untuk header
            "bodyClass" => "fixed-header layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary",
            "parentClass" => "app-wrapper",
            // set class untuk sidebar
            "dashboardMenuClass" => "menu-open",
            "dashboardClass" => "active",
            "dashboardProductClass" => "active",
            // set class untuk footer
            "footerClass" => "app-footer",
        ];

        // set class & data untuk form
        if ($id === []) {
            $data["title"] = "Add New Product";
            $data["product"] = null;
            $data["btnSubmit"] = "Add Product";
            $data["formId"] = "addProduct";
        } else {
            $data["title"] = "Edit Product Info";
            $data["product"] = $this->model('Product_model')->getById($id);
            $data["btnSubmit"] = "Save Changes";
            $data["formId"] = "editProduct";
        };

        $this->view("templates/header", $data);
        $this->view("templates/navbar");
        $this->view("templates/sidebar", $data);
        $this->view("form/product", $data);
        $this->view("templates/footer", $data);
    }
}
