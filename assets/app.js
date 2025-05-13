/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

/////////////////////////////////////////
// Mailing
/////////////////////////////////////////
document.addEventListener("DOMContentLoaded", function() {
    console.log("JS chargé ✅");

    const contactLink = document.getElementById("contact-email");
    const form = document.getElementById("contact-form");

    const email = "chr" + "ist" + "ell" + "e.b" + "orr" + "ego" + "@ou" + "tlo" + "ok." + "com";

    const nameField = document.getElementById("name");
    const emailField = document.getElementById("email");
    const subjectField = document.getElementById("subject");
    const messageField = document.getElementById("message");

    contactLink.addEventListener("click", function(e) {
        e.preventDefault();

        form.querySelectorAll('.form-control').forEach(field => {
            field.classList.remove('is-invalid');
        });

        const name = nameField.value.trim();
        const senderEmail = emailField.value.trim();
        const subject = subjectField.value;
        const message = messageField.value.trim();

        let isValid = true;

        if (!name) {
            nameField.classList.add("is-invalid");
            isValid = false;
        }

        if (!senderEmail || !validateEmail(senderEmail)) {
            emailField.classList.add("is-invalid");
            isValid = false;
        }

        if (!subject) {
            subjectField.classList.add("is-invalid");
            isValid = false;
        }

        if (!message) {
            messageField.classList.add("is-invalid");
            isValid = false;
        }

        if (!isValid) return;

        const body = encodeURIComponent(
            `Nom: ${name}\nEmail: ${senderEmail}\nSujet: ${subject}\n\nMessage:\n${message}`
        );

        const mailtoLink = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${body}`;

        window.location.href = mailtoLink;
    });

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Suppression dynamique de la bordure rouge
    nameField.addEventListener("input", function () {
        if (nameField.value.trim().length > 0) {
            nameField.classList.remove("is-invalid");
        }
    });

    emailField.addEventListener("input", function () {
        if (validateEmail(emailField.value.trim())) {
            emailField.classList.remove("is-invalid");
        }
    });

    subjectField.addEventListener("change", function () {
        if (subjectField.value) {
            subjectField.classList.remove("is-invalid");
        }
    });

    const messageCounter = document.getElementById("message-counter");
    
    messageField.addEventListener("input", function () {
        const currentLength = messageField.value.length;
        messageCounter.textContent = `${currentLength} / 1000`;
    
        if (currentLength > 1000) {
            messageField.classList.add("is-invalid");
        } else if (currentLength > 9) {
            messageField.classList.remove("is-invalid");
        }
    });
});

/////////////////////////////////////////
// MAILING END
/////////////////////////////////////////

