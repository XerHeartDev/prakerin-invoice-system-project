<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="<?= BASEURL ?>" class="brand-link">
            <img
                src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png"
                alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow" />

            <span class="brand-text fw-light">XerAdmin</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="navigation"
                aria-label="Main navigation"
                data-accordion="false"
                id="navigation">

                <li class="nav-header">TOOLS</li>
                <li class="nav-item <?= $data['dashboardMenuClass'] ?? '' ?>">
                    <a href="#" class="nav-link <?= $data['dashboardClass'] ?? '' ?>">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Dashboard
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= BASEURL ?>/Dashboard/user" class="nav-link <?= $data['dashboardUserClass'] ?? '' ?>">
                                <i class="nav-icon bi bi-person"></i>
                                <p>User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASEURL ?>/Dashboard/client" class="nav-link <?= $data['dashboardClientClass'] ?? '' ?>">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Client</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASEURL ?>/Dashboard/product" class="nav-link <?= $data['dashboardProductClass'] ?? '' ?>">
                                <i class="nav-icon bi bi-box-seam"></i>
                                <p>Product</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= BASEURL ?>/Invoice" class="nav-link <?= $data['invoiceClass'] ?? '' ?>">
                        <i class="nav-icon bi bi-receipt"></i>
                        <p>
                            Invoice
                        </p>
                    </a>
                </li>

                <li class="nav-header">SETTINGS</li>
                <li class="nav-item">
                    <a href="<?= BASEURL ?>/Account" class="nav-link <?= $data['accountClass'] ?? '' ?>">
                        <i class="nav-icon bi bi-person-vcard"></i>
                        <p>
                            Account
                        </p>
                    </a>
                </li>

                <li class="nav-header">DOCUMENTATIONS</li>
                <li class="nav-item">
                    <a href="<?= BASEURL ?>/About" class="nav-link <?= $data['aboutClass'] ?? '' ?>">
                        <i class="nav-icon bi bi-info-circle"></i>
                        <p>About Us</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link <?= $data['faqClass'] ?? '' ?>">
                        <i class="nav-icon bi bi-question-circle"></i>
                        <p>FAQ</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link <?= $data['licenseClass'] ?? '' ?>">
                        <i class="nav-icon bi bi-patch-check"></i>
                        <p>License</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>