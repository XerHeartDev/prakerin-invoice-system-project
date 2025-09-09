<?php

class Auth extends Controller
{
    public function index()
    {
        header('Location: ' . BASEURL);
        exit;
    }

    // Handler Login
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = $this->model("User_model")->getByEmail($email); //Mengambil Sampel User Sesuai dengan Email yang Dimasukkan
            header("Content-Type: application/json");
            // Validasi Apakah User Terdaftar di DB
            if ($user === false) {
                echo json_encode([
                    "status" => "error",
                    "message" => "User Tidak Ditemukan",
                ]);
                exit;
            } else {
                // Validasi Apakah Email dan Password Benar dan Cocok
                if ($email === $user["email"] && password_verify($password, $user["password"])) {
                    $_SESSION["user"] = $user;
                    echo json_encode([
                        "status" => "success",
                        "message" => "Login Berhasil",
                    ]);
                    exit;
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Email/Password Salah",
                    ]);
                    exit;
                };
            };
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    // Handler Register
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->model("User_model")->insert($_POST);
            header("Content-Type: application/json");
            echo json_encode([
                "status" => $result ? "success" : "error",
                "message" => $result ? "Berhasil Mendaftar Akun" : "Gagal Mendaftar Akun"
            ]);
            exit;
        } else {
            header('Location: ' . BASEURL);
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . BASEURL);
        exit;
    }

    //Tampil Halaman Login
    public function loginPage()
    {
        $data = [
            // set class untuk header
            "bodyClass" => "login-page bg-body-secondary",
            "parentClass" => "login-box",
        ];

        $this->view("templates/header", $data);
        $this->view("auth/login");
        $this->view("templates/footer");
    }

    // Tampil Halaman Register
    public function registerPage()
    {
        $data = [
            // set class untuk header
            "bodyClass" => "register-page bg-body-secondary",
            "parentClass" => "register-box",
        ];

        $this->view("templates/header", $data);
        $this->view("auth/register");
        $this->view("templates/footer");
    }
}
