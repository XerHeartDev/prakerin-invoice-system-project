<?php

class Dashboard extends Controller
{
    public function index()
    {
        header('Location: ' . BASEURL);
        exit;
    }

    public function user()
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

        $this->view("templates/header", $data);
        $this->view("templates/navbar");
        $this->view("templates/sidebar", $data);
        $this->view("dashboard/user");
        $this->view("templates/footer", $data);
    }

    public function client()
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

        $this->view("templates/header", $data);
        $this->view("templates/navbar");
        $this->view("templates/sidebar", $data);
        $this->view("dashboard/client", $data);
        $this->view("templates/footer", $data);
    }

    public function product()
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

        $this->view("templates/header", $data);
        $this->view("templates/navbar");
        $this->view("templates/sidebar", $data);
        $this->view("dashboard/product", $data);
        $this->view("templates/footer", $data);
    }
}
