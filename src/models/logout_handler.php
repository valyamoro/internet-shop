<?php

error_reporting(-1);
session_start();

session_destroy();

header('Location: ../../index.php');