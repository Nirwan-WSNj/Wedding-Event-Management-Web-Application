document.addEventListener('DOMContentLoaded', function() {
    window.togglePassword = function(element) {
        const inputId = element.getAttribute('data-input-id');
        const passwordField = document.getElementById(inputId);
        const toggleIcon = element;
        if (passwordField && toggleIcon) {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            toggleIcon.classList.toggle('ri-eye-line');
            toggleIcon.classList.toggle('ri-eye-off-line');
        } else {
            console.error(`Element not found: inputId=${inputId}`);
        }
    };
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function() {
            togglePassword(this);
        });
    });
});