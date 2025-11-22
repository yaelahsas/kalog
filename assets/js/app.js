/**
 * Kalog Admin Panel - Main Application JavaScript
 * Version: 2.0.0
 * Author: Kalog Development Team
 */

(function() {
    'use strict';

    // Application namespace
    window.Kalog = {
        config: window.KalogConfig || {},
        modules: {},
        utils: {},
        components: {},
        events: {}
    };

    // Utility Functions
    Kalog.utils = {
        /**
         * Debounce function to limit function calls
         */
        debounce: function(func, wait, immediate) {
            let timeout;
            return function executedFunction() {
                const context = this;
                const args = arguments;
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        },

        /**
         * Throttle function to limit function calls
         */
        throttle: function(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },

        /**
         * Format number with thousands separator
         */
        formatNumber: function(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },

        /**
         * Format currency
         */
        formatCurrency: function(amount, currency = 'IDR') {
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: currency,
                minimumFractionDigits: 0
            });
            return formatter.format(amount);
        },

        /**
         * Format date
         */
        formatDate: function(date, format = 'DD MMM YYYY') {
            const moment = window.moment || window.dayjs;
            if (moment) {
                return moment(date).format(format);
            }
            return new Date(date).toLocaleDateString('id-ID');
        },

        /**
         * Generate random ID
         */
        generateId: function(prefix = 'kalog') {
            return prefix + '_' + Math.random().toString(36).substr(2, 9);
        },

        /**
         * Check if element is in viewport
         */
        isInViewport: function(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        },

        /**
         * Scroll to element with animation
         */
        scrollToElement: function(element, offset = 0) {
            const elementTop = element.offsetTop - offset;
            window.scrollTo({
                top: elementTop,
                behavior: 'smooth'
            });
        },

        /**
         * Copy text to clipboard
         */
        copyToClipboard: function(text) {
            if (navigator.clipboard) {
                return navigator.clipboard.writeText(text);
            } else {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                return Promise.resolve();
            }
        },

        /**
         * Get URL parameters
         */
        getUrlParams: function() {
            const params = {};
            const queryString = window.location.search.substring(1);
            const pairs = queryString.split('&');
            
            for (let i = 0; i < pairs.length; i++) {
                const pair = pairs[i].split('=');
                params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
            }
            
            return params;
        },

        /**
         * Set URL parameter
         */
        setUrlParam: function(param, value) {
            const url = new URL(window.location);
            url.searchParams.set(param, value);
            window.history.replaceState({}, '', url);
        },

        /**
         * Remove URL parameter
         */
        removeUrlParam: function(param) {
            const url = new URL(window.location);
            url.searchParams.delete(param);
            window.history.replaceState({}, '', url);
        },

        /**
         * Validate email
         */
        isValidEmail: function(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },

        /**
         * Validate phone number (Indonesian format)
         */
        isValidPhone: function(phone) {
            const re = /^(\+62|62|0)8[1-9][0-9]{6,9}$/;
            return re.test(phone.replace(/[\s-]/g, ''));
        },

        /**
         * Get file size in human readable format
         */
        formatFileSize: function(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        /**
         * Calculate percentage
         */
        calculatePercentage: function(value, total) {
            return total > 0 ? Math.round((value / total) * 100) : 0;
        },

        /**
         * Local storage wrapper
         */
        storage: {
            set: function(key, value) {
                try {
                    localStorage.setItem(key, JSON.stringify(value));
                    return true;
                } catch (e) {
                    console.error('LocalStorage set error:', e);
                    return false;
                }
            },
            
            get: function(key, defaultValue = null) {
                try {
                    const item = localStorage.getItem(key);
                    return item ? JSON.parse(item) : defaultValue;
                } catch (e) {
                    console.error('LocalStorage get error:', e);
                    return defaultValue;
                }
            },
            
            remove: function(key) {
                try {
                    localStorage.removeItem(key);
                    return true;
                } catch (e) {
                    console.error('LocalStorage remove error:', e);
                    return false;
                }
            },
            
            clear: function() {
                try {
                    localStorage.clear();
                    return true;
                } catch (e) {
                    console.error('LocalStorage clear error:', e);
                    return false;
                }
            }
        }
    };

    // Event System
    Kalog.events = {
        listeners: {},

        on: function(event, callback) {
            if (!this.listeners[event]) {
                this.listeners[event] = [];
            }
            this.listeners[event].push(callback);
        },

        off: function(event, callback) {
            if (this.listeners[event]) {
                this.listeners[event] = this.listeners[event].filter(cb => cb !== callback);
            }
        },

        emit: function(event, data) {
            if (this.listeners[event]) {
                this.listeners[event].forEach(callback => callback(data));
            }
        }
    };

    // Animation Module
    Kalog.modules.animations = {
        /**
         * Fade in element
         */
        fadeIn: function(element, duration = 300) {
            element.style.opacity = 0;
            element.style.display = 'block';
            
            const start = performance.now();
            const animate = (currentTime) => {
                const elapsed = currentTime - start;
                const progress = Math.min(elapsed / duration, 1);
                element.style.opacity = progress;
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };
            
            requestAnimationFrame(animate);
        },

        /**
         * Fade out element
         */
        fadeOut: function(element, duration = 300) {
            const start = performance.now();
            const initialOpacity = parseFloat(window.getComputedStyle(element).opacity);
            
            const animate = (currentTime) => {
                const elapsed = currentTime - start;
                const progress = Math.min(elapsed / duration, 1);
                element.style.opacity = initialOpacity * (1 - progress);
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    element.style.display = 'none';
                }
            };
            
            requestAnimationFrame(animate);
        },

        /**
         * Slide down element
         */
        slideDown: function(element, duration = 300) {
            element.style.height = '0';
            element.style.overflow = 'hidden';
            element.style.display = 'block';
            
            const targetHeight = element.scrollHeight;
            const start = performance.now();
            
            const animate = (currentTime) => {
                const elapsed = currentTime - start;
                const progress = Math.min(elapsed / duration, 1);
                element.style.height = (targetHeight * progress) + 'px';
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    element.style.height = 'auto';
                    element.style.overflow = 'visible';
                }
            };
            
            requestAnimationFrame(animate);
        },

        /**
         * Slide up element
         */
        slideUp: function(element, duration = 300) {
            const startHeight = element.scrollHeight;
            const start = performance.now();
            
            element.style.height = startHeight + 'px';
            element.style.overflow = 'hidden';
            
            const animate = (currentTime) => {
                const elapsed = currentTime - start;
                const progress = Math.min(elapsed / duration, 1);
                element.style.height = (startHeight * (1 - progress)) + 'px';
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    element.style.display = 'none';
                    element.style.height = 'auto';
                    element.style.overflow = 'visible';
                }
            };
            
            requestAnimationFrame(animate);
        },

        /**
         * Animate number counting
         */
        countUp: function(element, target, duration = 1000, start = 0) {
            const startTime = performance.now();
            
            const animate = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                const current = Math.floor(start + (target - start) * Kalog.modules.animations.easeOutQuart(progress));
                element.textContent = Kalog.utils.formatNumber(current);
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    element.textContent = Kalog.utils.formatNumber(target);
                }
            };
            
            requestAnimationFrame(animate);
        },

        /**
         * Easing functions
         */
        easeOutQuart: function(t) {
            return 1 - Math.pow(1 - t, 4);
        },

        easeInOutCubic: function(t) {
            return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
        }
    };

    // Form Module
    Kalog.modules.forms = {
        /**
         * Initialize form validation
         */
        initValidation: function(formElement, rules) {
            const form = formElement instanceof HTMLFormElement ? formElement : document.getElementById(formElement);
            if (!form) return;

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                if (this.validateForm(form, rules)) {
                    this.submitForm(form);
                }
            });

            // Real-time validation
            form.querySelectorAll('input, textarea, select').forEach(field => {
                field.addEventListener('blur', () => {
                    this.validateField(field, rules[field.name]);
                });
                
                field.addEventListener('input', () => {
                    this.clearFieldError(field);
                });
            });
        },

        /**
         * Validate entire form
         */
        validateForm: function(form, rules) {
            let isValid = true;
            
            Object.keys(rules).forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (field && !this.validateField(field, rules[fieldName])) {
                    isValid = false;
                }
            });
            
            return isValid;
        },

        /**
         * Validate single field
         */
        validateField: function(field, rules) {
            if (!rules) return true;
            
            let isValid = true;
            const value = field.value.trim();
            
            // Required validation
            if (rules.required && !value) {
                this.showFieldError(field, 'This field is required');
                return false;
            }
            
            // Email validation
            if (rules.email && value && !Kalog.utils.isValidEmail(value)) {
                this.showFieldError(field, 'Please enter a valid email address');
                return false;
            }
            
            // Phone validation
            if (rules.phone && value && !Kalog.utils.isValidPhone(value)) {
                this.showFieldError(field, 'Please enter a valid phone number');
                return false;
            }
            
            // Min length validation
            if (rules.minLength && value.length < rules.minLength) {
                this.showFieldError(field, `Minimum ${rules.minLength} characters required`);
                return false;
            }
            
            // Max length validation
            if (rules.maxLength && value.length > rules.maxLength) {
                this.showFieldError(field, `Maximum ${rules.maxLength} characters allowed`);
                return false;
            }
            
            // Pattern validation
            if (rules.pattern && value && !rules.pattern.test(value)) {
                this.showFieldError(field, rules.message || 'Invalid format');
                return false;
            }
            
            // Custom validation
            if (rules.custom && typeof rules.custom === 'function') {
                const customResult = rules.custom(value);
                if (customResult !== true) {
                    this.showFieldError(field, customResult);
                    return false;
                }
            }
            
            this.clearFieldError(field);
            return true;
        },

        /**
         * Show field error
         */
        showFieldError: function(field, message) {
            this.clearFieldError(field);
            
            field.classList.add('is-invalid');
            
            const errorElement = document.createElement('div');
            errorElement.className = 'invalid-feedback';
            errorElement.textContent = message;
            
            field.parentNode.appendChild(errorElement);
        },

        /**
         * Clear field error
         */
        clearFieldError: function(field) {
            field.classList.remove('is-invalid');
            
            const errorElement = field.parentNode.querySelector('.invalid-feedback');
            if (errorElement) {
                errorElement.remove();
            }
        },

        /**
         * Submit form with AJAX
         */
        submitForm: function(form) {
            const formData = new FormData(form);
            const submitButton = form.querySelector('[type="submit"]');
            
            // Show loading state
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';
            }
            
            fetch(form.action || window.location.href, {
                method: form.method || 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': Kalog.config.csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Kalog.events.emit('form:success', { form, data });
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    Kalog.events.emit('form:error', { form, data });
                    if (data.message) {
                        showToast('Error', data.message, 'error');
                    }
                }
            })
            .catch(error => {
                Kalog.events.emit('form:exception', { form, error });
                showToast('Network Error', 'Please check your connection and try again', 'error');
            })
            .finally(() => {
                // Reset loading state
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = submitButton.getAttribute('data-original-text') || 'Submit';
                }
            });
        }
    };

    // Data Table Module
    Kalog.modules.dataTable = {
        /**
         * Initialize enhanced data table
         */
        init: function(tableId, options = {}) {
            const table = document.getElementById(tableId);
            if (!table) return;

            const defaultOptions = {
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                responsive: true,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                      '<"row"<"col-sm-12"tr>>' +
                      '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            };

            const mergedOptions = Object.assign(defaultOptions, options);
            
            if (window.jQuery && window.jQuery.fn.DataTable) {
                return window.jQuery(table).DataTable(mergedOptions);
            }
            
            return null;
        },

        /**
         * Add row to table
         */
        addRow: function(dataTable, data) {
            if (dataTable && dataTable.row) {
                return dataTable.row.add(data).draw();
            }
            return null;
        },

        /**
         * Update row in table
         */
        updateRow: function(dataTable, row, data) {
            if (dataTable && row && data) {
                row.data(data).draw();
            }
        },

        /**
         * Remove row from table
         */
        removeRow: function(dataTable, row) {
            if (dataTable && row) {
                row.remove().draw();
            }
        }
    };

    // Chart Module
    Kalog.modules.charts = {
        /**
         * Create line chart
         */
        createLineChart: function(canvasId, data, options = {}) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return null;

            const defaultOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#ddd',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 2]
                        }
                    }
                }
            };

            return new Chart(ctx, {
                type: 'line',
                data: data,
                options: Object.assign(defaultOptions, options)
            });
        },

        /**
         * Create bar chart
         */
        createBarChart: function(canvasId, data, options = {}) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return null;

            const defaultOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 2]
                        }
                    }
                }
            };

            return new Chart(ctx, {
                type: 'bar',
                data: data,
                options: Object.assign(defaultOptions, options)
            });
        },

        /**
         * Create doughnut chart
         */
        createDoughnutChart: function(canvasId, data, options = {}) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return null;

            const defaultOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            };

            return new Chart(ctx, {
                type: 'doughnut',
                data: data,
                options: Object.assign(defaultOptions, options)
            });
        }
    };

    // Notification Module
    Kalog.modules.notifications = {
        container: null,

        init: function() {
            this.container = document.getElementById('notificationContainer');
            if (!this.container) {
                this.container = document.createElement('div');
                this.container.className = 'notification-container';
                document.body.appendChild(this.container);
            }
        },

        show: function(message, type = 'info', duration = 5000, actions = []) {
            if (!this.container) this.init();

            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            
            const content = document.createElement('div');
            content.className = 'notification-content';
            
            const messageElement = document.createElement('div');
            messageElement.className = 'notification-message';
            messageElement.textContent = message;
            
            content.appendChild(messageElement);
            
            if (actions.length > 0) {
                const actionsElement = document.createElement('div');
                actionsElement.className = 'notification-actions';
                
                actions.forEach(action => {
                    const button = document.createElement('button');
                    button.className = `btn btn-sm btn-${action.type || 'secondary'}`;
                    button.textContent = action.text;
                    button.onclick = action.handler;
                    actionsElement.appendChild(button);
                });
                
                content.appendChild(actionsElement);
            }
            
            const closeButton = document.createElement('button');
            closeButton.className = 'notification-close';
            closeButton.innerHTML = '&times;';
            closeButton.onclick = () => this.hide(notification);
            
            notification.appendChild(content);
            notification.appendChild(closeButton);
            
            this.container.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            // Auto hide
            if (duration > 0) {
                setTimeout(() => this.hide(notification), duration);
            }
            
            return notification;
        },

        hide: function(notification) {
            if (!notification) return;
            
            notification.classList.add('hide');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        },

        clear: function() {
            if (!this.container) return;
            
            const notifications = this.container.querySelectorAll('.notification');
            notifications.forEach(notification => this.hide(notification));
        }
    };

    // Search Module
    Kalog.modules.search = {
        /**
         * Initialize global search
         */
        initGlobalSearch: function(inputId, resultsContainerId, searchUrl) {
            const input = document.getElementById(inputId);
            const container = document.getElementById(resultsContainerId);
            
            if (!input || !container) return;

            let searchTimeout;
            
            input.addEventListener('input', Kalog.utils.debounce(function(e) {
                const query = e.target.value.trim();
                
                if (query.length < 2) {
                    container.innerHTML = '';
                    container.style.display = 'none';
                    return;
                }
                
                this.performSearch(query, searchUrl, container);
            }.bind(this), 300));
            
            // Hide results when clicking outside
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !container.contains(e.target)) {
                    container.style.display = 'none';
                }
            });
        },

        /**
         * Perform search
         */
        performSearch: function(query, searchUrl, container) {
            fetch(`${searchUrl}?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                this.displayResults(data, container);
            })
            .catch(error => {
                console.error('Search error:', error);
                container.innerHTML = '<div class="search-error">Search failed. Please try again.</div>';
                container.style.display = 'block';
            });
        },

        /**
         * Display search results
         */
        displayResults: function(results, container) {
            if (!results || results.length === 0) {
                container.innerHTML = '<div class="search-no-results">No results found</div>';
                container.style.display = 'block';
                return;
            }
            
            let html = '<div class="search-results">';
            
            results.forEach(result => {
                html += `
                    <div class="search-result-item" onclick="window.location.href='${result.url}'">
                        <div class="search-result-icon">
                            <i class="fas ${this.getResultIcon(result.type)}"></i>
                        </div>
                        <div class="search-result-content">
                            <div class="search-result-title">${result.title}</div>
                            <div class="search-result-description">${result.description}</div>
                            <div class="search-result-meta">${result.type} • ${result.category}</div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            container.innerHTML = html;
            container.style.display = 'block';
        },

        /**
         * Get icon for result type
         */
        getResultIcon: function(type) {
            const icons = {
                'facility': 'fa-truck-loading',
                'vendor': 'fa-building',
                'area': 'fa-map-marker-alt',
                'user': 'fa-user',
                'report': 'fa-chart-bar',
                'maintenance': 'fa-wrench'
            };
            return icons[type] || 'fa-file';
        }
    };

    // Theme Module
    Kalog.modules.theme = {
        currentTheme: 'light',

        init: function() {
            this.currentTheme = Kalog.utils.storage.get('theme', 'light');
            this.applyTheme(this.currentTheme);
            
            // Listen for system theme changes
            if (window.matchMedia) {
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                mediaQuery.addListener((e) => {
                    if (Kalog.utils.storage.get('theme') === 'system') {
                        this.applyTheme(e.matches ? 'dark' : 'light');
                    }
                });
            }
        },

        toggle: function() {
            const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
            this.setTheme(newTheme);
        },

        setTheme: function(theme) {
            this.currentTheme = theme;
            this.applyTheme(theme);
            Kalog.utils.storage.set('theme', theme);
            Kalog.events.emit('theme:changed', { theme });
        },

        applyTheme: function(theme) {
            const body = document.body;
            
            if (theme === 'dark') {
                body.classList.add('dark-mode');
            } else {
                body.classList.remove('dark-mode');
            }
            
            // Update meta theme color
            const metaTheme = document.querySelector('meta[name="theme-color"]');
            if (metaTheme) {
                metaTheme.content = theme === 'dark' ? '#1f2937' : '#2563eb';
            }
        }
    };

    // Initialize application
    Kalog.init = function() {
        // Initialize modules
        Kalog.modules.theme.init();
        Kalog.modules.notifications.init();
        
        // Initialize global search if available
        const searchInput = document.getElementById('globalSearch');
        if (searchInput) {
            Kalog.modules.search.initGlobalSearch('globalSearch', 'searchResults', Kalog.config.siteUrl + 'dashboard/search');
        }
        
        // Initialize animations for stat cards
        this.initStatAnimations();
        
        // Initialize keyboard shortcuts
        this.initKeyboardShortcuts();
        
        // Initialize lazy loading
        this.initLazyLoading();
        
        // Initialize tooltips
        this.initTooltips();
        
        // Emit ready event
        Kalog.events.emit('app:ready');
    };

    // Initialize stat card animations
    Kalog.initStatAnimations = function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statValue = entry.target.querySelector('.stat-value');
                    if (statValue) {
                        const text = statValue.textContent;
                        const number = parseInt(text.replace(/[^\d]/g, ''));
                        
                        if (!isNaN(number)) {
                            Kalog.modules.animations.countUp(statValue, number);
                        }
                    }
                    
                    observer.unobserve(entry.target);
                }
            });
        });
        
        document.querySelectorAll('.stat-card').forEach(card => {
            observer.observe(card);
        });
    };

    // Initialize keyboard shortcuts
    Kalog.initKeyboardShortcuts = function() {
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K for global search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.getElementById('globalSearch');
                if (searchInput) {
                    searchInput.focus();
                }
            }
            
            // Ctrl/Cmd + / for command palette
            if ((e.ctrlKey || e.metaKey) && e.key === '/') {
                e.preventDefault();
                Kalog.events.emit('command-palette:open');
            }
            
            // Escape to close modals
            if (e.key === 'Escape') {
                Kalog.events.emit('modal:close');
            }
        });
    };

    // Initialize lazy loading
    Kalog.initLazyLoading = function() {
        const images = document.querySelectorAll('img[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            images.forEach(img => {
                imageObserver.observe(img);
            });
        } else {
            // Fallback for older browsers
            images.forEach(img => {
                img.src = img.dataset.src;
                img.classList.remove('lazy');
            });
        }
    };

    // Initialize tooltips
    Kalog.initTooltips = function() {
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', function(e) {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip-popup';
                tooltip.textContent = this.getAttribute('data-tooltip');
                
                document.body.appendChild(tooltip);
                
                const rect = this.getBoundingClientRect();
                tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
                tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
                
                this._tooltip = tooltip;
            });
            
            element.addEventListener('mouseleave', function() {
                if (this._tooltip) {
                    this._tooltip.remove();
                    delete this._tooltip;
                }
            });
        });
    };

    // Auto-initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            Kalog.init();
        });
    } else {
        Kalog.init();
    }

    // Global functions for backward compatibility
    window.showToast = function(title, message, type, duration) {
        type = type || 'info';
        duration = duration || 3000;
        return Kalog.modules.notifications.show(message, type, duration);
    };

    window.toggleTheme = function() {
        return Kalog.modules.theme.toggle();
    };

    window.scrollToTop = function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    };

})();