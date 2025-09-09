<?php

class User extends Controller
{
    public function index()
    {
        header('Location: ' . BASEURL);
        exit;
    }

    public function getUsers()
    {
        if (!isset($_SESSION["user"])) {
            header('Location: ' . BASEURL);
            exit;
        }

        $data = $this->model("User_model")->getAllUsers();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function getUserById()
    {
        if (!isset($_SESSION["user"])) {
            header('Location: ' . BASEURL);
            exit;
        }

        $id = $_POST['id'];
        $data = $this->model('User_model')->getById($id);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->model('User_model')->insert($_POST);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Menambah User. \nRow Affected: $result" : "Gagal Menambah User."
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    public function updateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->model('User_model')->update($_POST);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Mengubah Informasi User. \nRow Affected: $result" : "Gagal Mengubah Informasi User."
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    public function deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $result = $this->model('User_model')->delete($id);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => $result ? "success" : "error",
                'message' => $result ? "Berhasil Menghapus User. \nRow Affected: $result" : "Gagal Menghapus User."
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
            "dashboardUserClass" => "active",
            // set class untuk footer
            "footerClass" => "app-footer",
        ];

        // set class & data untuk form
        if ($id === []) {
            $data["title"] = "Register New User";
            $data["user"] = null;
            $data["btnSubmit"] = "Register User";
            $data["formId"] = "addUser";
        } else {
            $data["title"] = "Edit User";
            $data["user"] = $this->model('User_model')->getById($id);
            $data["btnSubmit"] = "Save Changes";
            $data["formId"] = "editUser";
        };

        $this->view("templates/header", $data);
        $this->view("templates/navbar");
        $this->view("templates/sidebar", $data);
        $this->view("form/user", $data);
        $this->view("templates/footer", $data);
    }
}
