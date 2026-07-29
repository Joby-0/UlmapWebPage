document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("contactForm");

    if (!form) return;

    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const phoneInput = document.getElementById("phone");
    const areaInput = document.getElementById("area");
    const messageInput = document.getElementById("message");

    function setError(input, message) {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");

        const feedback = input.parentElement.querySelector(".invalid-feedback");

        if (feedback) {
            feedback.textContent = message;
        }
    }

    function setValid(input) {
        input.classList.remove("is-invalid");
        input.classList.add("is-valid");

        const feedback = input.parentElement.querySelector(".invalid-feedback");

        if (feedback) {
            feedback.textContent = "";
        }
    }

    function clearValidation(input) {
        input.classList.remove("is-invalid", "is-valid");

        const feedback = input.parentElement.querySelector(".invalid-feedback");

        if (feedback) {
            feedback.textContent = "";
        }
    }

    function validateName() {
        const value = nameInput.value.trim();

        if (value === "") {
            setError(nameInput, "Vänligen ange ditt namn.");
            return false;
        }

        if (value.length < 2) {
            setError(nameInput, "Namnet måste vara minst 2 tecken.");
            return false;
        }

        setValid(nameInput);
        return true;
    }

    function validateEmail() {
        const value = emailInput.value.trim();

        if (value === "") {
            setError(emailInput, "Vänligen ange din e-postadress.");
            return false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(value)) {
            setError(emailInput, "Vänligen ange en giltig e-postadress.");
            return false;
        }

        setValid(emailInput);
        return true;
    }

    function validatePhone() {
        const value = phoneInput.value.trim();

        // Telefon är frivilligt
        if (value === "") {
            clearValidation(phoneInput);
            return true;
        }

        // Tillåter svenska telefonnummer med siffror,
        // mellanslag, +, bindestreck och parenteser
        const phoneRegex = /^[+]?[\d\s()-]{7,20}$/;

        if (!phoneRegex.test(value)) {
            setError(phoneInput, "Vänligen ange ett giltigt telefonnummer.");
            return false;
        }

        setValid(phoneInput);
        return true;
    }

    function validateArea() {
        if (areaInput.value === "") {
            setError(areaInput, "Vänligen välj ett område.");
            return false;
        }

        setValid(areaInput);
        return true;
    }

    function validateMessage() {
        const value = messageInput.value.trim();

        if (value === "") {
            setError(messageInput, "Vänligen skriv ett meddelande.");
            return false;
        }

        if (value.length < 10) {
            setError(
                messageInput,
                "Meddelandet måste vara minst 10 tecken."
            );
            return false;
        }

        setValid(messageInput);
        return true;
    }

    // Validera vid submit
    form.addEventListener("submit", (event) => {
        const isNameValid = validateName();
        const isEmailValid = validateEmail();
        const isPhoneValid = validatePhone();
        const isAreaValid = validateArea();
        const isMessageValid = validateMessage();

        const isFormValid =
            isNameValid &&
            isEmailValid &&
            isPhoneValid &&
            isAreaValid &&
            isMessageValid;

        if (!isFormValid) {
            event.preventDefault();

            // Scrolla till första felet
            const firstError = form.querySelector(".is-invalid");

            if (firstError) {
                firstError.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });

                firstError.focus();
            }
        }
    });

    // Ta bort fel när användaren börjar ändra fältet
    nameInput.addEventListener("input", validateName);
    emailInput.addEventListener("input", validateEmail);
    phoneInput.addEventListener("input", validatePhone);
    messageInput.addEventListener("input", validateMessage);

    areaInput.addEventListener("change", validateArea);
});