<?php
session_start();
// Only remove frontend user session keys — don't destroy admin session
unset($_SESSION['user_id']);
unset($_SESSION['user_data']);
unset($_SESSION['redirect_after_login']);
header('Location: /makaandekho.in/');
exit;
