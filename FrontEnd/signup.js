window.onload = () => {
    const form = document.getElementById("signup");
    const fullName = document.getElementById("fullName");
    const username = document.getElementById("username");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const error = document.getElementById("error");
    const submitBtn = document.getElementById("submitBtn");

    form.reset();
    submitBtn.disabled = true;

    function showError(message){
        error.textContent = message;
        error.style.display = message !== "" ? "block" : "none";
    }

    let fullNameValid = false;
    let usernameValid = false;
    let emailValid = false;
    let passwordValid = false;

    fullName.addEventListener("input", function(e) {
        showError("");
        if (fullName.value.length < 1) {
            e.preventDefault();
            showError("Please enter your name.");
            fullNameValid = false;
        } else {
            fullNameValid = true;
        }
    });
    username.addEventListener("input", function(e) {
        showError("");
        if (username.value.length < 1) {
            e.preventDefault();
            showError("Please enter a username.");
            usernameValid = false;
        } else {
            usernameValid = true;
        }
    });
    email.addEventListener("input", function(e) {
        showError("");
        if (email.classList.contains(":invalid")) {
            e.preventDefault();
            showError("Enter a valid email address.");
            emailValid = false;
        } else {
            emailValid = true;
        }
    });
    password.addEventListener("input", function(e) {
        showError("");
        if (password.value.length < 8){
            e.preventDefault();
            showError('Password must be at least 8 characters.');
            passwordValid = false;
        } else {
            passwordValid = true;
        }
    });

    form.addEventListener("input", function(e) {
        if (fullNameValid && usernameValid && emailValid && passwordValid) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    });

    form.addEventListener("submit", function(e){
        showError("");

        if (fullName.value.length < 1) {
            e.preventDefault();
            showError("Please enter your name.");
            fullName.focus();
            return;
        } else if (username.value.length < 1) {
            e.preventDefault();
            showError("Please enter a username.");
            username.focus();
            return;
        } else if (email.classList.contains(":invalid")) {
            e.preventDefault();
            showError("Enter a valid email address.");
            email.focus();
            return;
        } else if (password.value.length < 8){
            e.preventDefault();
            showError("Password must be at least 8 characters.");
            password.focus();
            return;
        }


        submitBtn.disabled = true;
        submitBtn.textContent = "Creating…";
    });
};
