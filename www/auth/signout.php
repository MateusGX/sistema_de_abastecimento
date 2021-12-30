<?php
session_start();
include_once('../core/user/manager.php');
Usuario::SignOut();

header("Location: /");
