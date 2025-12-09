<?php
$pageTitle = 'Skill Swap - Learn Something, Teach Something';
include 'header.php';
?>

<div class="container">

    <!-- Hero -->
    <div class="hero"></div>

    <!-- Search -->
    <div class="search-container">
        <div class="search-box">
            <input
                type="text"
                id="searchInput"
                class="search-input"
                placeholder="Search by name, skill, or description..."
                autocomplete="off"
            >
        </div>

        <div class="filter-section">
            <span class="filter-label">Filter by category:</span>
            <div class="category-filter" id="categoryFilter"></div>
        </div>
    </div>

    <div class="results-count" id="resultsCount"></div>
    <div class="results-container" id="resultsContainer"></div>

</div>

<br><br>

<h2 style="text-align:center; font-size:33px; font-family:Georgia, 'Times New Roman', Times, serif;">
    What Our Users Are Saying:
</h2>

<div id="Testimonials">
    <div class="testimonial-item">
        <img src="images/akiko.png" alt="Akiko testimonial">
    </div>
    <div class="testimonial-item">
        <img src="images/alex.png" alt="Alex testimonial">
    </div>
    <div class="testimonial-item">
        <img src="images/ava3.png" alt="Ava testimonial">
    </div>
    <div class="testimonial-item">
        <img src="images/Matt.png" alt="Matt testimonial">
    </div>
    <div class="testimonial-item">
        <img src="images/mark.png" alt="Mark testimonial">
    </div>
</div>

