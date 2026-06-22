<?php
$password_polos = "123";

// Generate hash
$hash = password_hash($password_polos, PASSWORD_DEFAULT);

echo "Password Polos: " . $password_polos . "<br>";
echo "Hasil Hash (Simpan ini ke Database): <br><b>" . $hash . "</b>";
?>