<?php
session_start();
echo Json::encode( User::getMe($_SESSION["uuid"]) );
