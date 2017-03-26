<?php
session_start();
unset($_SESSION["admin"]);
header("location: " . adminUrl("login"));
exit;