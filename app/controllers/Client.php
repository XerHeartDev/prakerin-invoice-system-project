<?php

class Client extends Controller
{
    public function index()
    {
        header('Location: ' . BASEURL);
        exit;
    }

    public function getAllClients()
    {
        if (!isset($_SESSION["user"])) {
            header('Location: ' . BASEURL);
            exit;
        }

        $data = $this->model("Client_model")->getAllClient();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function addClient()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $result = $this->model('Client_model')->insert($_POST);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Menambah Client. \nRow Affected: $result" : "Gagal Menambah Client."
            ]);
            exit;
        } else {
            header("Location: " . BASEURL);
            exit;
        }
    }

    public function updateClient()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->model('Client_model')->update($_POST);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Mengubah Informasi Client. \nRow Affected: $result" : "Gagal Mengubah Informasi Client."
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    public function deleteClient()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $result = $this->model('Client_model')->delete($id);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Menghapus Client. \nRow Affected: $result" : "Gagal Menghapus Client."
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
            "dashboardClientClass" => "active",
            // set class untuk footer
            "footerClass" => "app-footer",
        ];

        // set class & data untuk form
        if ($id === []) {
            $data["title"] = "Register New Client";
            $data["client"] = null;
            $data["btnSubmit"] = "Register Client";
            $data["formId"] = "addClient";
        } else {
            $data["title"] = "Edit User";
            $data["client"] = $this->model('Client_model')->getById($id);
            $data["btnSubmit"] = "Save Changes";
            $data["formId"] = "editClient";
        };

        $this->view("templates/header", $data);
        $this->view("templates/navbar");
        $this->view("templates/sidebar", $data);
        $this->view("form/client", $data);
        $this->view("templates/footer", $data);
    }
}
