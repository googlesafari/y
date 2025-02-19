<?php
session_start();
include "database.php";

// Simulasi login admin
$admin_email = "axeldine@gmail.com";
$admin_password = "axeldine123";

// Proses login
if (isset($_POST["login"])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    
    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['admin'] = true;
    } else {
        $_SESSION['admin'] = false;
    }
}

// Keluar dari sesi admin
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Data diamond default
if (!isset($_SESSION['diamond_options'])) {
    $_SESSION['diamond_options'] = ["100 DM", "500 DM", "1000 DM", "5000 DM"];
}

// Tambah atau hapus opsi diamond jika admin
if (isset($_POST['add_diamond']) && $_SESSION['admin']) {
    $new_diamond = htmlspecialchars($_POST['new_diamond']);
    if (!empty($new_diamond)) {
        $_SESSION['diamond_options'][] = $new_diamond;
    }
}

if (isset($_POST['remove_diamond']) && $_SESSION['admin']) {
    $remove_diamond = $_POST['remove_diamond'];
    $_SESSION['diamond_options'] = array_filter($_SESSION['diamond_options'], function($diamond) use ($remove_diamond) {
        return $diamond !== $remove_diamond;
    });
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Diamond</title>
</head>
<body>

<?php if (!isset($_SESSION['admin']) || !$_SESSION['admin']) : ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <h1>Login</h1>
    <input type="email" placeholder="Email" name="email" required />
    <br>
    <input type="password" placeholder="Password" name="password" required />
    <br><br>
    <button type="submit" name="login">Login</button>
</form>
<?php else: ?>
    <h2>Selamat datang, Admin!</h2>
    <form method="POST">
        <button type="submit" name="logout">Logout</button>
    </form>

    <h2>Kelola Diamond</h2>
    <form method="POST">
        <label>Tambah Opsi Diamond:</label>
        <input type="text" name="new_diamond" placeholder="Misal: 750 DM">
        <button type="submit" name="add_diamond">Tambah</button>
    </form>

    <form method="POST">
        <label>Hapus Opsi Diamond:</label>
        <select name="remove_diamond">
            <?php foreach ($_SESSION['diamond_options'] as $option) : ?>
                <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="remove_diamond">Hapus</button>
    </form>
<?php endif; ?>
