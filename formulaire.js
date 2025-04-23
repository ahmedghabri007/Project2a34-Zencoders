document.getElementById("profileForm").addEventListener("submit", function(event) {
    const fullname = document.querySelector("input[name='fullname']");
    const age = document.querySelector("input[name='age']");
    const gender = document.querySelector("select[name='gender']");
    const location = document.querySelector("input[name='location']");
    const profession = document.querySelector("input[name='profession']");
    const interests = document.querySelector("input[name='interests']");
    const biography = document.querySelector("textarea[name='biography']");
    const phone = document.querySelector("input[name='phone']");

    let errorMessages = [];

    // Clear previous error messages
    const errorElements = document.querySelectorAll(".error-message");
    errorElements.forEach(function(errorElement) {
        errorElement.remove();
    });

    // Full Name validation
    if (!fullname.value.trim()) {
        const error = createErrorMessage("❌ Le nom complet est requis.");
        fullname.parentElement.appendChild(error);
        errorMessages.push("fullname");
    }

    // Age validation
    if (!age.value.trim() || age.value < 18) {
        const error = createErrorMessage("❌ L'âge doit être au moins 18.");
        age.parentElement.appendChild(error);
        errorMessages.push("age");
    }

    // Gender validation
    if (!gender.value) {
        const error = createErrorMessage("❌ Veuillez sélectionner un genre.");
        gender.parentElement.appendChild(error);
        errorMessages.push("gender");
    }

    // Location validation
    if (!location.value.trim()) {
        const error = createErrorMessage("❌ La localisation est requise.");
        location.parentElement.appendChild(error);
        errorMessages.push("location");
    }

    // Profession validation
    if (!profession.value.trim()) {
        const error = createErrorMessage("❌ La profession est requise.");
        profession.parentElement.appendChild(error);
        errorMessages.push("profession");
    }

    // Interests validation
    if (!interests.value.trim()) {
        const error = createErrorMessage("❌ Les centres d'intérêt sont requis.");
        interests.parentElement.appendChild(error);
        errorMessages.push("interests");
    } else if (interests.value.includes(" ") && !interests.value.includes(",")) {
        const error = createErrorMessage("❌ Si vous avez plusieurs centres d’intérêt, séparez-les avec des virgules.");
        interests.parentElement.appendChild(error);
        errorMessages.push("interests");
    }

    // Biography validation
    if (!biography.value.trim() || biography.value.length < 20) {
        const error = createErrorMessage("❌ La biographie doit contenir au moins 20 caractères.");
        biography.parentElement.appendChild(error);
        errorMessages.push("biography");
    }

    // Phone validation (8 digits)
    const phoneRegex = /^\d{8}$/;
    if (!phone.value.trim() || !phone.value.match(phoneRegex)) {
        const error = createErrorMessage("❌ Le numéro de téléphone doit être valide (8 chiffres).");
        phone.parentElement.appendChild(error);
        errorMessages.push("phone");
    }

    // Prevent form submission if there are validation errors
    if (errorMessages.length > 0) {
        event.preventDefault();  // Prevent form submission
    }
});

// Helper function to create error message
function createErrorMessage(message) {
    const errorElement = document.createElement("div");
    errorElement.classList.add("error-message");
    errorElement.style.color = "red";
    errorElement.style.fontSize = "14px";
    errorElement.style.marginTop = "5px";
    errorElement.textContent = message;
    return errorElement;
}
