function togglePassword(inputId, buttonId) {
    const passwordInput = document.getElementById(inputId);
    const toggleButton = document.getElementById(buttonId);
    
    if (passwordInput && toggleButton) {
        toggleButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    togglePassword('senhaLogin', 'toggleSenha');
    togglePassword('senhaCadastro', 'toggleSenhaCadastro');
 
    const btnSair = document.getElementById('sair');
    if (btnSair) {
        btnSair.addEventListener('click', function() {
            window.location.href = 'logout.php';
        });
    }
});