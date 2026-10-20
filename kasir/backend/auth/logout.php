<?php
session_start();
session_destroy();
header('Location: /kasir/login.php');
exit;
