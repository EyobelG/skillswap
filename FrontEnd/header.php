<?php
// header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $pageTitle ?? 'Skill Swap - Learn Something, Teach Something' ?></title>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- App styles -->
    <link rel="stylesheet" href="style.css">

    <!-- App scripts -->
    <script src="main.js" defer></script>
    <script src="header.js" defer></script>
</head>

<body>

<nav class="topnav" id="myTopnav">
    <a href="index.php" class="logo">SkillSwap</a>

    <div class="nav-links">
        <a href="account.php">Account</a>
        <a href="members.php">Members</a>
        <a href="contact.php">Contact Us</a>
        <a href="signup.php">Sign Up</a>
        <a href="signin.php">Sign In</a>
    </div>

    <button class="icon" onclick="toggleMenu()" aria-label="Menu">
        <i class="fa fa-bars"></i>
    </button>
</nav>


