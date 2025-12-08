window.onload = () => {
    const form = document.getElementById("signin");
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

    let emailValid = false;
    let passwordValid = false;

    email.addEventListener("input", function(e) {
        showError("");
        if (email.value.length < 1){
            e.preventDefault();
            showError('Enter a username or email.');
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
        if (emailValid && passwordValid) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    });

    form.addEventListener("submit", function(e){
        showError("");

        if (!email.validity.valid) {
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
        submitBtn.textContent = "Signing in…";
    });
};

function googlesignin() {
    console.log("google sign in detected");
}