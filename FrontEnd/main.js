//CODE TO LOAD MEMBERS FROM DATABASE
let allMembers = [];

document.addEventListener("DOMContentLoaded", () => {
    fetch("../backend/database.php")
        .then(response => response.json())
        .then(members => {
            const container = document.getElementById("members-container");

            if (!Array.isArray(members) || members.length === 0) {
                container.innerHTML = "<p>No members found.</p>";
                return;
            }

            allMembers = members;
            renderMembers(allMembers);

            const teachInput = document.getElementById("teach-filter");
            const learnInput = document.getElementById("learn-filter");

            function applyFilters() {
                console.log("hello");
                const teachQuery = teachInput.value.toLowerCase();
                const learnQuery = learnInput.value.toLowerCase();

                const filtered = allMembers.filter(member => {
                    const categories = (member.categories || []).map(c => c.toLowerCase());
                    const wants = (member.wantsToLearn || []).map(w => w.toLowerCase());

                    const matchesTeach =
                        !teachQuery ||
                        categories.some(cat => cat.includes(teachQuery));

                    const matchesLearn =
                        !learnQuery ||
                        wants.some(w => w.includes(learnQuery));

                    return matchesTeach && matchesLearn;
                });

                renderMembers(filtered);
            }

            const searchBtn = document.getElementById("search-btn");
            searchBtn.addEventListener("click", applyFilters);
        })
        .catch(error => {
            console.error("Error fetching members:", error);
            document.getElementById("members-container").innerHTML =
                "<p>Error loading members.</p>";
        });
});

function renderMembers(members) {
    const container = document.getElementById("members-container");
    container.innerHTML = "";

    if (members.length === 0) {
        container.innerHTML = "<p>No matching members found.</p>";
        return;
    }

    members.forEach(member => {
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
}
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
// Intersection Observer for scroll animations
// Each image appears ONLY when you scroll to it
const observerOptions = {
    threshold: 0.3, // Trigger when 30% of element is visible
    rootMargin: '0px 0px -100px 0px' // Need to scroll more before triggering
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        // Only animate when scrolling INTO view
        if (entry.isIntersecting && !entry.target.classList.contains('visible')) {
            entry.target.classList.add('visible');
            
            // Stop observing this element (one-time animation)
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe all testimonial items
document.querySelectorAll('.testimonial-item').forEach(item => {
    observer.observe(item);
});