<?php include 'footer.php'; ?>
        <script>
        // Member data
        const members = [
            {
                "name": "Geneviève DuPont",
                "user-id": "gen-dup",
                "age": 38,
                "description": "Experienced French tutor offering personalized lessons for beginners to advanced learners. Specializes in conversational skills and exam preparation.",
                "categories": ["French", "Tutor"],
                "image": "images/genevieve.jpg"
            },
            {
                "name": "Miguel Alvarez",
                "user-id": "mig-alv",
                "age": 29,
                "description": "Guitar teacher focusing on acoustic technique, chords, and beginner music theory. Lessons tailored to adults and teens.",
                "categories": ["Music", "Guitar", "Tutor"],
                "image": "images/miguel.jpg"
            },
            {
                "name": "Aisha Khan",
                "user-id": "aish-kha",
                "age": 30,
                "description": "Professional chef offering cooking classes in various cuisines including Italian, Indian, and Mediterranean.",
                "categories": ["Cooking", "Chef"],
                "image": "images/Aisha.jpg"
            },
            {
                "name": "Jake Thompson",
                "user-id": "jake-tho",
                "age": 27,
                "description": "Professional bowling coach with 10 years of experience in training beginners and advanced players.",
                "categories": ["Bowling", "Coach"],
                "image": "images/jake.png"
            },
            {
                "name": "Olivia Brown",
                "user-id": "oliv-bro",
                "age": 26,
                "description": "Certified personal trainer specializing in strength training, cardio, and nutrition guidance.",
                "categories": ["Sports", "Personal Trainer"],
                "image": "images/olivia.jpg"
            },
            {
                "name": "Liam Garcia",
                "user-id": "liam-gar",
                "age": 32,
                "description": "Experienced soccer coach offering training for youth and adult players, focusing on skills development and teamwork.",
                "categories": ["Soccer", "Coach"],
                "image": "images/liam.png"
            },
            {
                "name": "Sophia Lee",
                "user-id": "soph-lee",
                "age": 24,
                "description": "Piano teacher with a passion for classical and contemporary music, catering to students of all ages and skill levels.",
                "categories": ["Music", "Piano", "Tutor"],
                "image": "images/sophia.png"
            },
            {
                "name": "Emma Wilson",
                "user-id": "emma-wil",
                "age": 28,
                "description": "Certified yoga instructor specializing in Hatha and Vinyasa yoga, promoting physical and mental well-being.",
                "categories": ["Yoga", "Instructor"],
                "image": "images/emma.png"
            },
            {
                "name": "Noah Martinez",
                "user-id": "noah-mar",
                "age": 31,
                "description": "Experienced basketball coach focusing on skill development, strategy, and teamwork for players of all levels.",
                "categories": ["Basketball", "Coach"],
                "image": "images/Noah.png"
            },
            {
                "name": "Isabella Hernandez",
                "user-id": "isab-her",
                "age": 29,
                "description": "Professional gardener offering lessons in plant care, landscaping, and sustainable gardening practices.",
                "categories": ["Gardening", "Instructor"],
                "image": "images/isabella.png"
            },
            {
                "name": "Ethan Robinson",
                "user-id": "ethan-rob",
                "age": 27,
                "description": "Experienced pet trainer specializing in obedience training, behavior modification, and agility training for dogs.",
                "categories": ["Pets", "Trainer"],
                "image": "images/ethan.png"
            },
            {
                "name": "Ava Clark",
                "user-id": "ava-cla",
                "age": 26,
                "description": "Outdoor survival skills instructor with expertise in wilderness navigation, shelter building, and foraging.",
                "categories": ["Outdoors", "Instructor"],
                "image": "images/ava.png"
            },
            {
                "name": "James Lewis",
                "user-id": "jame-lew",
                "age": 33,
                "description": "Fishing guide and instructor with extensive knowledge of freshwater and saltwater fishing techniques.",
                "categories": ["Outdoors", "Fishing", "Guide"],
                "image": "images/james.png"
            },
            {
                "name": "Mia Walker",
                "user-id": "mia-wal",
                "age": 30,
                "description": "Hiking guide specializing in trail navigation, safety, and outdoor skills for all experience levels.",
                "categories": ["Outdoors", "Hiking", "Guide"],
                "image": "images/mia.png"
            }
        ];

        let selectedCategories = [];

        // Get all unique categories
        function getAllCategories() {
            const categories = new Set();
            members.forEach(member => {
                member.categories.forEach(cat => categories.add(cat));
            });
            return Array.from(categories).sort();
        }

        // Create category filter buttons
        function createCategoryFilters() {
            const filterContainer = document.getElementById('categoryFilter');
            const categories = getAllCategories();
            
            categories.forEach(category => {
                const btn = document.createElement('button');
                btn.className = 'category-btn';
                btn.textContent = category;
                btn.onclick = () => toggleCategory(category, btn);
                filterContainer.appendChild(btn);
            });
        }

        // Toggle category selection
        function toggleCategory(category, btn) {
            if (selectedCategories.includes(category)) {
                selectedCategories = selectedCategories.filter(c => c !== category);
                btn.classList.remove('active');
            } else {
                selectedCategories.push(category);
                btn.classList.add('active');
            }
            filterMembers();
        }

        // Filter members based on search and categories
        function filterMembers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            const filtered = members.filter(member => {
                // Check if matches search term
                const matchesSearch = 
                    member.name.toLowerCase().includes(searchTerm) ||
                    member.description.toLowerCase().includes(searchTerm) ||
                    member.categories.some(cat => cat.toLowerCase().includes(searchTerm)) ||
                    member.age.toString().includes(searchTerm);
                
                // Check if matches selected categories (if any)
                const matchesCategory = selectedCategories.length === 0 || 
                    selectedCategories.some(cat => member.categories.includes(cat));
                
                return matchesSearch && matchesCategory;
            });
            
            displayMembers(filtered);
        }

        // Display members
        function displayMembers(membersToDisplay) {
            const container = document.getElementById('resultsContainer');
            const countElement = document.getElementById('resultsCount');
            
            container.innerHTML = '';
            
            if (membersToDisplay.length === 0) {
                container.innerHTML = '<div class="no-results">😔 No instructors found. Try adjusting your search!</div>';
                countElement.textContent = '';
                return;
            }
            
            countElement.textContent = `Found ${membersToDisplay.length} instructor${membersToDisplay.length !== 1 ? 's' : ''}`;
            
            membersToDisplay.forEach(member => {
                const card = document.createElement('div');
                card.className = 'member-card';
                card.onclick = () => showHandshakeAndChat(member);
                
                card.innerHTML = `
                    <div class="member-avatar">
                        <img src="${member.image}" alt="${member.name}" onerror="this.src='https://via.placeholder.com/300?text=${encodeURIComponent(member.name)}'">
                    </div>
                    <div class="member-name">${member.name}</div>
                    <div class="member-age">Age: ${member.age}</div>
                    <div class="member-description">${member.description}</div>
                    <div class="member-categories">
                        ${member.categories.map(cat => `<span class="category-tag">${cat}</span>`).join('')}
                    </div>
                `;
                
                container.appendChild(card);
            });
        }

        // Initialize
        function init() {
            createCategoryFilters();
            displayMembers(members);
            
            // Add search input listener
            document.getElementById('searchInput').addEventListener('input', filterMembers);
        }

        // Show handshake animation then chat
        function showHandshakeAndChat(member) {
            // Create handshake overlay
            const handshakeOverlay = document.createElement('div');
            handshakeOverlay.className = 'handshake-overlay';
            handshakeOverlay.innerHTML = `
                <img src="images/handshake.gif" 
                     alt="Handshake" 
                     class="handshake-gif"
                     onerror="this.src='https://media.giphy.com/media/3oEjHWPTo7c0ajPwty/giphy.gif'">
            `;
            document.body.appendChild(handshakeOverlay);
            
            // Remove handshake and show chat after 2 seconds
            setTimeout(() => {
                handshakeOverlay.remove();
                openChatModal(member);
            }, 2000);
        }

        const API_ENDPOINT = 'https://joonh.sgedu.site/skillswap/backend/chat-api.php'; 

    // Helper function to escape HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, (m) => map[m]);
    }

    // Open chat modal
    function openChatModal(member) {
        // Remove any existing modal first
        document.querySelectorAll('.chat-modal').forEach(modal => modal.remove());

        const chatModal = document.createElement('div');
        chatModal.className = 'chat-modal';
        chatModal.innerHTML = `
            <div class="chat-container">
                <div class="chat-header">
                    <div class="chat-header-info">
                        <img src="${member.image}" alt="${member.name}" class="chat-avatar" onerror="this.src='https://via.placeholder.com/50'">
                        <div class="chat-user-info">
                            <h3>${member.name}</h3>
                            <p>${member.categories.join(', ')}</p>
                        </div>
                    </div>
                    <button class="close-chat" onclick="this.closest('.chat-modal').remove()">×</button>
                </div>
                <div class="chat-messages" id="chatMessages">
                    <div class="chat-welcome">
                        <h4>🤝 Start a conversation with ${member.name}!</h4>
                        <p>Introduce yourself and let them know what you'd like to learn.</p>
                    </div>
                </div>
                <div class="chat-input-container">
                    <div class="chat-input-wrapper">
                        <input type="text" class="chat-input" placeholder="Type your message here..." id="messageInput">
                        <button class="send-btn" id="sendBtn">Send</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(chatModal);
        
        // Store current member and initialize conversation history
        chatModal.currentMember = member;
        chatModal.conversationHistory = []; // <-- INITIALIZE HISTORY
        
        // Focus on input
        setTimeout(() => {
            document.getElementById('messageInput').focus();
        }, 100);
        
        // Add event listeners
        document.getElementById('sendBtn').addEventListener('click', () => sendMessage(chatModal));
        document.getElementById('messageInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage(chatModal);
            }
        });
    }

    // Send message function with AI response (UPDATED FOR HISTORY)
    async function sendMessage(chatModal) {
        const input = document.getElementById('messageInput');
        const message = input.value.trim();
        
        if (!message) return;
        
        const member = chatModal.currentMember;
        const messagesContainer = document.getElementById('chatMessages');
        
        // Remove welcome message if it exists
        const welcomeMsg = messagesContainer.querySelector('.chat-welcome');
        if (welcomeMsg) {
            welcomeMsg.remove();
        }
        
        // Add user message to UI
        const userMessage = document.createElement('div');
        userMessage.className = 'message user';
        userMessage.innerHTML = `<div class="message-bubble">${escapeHtml(message)}</div>`;
        messagesContainer.appendChild(userMessage);

        // Add user message to history (role: user)
        chatModal.conversationHistory.push({ role: "user", parts: [{ text: message }] }); 
        
        // Clear input
        input.value = '';
        
        // Scroll to bottom
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        // Show typing indicator
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message instructor';
        typingDiv.innerHTML = `
            <div class="typing-indicator">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;
        messagesContainer.appendChild(typingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        // Get AI response
        try {
            // Pass the ENTIRE history to the backend
            const response = await getAIResponse(chatModal.conversationHistory, member); 
            
            // Remove typing indicator
            typingDiv.remove();
            
            // Add instructor message to UI
            const instructorMessage = document.createElement('div');
            instructorMessage.className = 'message instructor';
            instructorMessage.innerHTML = `<div class="message-bubble">${escapeHtml(response)}</div>`;
            messagesContainer.appendChild(instructorMessage);

            // Add AI message to history (role: model)
            chatModal.conversationHistory.push({ role: "model", parts: [{ text: response }] });
            
            // Scroll to bottom
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        } catch (error) {
            typingDiv.remove();
            console.error('Error getting AI response:', error);
            
            // Simple error handling messages
            const errorMessage1 = document.createElement('div');
            errorMessage1.className = 'message instructor';
            errorMessage1.innerHTML = `<div class="message-bubble">Hello! I am away from my computer right now, please try again later!</div>`;
            messagesContainer.appendChild(errorMessage1);
            
            messagesContainer.appendChild(document.createElement('br')); 

            const errorMessage2 = document.createElement('div');
            errorMessage2.className = 'message instructor';
            errorMessage2.innerHTML = `<div class="message-bubble">I appreciate your patience.</div>`;
            messagesContainer.appendChild(errorMessage2);
            
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            
            chatModal.conversationHistory.pop(); 
        }
    }

    // Get AI response using your PHP backend (UPDATED FOR HISTORY)
    async function getAIResponse(history, member) {
        // Construct the payload matching what chat-api.php expects
        const payload = {
            history: history, // <-- Send the entire history array
            memberName: member.name,
            memberAge: member.age,
            memberCategories: member.categories.join(', '),
            memberDescription: member.description
        };

        const response = await fetch(API_ENDPOINT, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        // Handle HTTP errors
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`API Request Failed (HTTP ${response.status}): ${errorText}`);
        }

        const data = await response.json();

        // Handle errors returned in the JSON payload
        if (data.error) {
            throw new Error(`Backend Error: ${data.error} - ${data.details || ''}`);
        }

        // Return the AI's response text
        return data.response;
    }

        // Run initialization function
        init();
        // Load footer dynamically

        // Scroll animation for testimonials - appears one by one as you scroll
        const testimonialObserverOptions = {
            threshold: 0.3,
            rootMargin: '0px 0px -100px 0px'
        };

        const testimonialObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('visible')) {
                    entry.target.classList.add('visible');
                    testimonialObserver.unobserve(entry.target);
                }
            });
        }, testimonialObserverOptions);

        document.querySelectorAll('.testimonial-item').forEach(item => {
            testimonialObserver.observe(item);
        });
    </script>
