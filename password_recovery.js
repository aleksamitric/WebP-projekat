document.addEventListener('DOMContentLoaded', function() {
    const forgotPasswordLink = document.getElementById('forgot-password-link');

    forgotPasswordLink.addEventListener('click', function(event) {
        event.preventDefault();

        const userEmail = prompt("Unesite vašu email adresu za oporavak lozinke:");

        if (userEmail) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (emailPattern.test(userEmail)) {
                // Send AJAX request to PHP script
                fetch('check_email.php?email=' + encodeURIComponent(userEmail))
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'exists') {
                            alert("Na " + userEmail + " je poslat link za oporavak lozinke.");
                        } else if (data === 'not_exists') {
                            alert("Email adresa nije pronađena. Pokušajte ponovo.");
                        } else if (data === 'invalid_email') {
                            alert("Uneli ste nevažeću email adresu. Pokušajte ponovo.");
                        } else {
                            alert("Došlo je do greške. Pokušajte ponovo.");
                        }
                    })
                    .catch(error => {
                        alert("Došlo je do greške. Pokušajte ponovo.");
                    });
            } else {
                alert("Uneli ste nevažeću email adresu. Pokušajte ponovo.");
            }
        } else {
            alert("Oporavak lozinke je otkazan.");
        }
    });
});
