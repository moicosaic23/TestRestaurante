<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Restaurant MVC</title>
    <link rel="stylesheet" href="<?php echo $this->config['base_url']; ?>/assets/css/style.css?v=1.1">
    <script>const BASE_URL = '<?php echo $this->config['base_url']; ?>';</script>
</head>
<body>
<header class="topbar">
    <div class="container">
        <h1>
            El por venir
        </h1>
        <nav>
            <?php if(isset($_SESSION['user'])): ?>
                <span><?php echo htmlspecialchars($_SESSION['user']['username']); ?> (<?php echo $_SESSION['user']['role'] ?? 'sin-rol'; ?>)</span>
                <a href="<?php echo $this->config['base_url']; ?>/?route=auth/logout">Salir</a>
            <?php else: ?>
                <a href="<?php echo $this->config['base_url']; ?>/?route=auth/login">Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
