<?php
require __DIR__ . "/config/app.php";
session_start();
session_unset();
session_destroy();
header("Location: ".url("index.php"));
exit;
