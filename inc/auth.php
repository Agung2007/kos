<?php
// inc/auth.php
session_start();
require_once __DIR__ . '/db.php';

function is_logged_in(){
    return isset($_SESSION['user_id']);
}

function is_admin(){
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function require_login(){
    if(!is_logged_in()){
        header("Location: /kos/login.php");
        exit;
    }
}

function require_admin(){
    if(!is_logged_in() || !is_admin()){
        header("Location: ../admin/login.php");
        exit;
    }
}
