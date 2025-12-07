// Prepend navbar
document.addEventListener('DOMContentLoaded', function() {
    // document.getElementById('header-container').innerHTML = `
    //     <!-- Navbar -->
    //     <nav class="topnav" id="myTopnav">
    //         <a href="index.html" class="logo">SkillSwap</a>
    //         <div class="nav-links">
    //             <a href="account.html">Account</a>
    //             <a href="members.html">Members</a>
    //             <a href="contact.html">Contact Us</a>
    //             <a href="signup.html" class="active">Sign Up</a>
    //         </div>
    //         <a class="icon" onclick="toggleMenu()">
    //             <i class="fa fa-bars"></i>
    //         </a>
    //     </nav>    
    // `;
    document.getElementById('footer-container').innerHTML = `
        <footer class="site-footer">
            <p>&copy; 2025 SkillSwap. All rights reserved.</p>
            <div>
                <a href="contact.html">Contact Us</a>
            </div>
        </footer>
    `;
});