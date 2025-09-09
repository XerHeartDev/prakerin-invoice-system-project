<?php

class Invoice extends Controller
{
    public function index()
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
            "invoiceClass" => "active",
            // set class untuk footer
            "footerClass" => "app-footer",
        ];

        $this->view("templates/header", $data);
        $this->view("templates/navbar");
        $this->view("templates/sidebar", $data);
        $this->view("invoice/index");
        $this->view("templates/footer", $data);
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
            "invoiceClass" => "active",
            // set class untuk footer
            "footerClass" => "app-footer",
        ];

        // set class & data untuk form
        $data["clients"] = $this->model("Client_model")->getActiveClient();
        if ($id === []) {
            $data["title"] = "Create New Invoice";
            $data["invoice"] = null;
            $data["btnSubmit"] = "Create Invoice";
            $data["formId"] = "addInvoice";
        } else {
            $data["title"] = "Edit Invoice";
            $data["invoice"] = $this->model('Invoice_model')->getInvoiceHeaderById($id);
            $data["btnSubmit"] = "Save Changes";
            $data["formId"] = "editInvoice";
        };

        $this->view("templates/header", $data);
        $this->view("templates/navbar");
        $this->view("templates/sidebar", $data);
        $this->view("invoice/form", $data);
        $this->view("templates/footer", $data);
    }

    public function detail($id)
    {
        if (!isset($_SESSION["user"]) || $id === null) {
            header('Location: ' . BASEURL);
            exit;
        }

        $data = [
            // set class untuk header
            "bodyClass" => "fixed-header layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary",
            "parentClass" => "app-wrapper",
            // set class untuk sidebar
            "invoiceClass" => "active",
            // set class untuk footer
            "footerClass" => "app-footer",
        ];

        // set data untuk halaman
        $data["invoice"] = $this->model("Invoice_model")->getInvoiceHeaderById($id);
        $data["client"] = $this->model("Client_model")->getClientById($data["invoice"]["client_id"]);
        if ($data["invoice"]["status_invoice"] === "Draft") {
            $data["product"] = $this->model("Product_model")->getActiveProduct();
        } else {
            $temp_arr = $this->model("Product_model")->getAllProduct();
            $data["product"] = $temp_arr["data"];
        }

        $this->view("templates/header", $data);
        $this->view("templates/navbar");
        $this->view("templates/sidebar", $data);
        $this->view("invoice/detail", $data);
        $this->view("templates/footer", $data);
    }

    // Method untuk Invoice Header
    public function getInvoiceHeader()
    {
        if (!isset($_SESSION["user"])) {
            header('Location: ' . BASEURL);
            exit;
        }

        $data = $this->model("Invoice_model")->getAllInvoiceHeader();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function addInvoiceHeader()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->model('Invoice_model')->insertHeader($_POST);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Membuat Invoice. \nRow Affected: $result" : "Gagal Membuat Invoice."
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    public function updateInvoiceHeader()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->model('Invoice_model')->updateHeader($_POST);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Mengubah Invoice. \nRow Affected: $result" : "Gagal Mengubah Invoice."
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    public function publishInvoiceHeader()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $result = $this->model('Invoice_model')->publishHeader($id);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Publish Invoice Berhasil. \nRow Affected: $result" : "Publish Invoice Gagal."
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    public function voidInvoiceHeader()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $result = $this->model('Invoice_model')->voidHeader($id);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Void Invoice Berhasil. \nRow Affected: $result" : "Void Invoice Gagal."
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    // Method untuk Invoice Detail
    public function getInvoiceDetailByHeaderId($id)
    {
        if (!isset($_SESSION["user"])) {
            header('Location: ' . BASEURL);
            exit;
        }

        $data = $this->model("Invoice_model")->getAllInvoiceDetailByHeaderId($id);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function addInvoiceDetail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->model('Invoice_model')->insertDetail($_POST);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Menambah Baris Invoice. \nRow Affected: $result" : "Gagal Menambah Baris Invoice."
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    public function updateInvoiceDetail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = $_POST["data"];
            $result = 0;
            foreach ($payload as $data) {
                $result += $this->model('Invoice_model')->updateDetail($data);
            };
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Mengubah Invoice. \nRow Affected: $result" : "Gagal Mengubah Invoice."
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    public function deleteInvoiceDetail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $result = $this->model('Invoice_model')->deleteDetail($id);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Menghapus Baris Invoice. \nRow Affected: $result" : "Gagal Menghapus Baris Invoice."
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }
}
