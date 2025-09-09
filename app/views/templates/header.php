<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PHP MVC</title>
    <!-- Accessibilty Meta Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!-- Accessibility Features -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="<?= BASEURL; ?>/css/bootstrap/bootstrap.css" as="style">
    <link rel="preload" href="<?= BASEURL; ?>/css/adminLTE/adminlte.css" as="style">
    <link rel="preload" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" as="style">
    <!-- Fonts -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
        crossorigin="anonymous"
        media="print"
        onload="this.media='all'" />
    <!-- Third Party Plugin(OverlayScrollbars) -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <!-- Third Party Plugin(Bootstrap Icons) -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <!-- Third Party Plugin(Select2) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Required Plugin  -->
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/adminLTE/adminlte.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css">
</head>

<body class="<?= isset($data['bodyClass']) ? $data['bodyClass'] : '' ?>">
    <div class="<?= isset($data['parentClass']) ? $data['parentClass'] : '' ?>">