document.getElementById('register-form').addEventListener('submit', function(event) {
    event.preventDefault();

    let isValid = true;
    let errors = {};

    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const passwordConfirmation = document.getElementById('password_confirmation').value.trim();

    // Reset error poruka
    document.getElementById('first_name_error').textContent = '';
    document.getElementById('last_name_error').textContent = '';
    document.getElementById('phone_error').textContent = '';
    document.getElementById('email_error').textContent = '';
    document.getElementById('password_error').textContent = '';
    document.getElementById('password_confirmation_error').textContent = '';

    // Provera prisustva svih obaveznih polja
    if (!firstName || !lastName || !phone || !email || !password || !passwordConfirmation) {
        document.getElementById('password_confirmation_error').textContent = 'Sva polja su obavezna';
        isValid = false;
    } else {
        // Provera imena i prezimena
        if (!/^[a-zA-Z]+$/.test(firstName)) {
            errors.firstName = 'Ime može sadržati samo slova';
            isValid = false;
        }
        if (!/^[a-zA-Z]+$/.test(lastName)) {
            errors.lastName = 'Prezime može sadržati samo slova';
            isValid = false;
        }

        // Provera broja telefona
        if (!/^\d{1,10}$/.test(phone)) {
            errors.phone = 'Broj telefona neispravno unet';
            isValid = false;
        }

        // Provera emaila
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.email = 'Nevažeća email adresa';
            isValid = false;
        }

        // Provera lozinke
        if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
            errors.password = 'Lozinka mora sadržati veliko slovo, malo slovo i broj';
            isValid = false;
        }

        // Potvrda lozinke
        if (password !== passwordConfirmation) {
            errors.passwordConfirmation = 'Lozinke se ne podudaraju';
            isValid = false;
        }
    }

    // Ispis errora
    if (!isValid) {
        if (errors.firstName) {
            document.getElementById('first_name_error').textContent = errors.firstName;
        }
        if (errors.lastName) {
            document.getElementById('last_name_error').textContent = errors.lastName;
        }
        if (errors.phone) {
            document.getElementById('phone_error').textContent = errors.phone;
        }
        if (errors.email) {
            document.getElementById('email_error').textContent = errors.email;
        }
        if (errors.password) {
            document.getElementById('password_error').textContent = errors.password;
        }
        if (errors.passwordConfirmation) {
            document.getElementById('password_confirmation_error').textContent = errors.passwordConfirmation;
        }
    } else {
        // Ako je sve u redu, slanje AJAXom
        const data = {
            first_name: firstName,
            last_name: lastName,
            phone: phone,
            email: email,
            password: password
        };

        fetch('registration_route.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert(result.message);
            } else {
                alert(result.message);
            }
        })
        .catch(error => {
            alert("Došlo je do greške. Pokušajte ponovo.");
        });
    }
});

// "Zaboravljena lozinka"
document.getElementById('forgot-password-link').addEventListener('click', function(event) {
    event.preventDefault();
    const email = prompt("Unesite vašu email adresu za resetovanje lozinke:");
    if (email) {
        // Logika za slanje email-a za reset lozinke
        alert("Uputstva za resetovanje lozinke su poslata na " + email);
    }
});
