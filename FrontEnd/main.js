//CODE TO LOAD MEMBERS FROM DATABASE
let allMembers = [];

document.addEventListener("DOMContentLoaded", () => {
    // Check if we're on a page that has the members container
    const container = document.getElementById("members-container");
    
    // If not on members page, skip this code
    if (!container) {
        console.log("Not on members page, skipping member loading");
        return;
    }

    fetch("../backend/database.php")
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(members => {
            if (!Array.isArray(members) || members.length === 0) {
                container.innerHTML = "<p>No members found.</p>";
                return;
            }

            allMembers = members;
            renderMembers(allMembers);

            const teachInput = document.getElementById("teach-filter");
            const learnInput = document.getElementById("learn-filter");
            const searchBtn = document.getElementById("search-btn");

            // Only add listeners if elements exist
            if (teachInput && learnInput && searchBtn) {
                function applyFilters() {
                    console.log("Applying filters");
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

                searchBtn.addEventListener("click", applyFilters);
            }
        })
        .catch(error => {
            console.error("Error fetching members:", error);
            // Only update container if it exists
            if (container) {
                container.innerHTML = "<p>Error loading members. Please try again later.</p>";
            }
        });
});

function renderMembers(members) {
    const container = document.getElementById("members-container");
    
    // Safety check
    if (!container) {
        console.error("members-container element not found");
        return;
    }
    
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

//RESPONSIVE NAVBAR CODE
function toggleMenu() {
    const nav = document.getElementById("myTopnav");
    if (nav) {
        nav.classList.toggle("responsive");
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('submitBtn');

    if (submitBtn) {
        submitBtn.addEventListener('click', function(event) {
            window.location.href = 'success.html';
        });
    }
});

// Intersection Observer for scroll animations
// Each image appears ONLY when you scroll to it
const observerOptions = {
    threshold: 0.3, 
    rootMargin: '0px 0px -100px 0px' 
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        // Only animate when scrolling INTO view
        if (entry.isIntersecting && !entry.target.classList.contains('visible')) {
            entry.target.classList.add('visible');
            
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Only observe testimonials if they exist on the page
document.addEventListener('DOMContentLoaded', function() {
    const testimonials = document.querySelectorAll('.testimonial-item');
    if (testimonials.length > 0) {
        testimonials.forEach(item => {
            observer.observe(item);
        });
    }
});