let container = document.getElementById('container');

function toggle() {
    container.classList.remove('start-animation');
    container.classList.toggle('sign-in');
    container.classList.toggle('sign-up');
    
    setTimeout(() => {
        container.classList.add('start-animation');
    }, 50);
}

function togglePassword(id, icon) {
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bx-hide");
        icon.classList.add("bx-show");
    } else {
        input.type = "password";
        icon.classList.remove("bx-show");
        icon.classList.add("bx-hide");
    }
}

// Form validation and submission
document.getElementById('signupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const password = this.querySelector('#password').value;
    const confirmPassword = this.querySelector('#confirm_password').value;
    
    if (password !== confirmPassword) {
        alert('Passwords do not match!');
        return;
    }
    
    // Here you would typically send the data to your server
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    console.log('Form submitted successfully:', data);
});

document.getElementById('signinForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    console.log('Login attempt:', data);
});

// Initialize animation on page load
window.addEventListener('load', () => {
    container.classList.add('sign-in');
    setTimeout(() => {
        container.classList.add('start-animation');
    }, 100);
});