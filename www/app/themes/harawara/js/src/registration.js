/**
 * Registration Form Validation and UX
 */
class RegistrationForm {
    constructor() {
        this.form = document.querySelector('.registration-form');
        if (!this.form) return;

        this.init();
    }

    init() {
        this.bindEvents();
        this.setupPasswordValidation();
    }

    bindEvents() {
        this.form.addEventListener('submit', this.handleSubmit.bind(this));

        // Real-time validation
        const inputs = this.form.querySelectorAll('input[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', this.validateField.bind(this, input));
            input.addEventListener('input', this.clearFieldError.bind(this, input));
        });
    }

    setupPasswordValidation() {
        const password = this.form.querySelector('#password');
        const confirmPassword = this.form.querySelector('#confirm_password');

        if (password && confirmPassword) {
            confirmPassword.addEventListener('input', () => {
                this.validatePasswordMatch(password, confirmPassword);
            });
        }
    }

    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // Remove existing error styling
        this.clearFieldError(field);

        // Field-specific validation
        switch (field.name) {
            case 'username':
                if (value.length < 3) {
                    isValid = false;
                    errorMessage = 'Username must be at least 3 characters long.';
                } else if (!/^[a-zA-Z0-9_-]+$/.test(value)) {
                    isValid = false;
                    errorMessage =
                        'Username can only contain letters, numbers, underscores, and hyphens.';
                }
                break;

            case 'email':
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address.';
                }
                break;

            case 'password':
                if (value.length < 8) {
                    isValid = false;
                    errorMessage = 'Password must be at least 8 characters long.';
                }
                break;

            case 'confirm_password':
                const password = this.form.querySelector('#password').value;
                if (value !== password) {
                    isValid = false;
                    errorMessage = 'Passwords do not match.';
                }
                break;
        }

        if (!isValid) {
            this.showFieldError(field, errorMessage);
        }

        return isValid;
    }

    validatePasswordMatch(password, confirmPassword) {
        if (confirmPassword.value && password.value !== confirmPassword.value) {
            this.showFieldError(confirmPassword, 'Passwords do not match.');
            return false;
        } else {
            this.clearFieldError(confirmPassword);
            return true;
        }
    }

    showFieldError(field, message) {
        field.classList.add('error');

        // Remove existing error message
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }

        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.style.cssText = 'color: #dc3545; font-size: 14px; margin-top: 5px;';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    clearFieldError(field) {
        field.classList.remove('error');
        const errorDiv = field.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    validateForm() {
        const requiredFields = this.form.querySelectorAll('input[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    handleSubmit(e) {
        if (!this.validateForm()) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        this.showLoadingState();
    }

    showLoadingState() {
        this.form.classList.add('loading');
        const submitBtn = this.form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.textContent = 'Creating Account...';
            submitBtn.disabled = true;
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new RegistrationForm();
});

export default RegistrationForm;
