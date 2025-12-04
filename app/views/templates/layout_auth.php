<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Acceso - El por Venir</title>
    <link rel="stylesheet" href="<?php echo $this->config['base_url']; ?>/assets/css/auth.css">
</head>
<body>

<?php
// $file es provisto por Controller::view()
if (isset($file) && file_exists($file)) {
    require $file;
} else {
    echo "<p>Vista no encontrada.</p>";
}
?>

</body>
</html>


