import './bootstrap';

// Basic JavaScript functionality for the ecommerce application
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });

    // Cart quantity update functionality
    const quantityInputs = document.querySelectorAll('input[name="quantity"]');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });

    // Add to cart button loading state
    const addToCartButtons = document.querySelectorAll('form[action*="cart/add"] button[type="submit"]');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';
            this.disabled = true;
        });
    });

    // Form validation (excluding admin dashboard forms which have their own validation)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        // Skip validation for admin dashboard forms and specific forms with custom validation
        if (form.closest('.dashboard-main-body') || 
            form.id === 'product-form' || 
            form.classList.contains('skip-global-validation')) {
            return;
        }

        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                // Skip hidden or disabled fields
                if (field.offsetParent === null || field.disabled) {
                    return;
                }

                let isEmpty = false;
                const fieldType = field.type.toLowerCase();
                const tagName = field.tagName.toLowerCase();

                // Handle different input types
                if (fieldType === 'number') {
                    // For number inputs, check if value is empty string or null (but allow 0)
                    isEmpty = field.value === '' || field.value === null || field.value === undefined;
                } else if (fieldType === 'checkbox') {
                    // For checkboxes, check if checked
                    isEmpty = !field.checked;
                } else if (tagName === 'select') {
                    // For select elements, check if a value is selected
                    isEmpty = !field.value || field.value === '';
                } else {
                    // For text inputs, textareas, etc., use trim()
                    isEmpty = !field.value || field.value.trim() === '';
                }

                if (isEmpty) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                console.log('Form validation failed. Please fill in all required fields.');
            }
        });
    });

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});
