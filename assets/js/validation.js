/**
 * Client-side Validation Library
 * Provides real-time form validation with user feedback
 * Requirements: 7.1, 7.4, 7.7
 */

class FormValidator {
    constructor(formSelector, options = {}) {
        this.form = document.querySelector(formSelector);
        this.options = {
            validateOnInput: true,
            validateOnBlur: true,
            showSuccessMessages: false,
            errorClass: 'is-invalid',
            successClass: 'is-valid',
            errorMessageClass: 'invalid-feedback',
            successMessageClass: 'valid-feedback',
            ...options
        };
        
        this.rules = {};
        this.errors = {};
        this.isValid = false;
        
        if (this.form) {
            this.init();
        }
    }
    
    init() {
        // Add event listeners
        if (this.options.validateOnInput) {
            this.form.addEventListener('input', (e) => this.handleInput(e));
        }
        
        if (this.options.validateOnBlur) {
            this.form.addEventListener('blur', (e) => this.handleBlur(e), true);
        }
        
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }
    
    /**
     * Set validation rules for form fields
     * @param {Object} rules Validation rules object
     */
    setRules(rules) {
        this.rules = rules;
        return this;
    }
    
    /**
     * Handle input events for real-time validation
     * @param {Event} e Input event
     */
    handleInput(e) {
        const field = e.target;
        if (this.rules[field.name]) {
            this.validateField(field.name, field.value);
        }
    }
    
    /**
     * Handle blur events for field validation
     * @param {Event} e Blur event
     */
    handleBlur(e) {
        const field = e.target;
        if (this.rules[field.name]) {
            this.validateField(field.name, field.value);
        }
    }
    
    /**
     * Handle form submission
     * @param {Event} e Submit event
     */
    handleSubmit(e) {
        e.preventDefault();
        
        if (this.validateForm()) {
            // Form is valid, allow submission
            this.onSuccess();
        } else {
            // Form has errors, prevent submission
            this.onError();
        }
    }
    
    /**
     * Validate entire form
     * @returns {boolean} Form validity
     */
    validateForm() {
        this.errors = {};
        let isValid = true;
        
        // Validate all fields with rules
        for (const fieldName in this.rules) {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                const fieldValid = this.validateField(fieldName, field.value);
                if (!fieldValid) {
                    isValid = false;
                }
            }
        }
        
