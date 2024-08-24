document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    let isValid = true;
    let errors = {};

    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const passwordConfirmation = document.getElementById('password_confirmation').value.trim();

    // Reset error messages
    document.getElementById('first_name_error').textContent = '';
    document.getElementById('last_name_error').textContent = '';
    document.getElementById('phone_error').textContent = '';
    document.getElementById('email_error').textContent = '';
    document.getElementById('password_error').textContent = '';
    document.getElementById('password_confirmation_error').textContent = '';

    // Validate required fields
    if (!firstName || !lastName || !phone || !email || !password || !passwordConfirmation) {
        document.getElementById('password_confirmation_error').textContent = 'Sva polja su obavezna';
        isValid = false;
    } else {
        // Validate first name and last name
        if (!/^[a-zA-Z]+$/.test(firstName)) {
            errors.firstName = 'Ime može sadržati samo slova';
            isValid = false;
        }
        if (!/^[a-zA-Z]+$/.test(lastName)) {
            errors.lastName = 'Prezime može sadržati samo slova';
            isValid = false;
        }

        // Validate phone number
        if (!/^\d{1,10}$/.test(phone)) {
            errors.phone = 'Broj telefona neispravno unet';
            isValid = false;
        }

        // Validate email
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.email = 'Nevažeća email adresa';
            isValid = false;
        }

        // Validate password
        if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
            errors.password = 'Lozinka mora sadržati veliko slovo, malo slovo i broj';
            isValid = false;
        }

        // Validate password confirmation
        if (password !== passwordConfirmation) {
            errors.passwordConfirmation = 'Lozinke se ne podudaraju';
            isValid = false;
        }
    }

    // Display errors
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
        // If everything is valid, send the data via AJAX
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
                // Optionally, redirect to another page or clear the form
            } else {
                alert(result.message);
            }
        })
        .catch(error => {
            alert("Došlo je do greške. Pokušajte ponovo.");
        });
    }
});
