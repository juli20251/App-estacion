<?php
session_start();
session_unset();
session_destroy();
header("Location: /alumno/10014/app-estacion/view/login.php");
exit;
