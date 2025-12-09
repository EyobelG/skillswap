<?php
// footer.php
?>

    <script>
        // Hamburger menu toggle function
        function toggleMenu() {
            var nav = document.getElementById("myTopnav");
            nav.classList.toggle("responsive");
        }
    </script>

<footer class="site-footer">
    <p>&copy; <?= date('Y') ?> SkillSwap. All rights reserved.</p>

    <div>
        <a href="terms.php">Terms</a>
        <a href="privacy.php">Privacy</a>
        <a href="contact.php">Contact</a>
    </div>
</footer>

</body>
</html>


