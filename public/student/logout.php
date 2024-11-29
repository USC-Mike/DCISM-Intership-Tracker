<?php
session_start();
session_destroy(); // Destroys all session data
header('Location: ../../public/login.php');
exit();
