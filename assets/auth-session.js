// A page restored from browser history may contain a token from a previous session.
window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        window.location.reload();
    }
});

const passwordToggle = document.querySelector('[data-toggle-password]');
if (passwordToggle) {
    passwordToggle.addEventListener('click', () => {
        const password = document.getElementById(passwordToggle.getAttribute('aria-controls'));
        const visible = password.type === 'password';
        password.type = visible ? 'text' : 'password';
        passwordToggle.setAttribute('aria-pressed', String(visible));
        passwordToggle.setAttribute('aria-label', visible ? 'Nascondi password' : 'Mostra password');
        passwordToggle.setAttribute('title', visible ? 'Nascondi password' : 'Mostra password');
    });
}
