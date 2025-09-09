<?php

class About extends Controller
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
            "aboutClass"=> "active",
            // set class untuk footer
            "footerClass" => "app-footer",
        ];

        $this->view("templates/header", $data);
        $this->view("templates/navbar");
        $this->view("templates/sidebar", $data);
        $this->view("about/index");
        $this->view("templates/footer", $data);
    }
}