        this.isValid = isValid;
        return isValid;
    }
    
    /**
     * Validate individual field
     * @param {string} fieldName Field name
     * @param {string} value Field value
     * @returns {boolean} Field validity
     */
    validateField(fieldName, value) {
        const rules = this.rules[fieldName];
        const field = this.form.querySelector(`[name="${fieldName}"]`);
        
        if (!rules || !field) {
            return true;
        }
        
        const errors = [];
        
        // Required validation
        if (rules.required && this.isEmpty(value)) {
            errors.push(rules.messages?.required || `${this.getFieldLabel(fieldName)} is required`);
        }
        
        // Skip other validations if field is empty and not required
        if (!rules.required && this.isEmpty(value)) {
            this.clearFieldError(field);
            return true;
        }
        
        // Length validations
        if (rules.minLength && value.length < rules.minLength) {
            errors.push(rules.messages?.minLength || `${this.getFieldLabel(fieldName)} must be at least ${rules.minLength} characters`);
        }
        
        if (rules.maxLength && value.length > rules.maxLength) {
            errors.push(rules.messages?.maxLength || `${this.getFieldLabel(fieldName)} cannot exceed ${rules.maxLength} characters`);
        }
        
        // Pattern validation
        if (rules.pattern && !rules.pattern.test(value)) {
            errors.push(rules.messages?.pattern || `${this.getFieldLabel(fieldName)} format is invalid`);
        }
        
        // Email validation
        if (rules.email && !this.isValidEmail(value)) {
            errors.push(rules.messages?.email || 'Please enter a valid email address');
        }
        
        // Phone validation
        if (rules.phone && !this.isValidPhone(value)) {
            errors.push(rules.messages?.phone || 'Please enter a valid phone number');
        }
        
        // Numeric validation
        if (rules.numeric && !this.isNumeric(value)) {
            errors.push(rules.messages?.numeric || `${this.getFieldLabel(fieldName)} must be a number`);
        }
        
        // Min/Max value validation
        if (rules.min !== undefined && parseFloat(value) < rules.min) {
            errors.push(rules.messages?.min || `${this.getFieldLabel(fieldName)} must be at least ${rules.min}`);
        }
        
        if (rules.max !== undefined && parseFloat(value) > rules.max) {
            errors.push(rules.messages?.max || `${this.getFieldLabel(fieldName)} cannot exceed ${rules.max}`);
        }
        
        // Custom validation function
        if (rules.custom && typeof rules.custom === 'function') {
            const customResult = rules.custom(value, field);
            if (customResult !== true) {
                errors.push(customResult || 'Invalid value');
            }
        }
        
        // Update field display
        if (errors.length > 0) {
            this.errors[fieldName] = errors;
            this.showFieldError(field, errors[0]);
            return false;
        } else {
            delete this.errors[fieldName];
            this.showFieldSuccess(field);
            return true;
        }
    }
    
    /**
     * Show error message for field
     * @param {HTMLElement} field Form field element
     * @param {string} message Error message
     */
    showFieldError(field, message) {
        // Remove success classes
        field.classList.remove(this.options.successClass);
        
        // Add error class
        field.classList.add(this.options.errorClass);
        
        // Find or create error message element
        let errorElement = field.parentNode.querySelector(`.${this.options.errorMessageClass}`);
        
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = this.options.errorMessageClass;
            field.parentNode.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        
        // Hide success message if exists
        const successElement = field.parentNode.querySelector(`.${this.options.successMessageClass}`);
        if (successElement) {
            successElement.style.display = 'none';
        }
    }
    
    /**
     * Show success message for field
     * @param {HTMLElement} field Form field element
     */
    showFieldSuccess(field) {
        // Remove error classes
        field.classList.remove(this.options.errorClass);
        
        // Add success class if enabled
        if (this.options.showSuccessMessages) {
            field.classList.add(this.options.successClass);
        }
        
        // Hide error message
        const errorElement = field.parentNode.querySelector(`.${this.options.errorMessageClass}`);
        if (errorElement) {
            errorElement.style.display = 'none';
        }
        
        // Show success message if enabled
        if (this.options.showSuccessMessages) {
            let successElement = field.parentNode.querySelector(`.${this.options.successMessageClass}`);
            
            if (!successElement) {
                successElement = document.createElement('div');
                successElement.className = this.options.successMessageClass;
                successElement.textContent = 'Valid';
                field.parentNode.appendChild(successElement);
            }
            
            successElement.style.display = 'block';
        }
    }
    
    /**
     * Clear field error display
     * @param {HTMLElement} field Form field element
     */
    clearFieldError(field) {
        field.classList.remove(this.options.errorClass, this.options.successClass);
        
        const errorElement = field.parentNode.querySelector(`.${this.options.errorMessageClass}`);
        if (errorElement) {
            errorElement.style.display = 'none';
        }
        
        const successElement = field.parentNode.querySelector(`.${this.options.successMessageClass}`);
        if (successElement) {
            successElement.style.display = 'none';
        }
    }
    
    /**
     * Get field label for error messages
     * @param {string} fieldName Field name
     * @returns {string} Field label
     */
    getFieldLabel(fieldName) {
        const field = this.form.querySelector(`[name="${fieldName}"]`);
        const label = this.form.querySelector(`label[for="${fieldName}"]`);
        
        if (label) {
            return label.textContent.replace('*', '').trim();
        }
        
        if (field && field.getAttribute('data-label')) {
            return field.getAttribute('data-label');
        }
        
        // Convert field name to readable label
        return fieldName.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }
    
    /**
     * Check if value is empty
     * @param {string} value Value to check
     * @returns {boolean} Is empty
     */
    isEmpty(value) {
        return value === null || value === undefined || value.toString().trim() === '';
    }
    
    /**
     * Validate email format
     * @param {string} email Email address
     * @returns {boolean} Is valid email
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    /**
     * Validate phone format
     * @param {string} phone Phone number
     * @returns {boolean} Is valid phone
     */
    isValidPhone(phone) {
        const phoneRegex = /^[+]?[\d\s\-\(\)]{10,15}$/;
        return phoneRegex.test(phone);
    }
    
    /**
     * Check if value is numeric
     * @param {string} value Value to check
     * @returns {boolean} Is numeric
     */
    isNumeric(value) {
        return !isNaN(parseFloat(value)) && isFinite(value);
    }
    
    /**
     * Get all form errors
     * @returns {Object} Errors object
     */
    getErrors() {
        return this.errors;
    }
    
    /**
     * Check if form is valid
     * @returns {boolean} Form validity
     */
    isFormValid() {
        return this.isValid;
    }
    
    /**
     * Clear all form errors
     */
    clearErrors() {
        this.errors = {};
        
        // Clear visual error indicators
        const fields = this.form.querySelectorAll('input, select, textarea');
        fields.forEach(field => this.clearFieldError(field));
    }
    
    /**
     * Set custom error for field
     * @param {string} fieldName Field name
     * @param {string} message Error message
     */
    setFieldError(fieldName, message) {
        const field = this.form.querySelector(`[name="${fieldName}"]`);
        if (field) {
            this.errors[fieldName] = [message];
            this.showFieldError(field, message);
        }
    }
    
    /**
     * Success callback - override in implementation
     */
    onSuccess() {
        console.log('Form validation successful');
    }
    
    /**
     * Error callback - override in implementation
     */
    onError() {
        console.log('Form validation failed', this.errors);
        
        // Focus on first error field
        const firstErrorField = this.form.querySelector(`.${this.options.errorClass}`);
        if (firstErrorField) {
            firstErrorField.focus();
        }
    }
}

