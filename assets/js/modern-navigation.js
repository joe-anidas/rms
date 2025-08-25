/*!
 * Modern Navigation JavaScript
 * Enhanced navigation functionality for RMS
 * Requirements: 7.2, 7.6 - Modern UI and responsive design
 */

(function($) {
    'use strict';

    // Navigation state management
    const NavigationManager = {
        init: function() {
            this.bindEvents();
            this.initMobileNavigation();
            this.initGlobalSearch();
            this.initBreadcrumbs();
            this.initActiveStates();
            this.initKeyboardNavigation();
        },

        bindEvents: function() {
            // Sidebar toggle
            $(document).on('click', '#sidebar-toggle', this.toggleSidebar);
            
            // Mobile menu close
            $(document).on('click', '#mobile-menu-close', this.closeMobileSidebar);
            
            // Mobile overlay click
            $(document).on('click', '#mobile-overlay', this.closeMobileSidebar);
            
            // Mobile search toggle
            $(document).on('click', '#mobile-search-toggle', this.toggleMobileSearch);
            
            // Dropdown hover effects (desktop only)
            if (window.innerWidth > 768) {
                $('.nav-item.dropdown').hover(
                    function() {
                        $(this).find('.dropdown-menu').addClass('show');
                    },
                    function() {
                        $(this).find('.dropdown-menu').removeClass('show');
                    }
                );
            }
            
            // Window resize handler
            $(window).on('resize', this.handleResize.bind(this));
            
            // Escape key handler
            $(document).on('keydown', this.handleEscapeKey.bind(this));
        },

        toggleSidebar: function(e) {
            e.preventDefault();
            
            const sidebar = $('#sidebar-wrapper');
            const overlay = $('#mobile-overlay');
            
            if (window.innerWidth <= 768) {
                // Mobile behavior
                sidebar.toggleClass('show');
                overlay.toggleClass('show');
                $('body').toggleClass('sidebar-open');
            } else {
                // Desktop behavior
                sidebar.toggleClass('collapsed');
                $('.content-wrapper').toggleClass('sidebar-collapsed');
            }
        },

        closeMobileSidebar: function(e) {
            e.preventDefault();
            
            const sidebar = $('#sidebar-wrapper');
            const overlay = $('#mobile-overlay');
            
            sidebar.removeClass('show');
            overlay.removeClass('show');
            $('body').removeClass('sidebar-open');
        },

        initMobileNavigation: function() {
            // Close mobile menu when clicking on nav items
            $('.modern-nav-item > a:not(.dropdown-toggle)').on('click', function() {
                if (window.innerWidth <= 768) {
                    NavigationManager.closeMobileSidebar({ preventDefault: function() {} });
                }
            });
            
            // Handle dropdown toggles on mobile
            $('.nav-item.dropdown > a').on('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const dropdown = $(this).next('.dropdown-menu');
                    dropdown.toggleClass('show');
                    
                    // Close other dropdowns
                    $('.dropdown-menu').not(dropdown).removeClass('show');
                }
            });
        },

        initGlobalSearch: function() {
            const searchForm = $('#global-search-form');
            const searchInput = $('#global-search-input');
            const mobileSearchForm = $('#mobile-search-form');
            const mobileSearchInput = $('#mobile-search-input');
            
            // Global search functionality
            searchForm.on('submit', function(e) {
                e.preventDefault();
                NavigationManager.performSearch(searchInput.val());
            });
            
            // Mobile search functionality
            mobileSearchForm.on('submit', function(e) {
                e.preventDefault();
                NavigationManager.performSearch(mobileSearchInput.val());
            });
            
            // Search suggestions (debounced)
            let searchTimeout;
            searchInput.on('input', function() {
                clearTimeout(searchTimeout);
                const query = $(this).val();
                
                if (query.length >= 2) {
                    searchTimeout = setTimeout(function() {
                        NavigationManager.showSearchSuggestions(query);
                    }, 300);
                }
            });
            
            // Mobile search suggestions
            mobileSearchInput.on('input', function() {
                clearTimeout(searchTimeout);
                const query = $(this).val();
                
                if (query.length >= 2) {
                    searchTimeout = setTimeout(function() {
                        NavigationManager.showMobileSearchSuggestions(query);
                    }, 300);
                }
            });
        },

        performSearch: function(query) {
            if (!query || query.trim().length < 2) {
                return;
            }
            
            // Show loading state
            this.showSearchLoading();
            
            // Perform AJAX search
            $.ajax({
                url: base_url + 'api/search/global',
                method: 'GET',
                data: { q: query.trim() },
                dataType: 'json',
                success: function(response) {
                    NavigationManager.handleSearchResults(response);
                },
                error: function() {
                    NavigationManager.showSearchError();
                },
                complete: function() {
                    NavigationManager.hideSearchLoading();
                }
            });
        },

        showSearchSuggestions: function(query) {
            // Implementation for desktop search suggestions
            // This would typically show a dropdown with suggestions
            console.log('Showing search suggestions for:', query);
        },

        showMobileSearchSuggestions: function(query) {
            // Implementation for mobile search suggestions
            const resultsContainer = $('#mobile-search-results');
            
            $.ajax({
                url: base_url + 'api/search/suggestions',
                method: 'GET',
                data: { q: query },
                dataType: 'json',
                success: function(response) {
                    NavigationManager.renderMobileSearchSuggestions(response, resultsContainer);
                }
            });
        },

        renderMobileSearchSuggestions: function(suggestions, container) {
            container.empty();
            
            if (suggestions && suggestions.length > 0) {
                const list = $('<div class="list-group"></div>');
                
                suggestions.forEach(function(item) {
                    const listItem = $(`
                        <a href="${item.url}" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center">
                                <i class="${item.icon} me-3"></i>
                                <div>
                                    <div class="fw-bold">${item.title}</div>
                                    <small class="text-muted">${item.description}</small>
                                </div>
                            </div>
                        </a>
                    `);
                    list.append(listItem);
                });
                
                container.append(list);
            }
        },

        toggleMobileSearch: function(e) {
            e.preventDefault();
            $('#mobileSearchModal').modal('show');
        },

        initBreadcrumbs: function() {
            // Auto-generate breadcrumbs based on current URL
            const path = window.location.pathname;
            const segments = path.split('/').filter(segment => segment !== '');
            
            if (segments.length > 1 && !$('.breadcrumb-modern').length) {
                this.generateBreadcrumbs(segments);
            }
        },

        generateBreadcrumbs: function(segments) {
            const breadcrumbMap = {
                'dashboard': { title: 'Dashboard', icon: 'zmdi-view-dashboard' },
                'properties': { title: 'Properties', icon: 'zmdi-home' },
                'customers': { title: 'Customers', icon: 'zmdi-account-box' },
                'staff': { title: 'Staff', icon: 'zmdi-accounts' },
                'registrations': { title: 'Registrations', icon: 'zmdi-assignment' },
                'transactions': { title: 'Transactions', icon: 'zmdi-money' },
                'reports': { title: 'Reports', icon: 'zmdi-chart' },
                'analytics': { title: 'Analytics', icon: 'zmdi-trending-up' },
                'create': { title: 'Create', icon: 'zmdi-plus' },
                'edit': { title: 'Edit', icon: 'zmdi-edit' },
                'view': { title: 'View', icon: 'zmdi-eye' }
            };
            
            // This would generate breadcrumbs dynamically
            // Implementation depends on your routing structure
        },

        initActiveStates: function() {
            // Set active states based on current URL
            const currentPath = window.location.pathname;
            
            $('.modern-nav-item').removeClass('active');
            $('.modern-nav-item > a').each(function() {
                const href = $(this).attr('href');
                if (href && currentPath.includes(href.replace(base_url, ''))) {
                    $(this).closest('.modern-nav-item').addClass('active');
                }
            });
            
            // Handle dropdown active states
            $('.dropdown-menu .modern-dropdown-item').each(function() {
                const href = $(this).attr('href');
                if (href && currentPath.includes(href.replace(base_url, ''))) {
                    $(this).closest('.nav-item.dropdown').addClass('active');
                }
            });
        },

        initKeyboardNavigation: function() {
            // Keyboard navigation for accessibility
            $('.modern-nav-item > a, .modern-dropdown-item').on('keydown', function(e) {
                const $current = $(this);
                let $next;
                
                switch(e.keyCode) {
                    case 38: // Up arrow
                        e.preventDefault();
                        $next = $current.closest('li').prev().find('a').first();
                        if ($next.length) $next.focus();
                        break;
                        
                    case 40: // Down arrow
                        e.preventDefault();
                        $next = $current.closest('li').next().find('a').first();
                        if ($next.length) $next.focus();
                        break;
                        
                    case 13: // Enter
                    case 32: // Space
                        if ($current.hasClass('dropdown-toggle')) {
                            e.preventDefault();
                            $current.click();
                        }
                        break;
                }
            });
        },

        handleResize: function() {
            // Handle window resize events
            if (window.innerWidth > 768) {
                // Desktop mode
                $('#sidebar-wrapper').removeClass('show');
                $('#mobile-overlay').removeClass('show');
                $('body').removeClass('sidebar-open');
            }
        },

        handleEscapeKey: function(e) {
            if (e.keyCode === 27) { // Escape key
                // Close mobile sidebar
                if ($('#sidebar-wrapper').hasClass('show')) {
                    this.closeMobileSidebar({ preventDefault: function() {} });
                }
                
                // Close mobile search modal
                $('#mobileSearchModal').modal('hide');
            }
        },

        showSearchLoading: function() {
            // Show loading indicator
            $('.search-bar').addClass('nav-loading');
        },

        hideSearchLoading: function() {
            // Hide loading indicator
            $('.search-bar').removeClass('nav-loading');
        },

        handleSearchResults: function(results) {
            // Handle search results
            if (results && results.redirect) {
                window.location.href = results.redirect;
            } else {
                // Show results in a modal or redirect to search page
                console.log('Search results:', results);
            }
        },

        showSearchError: function() {
            // Show search error message
            console.error('Search failed');
        }
    };

    // Notification Manager
    const NotificationManager = {
        init: function() {
            this.loadNotifications();
            this.bindEvents();
        },

        bindEvents: function() {
            // Mark notifications as read when dropdown is opened
            $('#notificationsDropdown').on('shown.bs.dropdown', function() {
                NotificationManager.markAsRead();
            });
        },

        loadNotifications: function() {
            // Load notifications via AJAX
            $.ajax({
                url: base_url + 'api/notifications',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    NotificationManager.updateNotificationCount(response.unread_count);
                    NotificationManager.renderNotifications(response.notifications);
                }
            });
        },

        updateNotificationCount: function(count) {
            const badge = $('.notification-count');
            if (count > 0) {
                badge.text(count).show();
            } else {
                badge.hide();
            }
        },

        renderNotifications: function(notifications) {
            // Render notifications in dropdown
            // Implementation would depend on your notification structure
        },

        markAsRead: function() {
            // Mark notifications as read
            $.ajax({
                url: base_url + 'api/notifications/mark-read',
                method: 'POST',
                success: function() {
                    $('.notification-count').hide();
                }
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        NavigationManager.init();
        NotificationManager.init();
        
        // Initialize Bootstrap dropdowns
        if (typeof bootstrap !== 'undefined') {
            // Bootstrap 5
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        }
    });

    // Export for global access
    window.NavigationManager = NavigationManager;
    window.NotificationManager = NotificationManager;

})(jQuery);