<?php
session_start();
session_destroy();
header("Location: ../pages/loginPage.php");
exit;
