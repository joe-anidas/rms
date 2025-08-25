/*!
 * Modern RMS Theme JavaScript
 * Handles theme switching, responsive behavior, and modern interactions
 */

class ModernTheme {
    constructor() {
        this.init();
    }

    init() {
        this.setupThemeToggle();
        this.setupSidebar();
        this.setupResponsiveHandling();
        this.setupFormEnhancements();
        this.setupNotifications();
        this.setupTooltips();
        this.setupAnimations();
        this.loadThemePreference();
    }

    // Theme Management
    setupThemeToggle() {
        // Create theme toggle button if it doesn't exist
        if (!document.querySelector('.theme-toggle')) {
            this.createThemeToggle();
        }

        // Add event listener for theme toggle
        const themeToggle = document.querySelector('.theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }
    }

    createThemeToggle() {
        const themeToggle = document.createElement('button');
        themeToggle.className = 'theme-toggle';
        themeToggle.innerHTML = `
            <svg class="theme-toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        `;
        themeToggle.setAttribute('aria-label', 'Toggle dark mode');
        document.body.appendChild(themeToggle);
    }

    toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme-preference', newTheme);
        
        // Update theme toggle icon
        this.updateThemeToggleIcon(newTheme);
        
        // Dispatch theme change event
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: newTheme } }));
    }

    updateThemeToggleIcon(theme) {
        const themeToggle = document.querySelector('.theme-toggle-icon');
        if (themeToggle) {
            if (theme === 'dark') {
                themeToggle.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                `;
            } else {
                themeToggle.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                `;
            }
        }
    }

    loadThemePreference() {
        const savedTheme = localStorage.getItem('theme-preference');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', theme);
        this.updateThemeToggleIcon(theme);
    }

    // Sidebar Management
    setupSidebar() {
        const menuToggle = document.querySelector('.topbar-menu-toggle');
        const sidebar = document.querySelector('.modern-sidebar');
        const mainContent = document.querySelector('.main-content');
        const topbar = document.querySelector('.modern-topbar');

        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                
                // Update ARIA attributes
                const isOpen = sidebar.classList.contains('open');
                menuToggle.setAttribute('aria-expanded', isOpen);
                sidebar.setAttribute('aria-hidden', !isOpen);
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', (e) => {
                if (window.innerWidth < 992 && 
                    !sidebar.contains(e.target) && 
                    !menuToggle.contains(e.target) &&
                    sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                    menuToggle.setAttribute('aria-expanded', 'false');
                    sidebar.setAttribute('aria-hidden', 'true');
                }
            });
        }

        // Handle responsive sidebar behavior
        this.handleSidebarResponsive();
    }

    handleSidebarResponsive() {
        const sidebar = document.querySelector('.modern-sidebar');
        const mainContent = document.querySelector('.main-content');
        const topbar = document.querySelector('.modern-topbar');

        const updateLayout = () => {
            if (window.innerWidth >= 992) {
                // Desktop: sidebar always visible
                if (sidebar) sidebar.classList.remove('open');
                if (mainContent) mainContent.classList.add('main-content-with-sidebar');
                if (topbar) topbar.classList.add('topbar-with-sidebar');
            } else {
                // Mobile: sidebar hidden by default
                if (mainContent) mainContent.classList.remove('main-content-with-sidebar');
                if (topbar) topbar.classList.remove('topbar-with-sidebar');
            }
        };

        window.addEventListener('resize', updateLayout);
        updateLayout(); // Initial call
    }

    // Responsive Handling
    setupResponsiveHandling() {
        // Handle responsive grid adjustments
        this.handleResponsiveGrids();
        
        // Handle responsive tables
        this.handleResponsiveTables();
        
        // Handle responsive forms
        this.handleResponsiveForms();
    }

    handleResponsiveGrids() {
        const dashboardGrids = document.querySelectorAll('.dashboard-grid');
        
        const updateGrids = () => {
            dashboardGrids.forEach(grid => {
                const width = window.innerWidth;
                
                // Remove existing responsive classes
                grid.classList.remove('dashboard-grid-1', 'dashboard-grid-2', 'dashboard-grid-3', 'dashboard-grid-4');
                
                // Apply responsive classes based on screen size
                if (width < 576) {
                    grid.classList.add('dashboard-grid-1');
                } else if (width < 768) {
                    grid.classList.add('dashboard-grid-2');
                } else if (width < 992) {
                    grid.classList.add('dashboard-grid-3');
                } else {
                    grid.classList.add('dashboard-grid-4');
                }
            });
        };

        window.addEventListener('resize', updateGrids);
        updateGrids(); // Initial call
    }

    handleResponsiveTables() {
        const tables = document.querySelectorAll('.modern-table');
        
        tables.forEach(table => {
            if (window.innerWidth < 768) {
                table.style.overflowX = 'auto';
            } else {
                table.style.overflowX = 'visible';
            }
        });
    }

    handleResponsiveForms() {
        const formRows = document.querySelectorAll('.form-row-2, .form-row-3');
        
        const updateFormRows = () => {
            formRows.forEach(row => {
                if (window.innerWidth < 576) {
                    row.style.gridTemplateColumns = '1fr';
                } else if (window.innerWidth < 768 && row.classList.contains('form-row-3')) {
                    row.style.gridTemplateColumns = 'repeat(2, 1fr)';
                } else {
                    row.style.gridTemplateColumns = '';
                }
            });
        };

        window.addEventListener('resize', updateFormRows);
        updateFormRows(); // Initial call
    }

    // Form Enhancements
    setupFormEnhancements() {
        // Add floating labels
        this.setupFloatingLabels();
        
        // Add form validation styling
        this.setupFormValidation();
        
        // Add loading states to buttons
        this.setupButtonLoadingStates();
    }

    setupFloatingLabels() {
        const formControls = document.querySelectorAll('.modern-form-control');
        
        formControls.forEach(control => {
            const handleFocus = () => {
                control.parentElement.classList.add('focused');
            };
            
            const handleBlur = () => {
                if (!control.value) {
                    control.parentElement.classList.remove('focused');
                }
            };
            
            control.addEventListener('focus', handleFocus);
            control.addEventListener('blur', handleBlur);
            
            // Initial state
            if (control.value) {
                control.parentElement.classList.add('focused');
            }
        });
    }

    setupFormValidation() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('.modern-form-control');
            
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    this.validateInput(input);
                });
                
                input.addEventListener('input', () => {
                    if (input.classList.contains('is-invalid')) {
                        this.validateInput(input);
                    }
                });
            });
        });
    }

    validateInput(input) {
        const isValid = input.checkValidity();
        
        input.classList.remove('is-valid', 'is-invalid');
        input.classList.add(isValid ? 'is-valid' : 'is-invalid');
        
        // Show/hide error message
        let errorMsg = input.parentElement.querySelector('.error-message');
        
        if (!isValid) {
            if (!errorMsg) {
                errorMsg = document.createElement('div');
                errorMsg.className = 'error-message text-danger text-sm mt-1';
                input.parentElement.appendChild(errorMsg);
            }
            errorMsg.textContent = input.validationMessage;
        } else if (errorMsg) {
            errorMsg.remove();
        }
    }

    setupButtonLoadingStates() {
        const buttons = document.querySelectorAll('.modern-btn[type="submit"]');
        
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                const form = button.closest('form');
                if (form && form.checkValidity()) {
                    this.setButtonLoading(button, true);
                    
                    // Reset loading state after form submission
                    setTimeout(() => {
                        this.setButtonLoading(button, false);
                    }, 2000);
                }
            });
        });
    }

    setButtonLoading(button, loading) {
        if (loading) {
            button.disabled = true;
            button.innerHTML = `
                <span class="loading-spinner"></span>
                <span>Loading...</span>
            `;
        } else {
            button.disabled = false;
            button.innerHTML = button.getAttribute('data-original-text') || 'Submit';
        }
    }

    // Notification System
    setupNotifications() {
        // Create notification container if it doesn't exist
        if (!document.querySelector('.notification-container')) {
            const container = document.createElement('div');
            container.className = 'notification-container';
            document.body.appendChild(container);
        }
    }

    showNotification(type, title, message, duration = 5000) {
        const container = document.querySelector('.notification-container');
        if (!container) return;

        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        const iconMap = {
            success: 'M5 13l4 4L19 7',
            error: 'M6 18L18 6M6 6l12 12',
            warning: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z',
            info: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
        };

        notification.innerHTML = `
            <svg class="notification-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconMap[type]}"/>
            </svg>
            <div class="notification-content">
                <div class="notification-title">${title}</div>
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close" aria-label="Close notification">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

        // Add close functionality
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            this.removeNotification(notification);
        });

        // Auto-remove after duration
        setTimeout(() => {
            this.removeNotification(notification);
        }, duration);

        container.appendChild(notification);
    }

    removeNotification(notification) {
        notification.style.animation = 'slideOutRight 0.3s ease-in forwards';
        setTimeout(() => {
            if (notification.parentElement) {
                notification.parentElement.removeChild(notification);
            }
        }, 300);
    }

    // Tooltip System
    setupTooltips() {
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        
        tooltipElements.forEach(element => {
            const tooltipText = element.getAttribute('data-tooltip');
            
            if (!element.querySelector('.tooltip-content')) {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip-content';
                tooltip.textContent = tooltipText;
                element.appendChild(tooltip);
                element.classList.add('tooltip');
            }
        });
    }

    // Animation System
    setupAnimations() {
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, observerOptions);

        // Observe elements that should animate on scroll
        const animateElements = document.querySelectorAll('.modern-card, .property-card, .customer-card, .staff-card');
        animateElements.forEach(el => observer.observe(el));
    }

    // Utility Methods
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Public API Methods
    static showSuccess(title, message) {
        window.modernTheme.showNotification('success', title, message);
    }

    static showError(title, message) {
        window.modernTheme.showNotification('error', title, message);
    }

    static showWarning(title, message) {
        window.modernTheme.showNotification('warning', title, message);
    }

    static showInfo(title, message) {
        window.modernTheme.showNotification('info', title, message);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.modernTheme = new ModernTheme();
});

// Add CSS animations for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOutRight {
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
`;
document.head.appendChild(style);