document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        return response.json();
    })
    .then(result => {
        
        const errorMessageDiv = document.getElementById('error-message');
        
        if (result.status === 'error') {
            errorMessageDiv.textContent = result.message;
            errorMessageDiv.classList.remove('d-none');
        } else {
            window.location.href = result.redirect;
        }
    })
    .catch(error => {
    });
});

document.getElementById('floatingInput').addEventListener('input', function() {
    document.getElementById('error-message').classList.add('d-none');
});
document.getElementById('floatingPassword').addEventListener('input', function() {
    document.getElementById('error-message').classList.add('d-none');
});
