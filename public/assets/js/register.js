document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ register.js loaded");

    const roleRadios = document.querySelectorAll('input[name="role"]');
    const vendorFields = document.getElementById('vendorFields');
    const nidField = document.getElementById('nidField');

    function handleRoleChange(role) {
        if (!vendorFields || !nidField) return;

        if (role === 'VENDOR') {
            vendorFields.style.display = 'block';
            nidField.style.display = 'none';
        } else {
            vendorFields.style.display = 'none';
            nidField.style.display = 'block';
        }
    }

    roleRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            handleRoleChange(this.value);
        });
    });

    // Initial state
    const checkedRole = document.querySelector('input[name="role"]:checked');
    if (checkedRole) {
        handleRoleChange(checkedRole.value);
    }

    const passwordInput = document.getElementById('password');
    const showPasswordCheckbox = document.getElementById('showPassword');

    if (passwordInput && showPasswordCheckbox) {
        showPasswordCheckbox.addEventListener('change', function () {
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    }
});
