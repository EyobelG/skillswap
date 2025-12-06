
//CODE TO LOAD FOOTER
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('footer-container').innerHTML = `
        <footer class="site-footer">
            <p>&copy; 2025 SkillSwap. All rights reserved.</p>
            <div>
                <a href="contact.html">Contact Us</a>
            </div>
        </footer>
    `;
});
//CODE TO LOAD FOOTER

//CODE TO LOAD MEMBERS FROM DATABASE
document.addEventListener("DOMContentLoaded", () => {
    fetch("../BackEnd/database.php")
        .then(response => response.json())
        .then(members => {
            const container = document.getElementById("members-container");

            if (!members.length) {
                container.innerHTML = "<p>No members found.</p>";
                return;
            }

            members.forEach(member => {
                const card = document.createElement("div");
                card.classList.add("member-card");

                card.innerHTML = `
                    <h2>${member.name}</h2>
                    <p><strong>Description:</strong> ${member.description}</p>
                    <p><strong>Willing to Teach:</strong> ${member.categories.join(", ")}</p>
                    <p><strong>Wants to Learn:</strong> ${member.wantsToLearn.join(", ")}</p>
                `;

                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error("Error fetching members:", error);
        });

});
//CODE TO LOAD MEMBERS FROM DATABASE
//RESPONSIVE NAVBAR CODE
function toggleMenu() {
    var nav = document.getElementById("myTopnav");
    nav.classList.toggle("responsive");
}
