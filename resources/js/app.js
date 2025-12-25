import './bootstrap';
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/autoplay';
import { Autoplay } from 'swiper/modules';
import Chart from 'chart.js/auto';

import Alpine from 'alpinejs';
import feather from 'feather-icons';

// Theme Management
function applyTheme(themeVal) {
    if (themeVal === 'dark') {
        document.documentElement.classList.add('dark');
    } else if (themeVal === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        // 'system'
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}

// Initialize Alpine.js Components
Alpine.data('coachDashboard', (hasErrors, oldScheduleId, oldPlace) => ({
    // State
    showModal: hasErrors,
    selectedScheduleId: oldScheduleId || null,
    selectedSchedulePlace: oldPlace || '',
    theme: localStorage.getItem('theme') || 'system',

    // Methods
    init() {
        console.log('Coach Dashboard initialized');
        
        // Watch theme changes
        this.$watch('theme', val => {
            localStorage.setItem('theme', val);
            applyTheme(val);
        });
        
        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (this.theme === 'system') {
                applyTheme('system');
            }
        });

        // Initialize Feather Icons reactively
        Alpine.effect(() => {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
        
        // Apply initial theme
        applyTheme(this.theme);
    }
}));

// Initialize Swiper for carousels if needed
function initSwiper() {
    const swiperElements = document.querySelectorAll('.swiper-container');
    if (swiperElements.length > 0) {
        swiperElements.forEach(element => {
            new Swiper(element, {
                modules: [Autoplay],
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    }
}

// Initialize Charts if needed
function initCharts() {
    const chartElements = document.querySelectorAll('[data-chart]');
    if (chartElements.length > 0 && typeof Chart !== 'undefined') {
        chartElements.forEach(element => {
            const ctx = element.getContext('2d');
            const chartType = element.dataset.chartType || 'line';
            const chartData = JSON.parse(element.dataset.chartData || '{}');
            
            new Chart(ctx, {
                type: chartType,
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        });
    }
}

// Initialize all components when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Alpine.js
    window.Alpine = Alpine;
    Alpine.start();
    
    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Initialize Swiper
    initSwiper();
    
    // Initialize Charts
    initCharts();
    
    // Make Chart available globally
    window.Chart = Chart;
    
    // Add any global event listeners
    document.addEventListener('alpine:initialized', () => {
        console.log('Alpine.js initialized');
    });
});

// Export for module usage
export { Alpine, Chart, initSwiper, initCharts };