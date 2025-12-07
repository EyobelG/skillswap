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


//CODE TO LOAD MEMBERS FROM DATABASE
document.addEventListener("DOMContentLoaded", () => {
    fetch("../backend/database.php")
        .then(response => response.json())
        .then(members => {
            const container = document.getElementById("members-container");

            // Ensure members is a valid array
            if (!Array.isArray(members) || members.length === 0) {
                container.innerHTML = "<p>No members found.</p>";
                return;
            }

            members.forEach(member => {
                // Normalize all fields to avoid null errors
                const name = member?.name || "Unknown Member";
                const description = member?.description || "No description available.";
                const categories = Array.isArray(member?.categories) ? member.categories : [];
                const wantsToLearn = Array.isArray(member?.wantsToLearn) ? member.wantsToLearn : [];

                const card = document.createElement("div");
                card.classList.add("member-card");

                card.innerHTML = `
                    <h2>${name}</h2>

                    <p><strong>Description:</strong> ${description}</p>

                    <p><strong>Willing to Teach:</strong><br>
                        ${
                            categories.length
                                ? categories.map(cat => `<span class="member-tag-list">${cat}</span>`).join("")
                                : "<em>No subjects listed.</em>"
                        }
                    </p>

                    <p><strong>Wants to Learn:</strong><br>
                        ${
                            wantsToLearn.length
                                ? wantsToLearn.map(w => `<span class="member-tag-list">${w}</span>`).join("")
                                : "<em>No preferences listed.</em>"
                        }
                    </p>
                `;

                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error("Error fetching members:", error);
            document.getElementById("members-container").innerHTML =
                "<p>Error loading members.</p>";
        });
});
//CODE TO LOAD MEMBERS FROM DATABASE
//RESPONSIVE NAVBAR CODE
function toggleMenu() {
    var nav = document.getElementById("myTopnav");
    nav.classList.toggle("responsive");
}

document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('submitBtn');

    if (submitBtn) {
        // 2. Add a click event listener
        submitBtn.addEventListener('click', function(event) {
            window.location.href = 'success.html';
        });
    }
});