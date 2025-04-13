<form action="submitForum.php" method="POST" class="contact-form" onsubmit="return validateForm();">
    <input type="text" name="sujet" id="sujet" placeholder="Sujet">
    <span id="sujetError" class="error"></span>

    <textarea name="contenu" id="contenu" placeholder="Contenu" rows="5"></textarea>
    <span id="contenuError" class="error"></span>

    <input type="text" name="date_publication" id="date_publication" placeholder="YYYY-MM-DD">
    <span id="dateError" class="error"></span>

    <button type="submit" class="btn btn-primary">Add Forum</button>
</form>

<script>
function validateForm() {
    let isValid = true;

    // Clear old errors
    document.querySelectorAll(".error").forEach(el => el.innerText = "");

    const sujet = document.getElementById("sujet").value.trim();
    const contenu = document.getElementById("contenu").value.trim();
    const date = document.getElementById("date_publication").value.trim();
    const dateRegex = /^\d{4}-\d{2}-\d{2}$/;

    if (sujet === "") {
        document.getElementById("sujetError").innerText = "Sujet requis.";
        isValid = false;
    }

    if (contenu === "") {
        document.getElementById("contenuError").innerText = "Contenu requis.";
        isValid = false;
    }

    if (!dateRegex.test(date)) {
        document.getElementById("dateError").innerText = "Format date: YYYY-MM-DD.";
        isValid = false;
    }

    return isValid;
}
</script>
