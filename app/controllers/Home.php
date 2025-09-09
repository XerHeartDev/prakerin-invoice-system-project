<?php

class Home extends Controller
{
    public function index()
    {
        if (!isset($_SESSION["user"])) {
            header('Location: ' . BASEURL . '/Auth/loginPage');
            exit;
        }

        $data = [
            // set class untuk header
            "bodyClass" => "fixed-header layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary",
            "parentClass" => "app-wrapper",
            // set class untuk footer
            "footerClass" => "app-footer",
        ];

        $this->view("templates/header", $data);
        $this->view("templates/navbar");
        $this->view("templates/sidebar");
        $this->view("home/index");
        $this->view("templates/footer", $data);
    }
}
