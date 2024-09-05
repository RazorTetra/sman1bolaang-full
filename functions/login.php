<?php
include('../config.php');
session_start();

// Fungsi untuk mencegah brute force dengan membatasi percobaan login
function limit_login_attempts($pdo, $username) {
    $stmt = $pdo->prepare("SELECT login_attempts, last_attempt FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        $time_diff = time() - strtotime($user['last_attempt']);
        if ($time_diff < 900 && $user['login_attempts'] >= 5) {
            return "Terlalu banyak percobaan login, coba lagi dalam 15 menit.";
        }
    }
    return null;
}

// Reset login attempts jika login berhasil
function reset_login_attempts($pdo, $username) {
    $stmt = $pdo->prepare("UPDATE users SET login_attempts = 0 WHERE username = :username");
    $stmt->execute(['username' => $username]);
}

// Tambah percobaan login jika gagal
function increment_login_attempts($pdo, $username) {
    $stmt = $pdo->prepare("UPDATE users SET login_attempts = login_attempts + 1, last_attempt = NOW() WHERE username = :username");
    $stmt->execute(['username' => $username]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Cek apakah username kosong atau tidak valid
    if (empty($username) || empty($password)) {
        $error = "Username dan password tidak boleh kosong!";
        header('Location: ../pages/loginPage.php?error=' . urlencode($error));
        exit();
    }

    // Batasi percobaan login jika username ditemukan
    $limit_error = limit_login_attempts($pdo, $username);
    if ($limit_error) {
        header('Location: ../pages/loginPage.php?error=' . urlencode($limit_error));
        exit();
    }

    // Gunakan prepared statement untuk menghindari SQL Injection
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        // Verifikasi password dengan fungsi password_verify
        if (password_verify($password, $user['password'])) {
            // Regenerasi ID sesi untuk menghindari session fixation attacks
            session_regenerate_id(true);

            // Set data sesi untuk user yang berhasil login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            // Reset login attempts setelah login berhasil
            reset_login_attempts($pdo, $username);

            header('Location: ../admin/index.php');
            exit();
        } else {
            // Tambah percobaan login jika gagal
            increment_login_attempts($pdo, $username);
            $error = "Username atau password salah!";
            header('Location: ../pages/loginPage.php?error=' . urlencode($error));
            exit();
        }
    } else {
        $error = "Username atau password salah!";
        header('Location: ../pages/loginPage.php?error=' . urlencode($error));
        exit();
    }
}
?>