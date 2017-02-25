<?php
session_start();
$bool = isset($_SESSION["uuid"]) && $_SESSION["uuid"];
echo JSON::encode( "{$bool}" );