/**
 * Property Form Validator
 */
class PropertyFormValidator extends FormValidator {
    constructor(formSelector) {
        super(formSelector);
        
        this.setRules({
            garden_name: {
                required: true,
                minLength: 3,
                maxLength: 255,
                messages: {
                    required: 'Property name is required',
                    minLength: 'Property name must be at least 3 characters',
                    maxLength: 'Property name cannot exceed 255 characters'
                }
            },
            property_type: {
                required: true,
                messages: {
                    required: 'Property type is required'
                }
            },
            size_sqft: {
                numeric: true,
                min: 0,
                messages: {
                    numeric: 'Size must be a valid number',
                    min: 'Size must be greater than 0'
                }
            },
            price: {
                numeric: true,
                min: 0,
                messages: {
                    numeric: 'Price must be a valid number',
                    min: 'Price must be greater than 0'
                }
            }
        });
    }
}

/**
 * Customer Form Validator
 */
class CustomerFormValidator extends FormValidator {
    constructor(formSelector) {
        super(formSelector);
        
        this.setRules({
            plot_buyer_name: {
                required: true,
                minLength: 2,
                maxLength: 255,
                messages: {
                    required: 'Customer name is required',
                    minLength: 'Customer name must be at least 2 characters',
                    maxLength: 'Customer name cannot exceed 255 characters'
                }
            },
            phone_number_1: {
                required: true,
                phone: true,
                minLength: 10,
                maxLength: 15,
                messages: {
                    required: 'Primary phone number is required',
                    phone: 'Please enter a valid phone number',
                    minLength: 'Phone number must be at least 10 digits',
                    maxLength: 'Phone number cannot exceed 15 characters'
                }
            },
            email_address: {
                email: true,
                maxLength: 255,
                messages: {
                    email: 'Please enter a valid email address',
                    maxLength: 'Email address cannot exceed 255 characters'
                }
            },
            aadhar_number: {
                pattern: /^\d{12}$/,
                messages: {
                    pattern: 'Aadhar number must be exactly 12 digits'
                }
            },
            pan_number: {
                pattern: /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/,
                messages: {
                    pattern: 'Invalid PAN number format (e.g., ABCDE1234F)'
                }
            },
            annual_income: {
                numeric: true,
                min: 0,
                messages: {
                    numeric: 'Annual income must be a valid number',
                    min: 'Annual income cannot be negative'
                }
            }
        });
    }
}

/**
 * Transaction Form Validator
 */
class TransactionFormValidator extends FormValidator {
    constructor(formSelector) {
        super(formSelector);
        
        this.setRules({
            registration_id: {
                required: true,
                messages: {
                    required: 'Registration is required'
                }
            },
            amount: {
                required: true,
                numeric: true,
                min: 0.01,
                messages: {
                    required: 'Amount is required',
                    numeric: 'Amount must be a valid number',
                    min: 'Amount must be greater than 0'
                }
            },
            payment_type: {
                required: true,
                messages: {
                    required: 'Payment type is required'
                }
            },
            payment_method: {
                required: true,
                messages: {
                    required: 'Payment method is required'
                }
            },
            payment_date: {
                required: true,
                custom: (value) => {
                    const date = new Date(value);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    if (isNaN(date.getTime())) {
                        return 'Please enter a valid date';
                    }
                    
                    if (date > today) {
                        return 'Payment date cannot be in the future';
                    }
                    
                    return true;
                },
                messages: {
                    required: 'Payment date is required'
                }
            }
        });
    }
}

/**
 * File Upload Validator
 */
class FileUploadValidator {
    constructor(options = {}) {
        this.options = {
            allowedTypes: ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
            maxSize: 5 * 1024 * 1024, // 5MB
            maxFiles: 1,
            ...options
        };
    }
    
    /**
     * Validate file upload
     * @param {FileList} files Files to validate
     * @returns {Object} Validation result
     */
    validate(files) {
        const errors = [];
        
        if (!files || files.length === 0) {
            return { isValid: false, errors: ['No file selected'] };
        }
        
        if (files.length > this.options.maxFiles) {
            errors.push(`Maximum ${this.options.maxFiles} file(s) allowed`);
        }
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            
            // Check file size
            if (file.size > this.options.maxSize) {
                errors.push(`File "${file.name}" is too large. Maximum size: ${this.formatFileSize(this.options.maxSize)}`);
            }
            
            // Check file type
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!this.options.allowedTypes.includes(fileExtension)) {
                errors.push(`File type "${fileExtension}" is not allowed. Allowed types: ${this.options.allowedTypes.join(', ')}`);
            }
        }
        
        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }
    
    /**
     * Format file size for display
     * @param {number} bytes File size in bytes
     * @returns {string} Formatted size
     */
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        FormValidator,
        PropertyFormValidator,
        CustomerFormValidator,
        TransactionFormValidator,
        FileUploadValidator
    };
}