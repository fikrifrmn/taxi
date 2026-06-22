<?php
session_start();

unset($_SESSION['booking_in_progress']);
unset($_SESSION['booking_paket']);

header("Location: ../user/home.php");
exit;