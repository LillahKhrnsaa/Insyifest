import './bootstrap';
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/autoplay';
import { Autoplay } from 'swiper/modules';

import Alpine from 'alpinejs';
import feather from 'feather-icons';

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

// 2. Daftarkan Logika Dashboard Anda sebagai Komponen Alpine
Alpine.data('coachDashboard', (hasErrors, oldScheduleId, oldPlace) => ({
    
    // --- STATE ---
    showModal: hasErrors,
    selectedScheduleId: oldScheduleId || null,
    selectedSchedulePlace: oldPlace || '',
    theme: localStorage.getItem('theme') || 'system',

    // --- METHODS (termasuk init) ---
    init() {
        console.log('Alpine component initialized.'); // Untuk debugging
        
        // 'this' di sini sudah PASTI benar (merujuk ke komponen Alpine)
        this.$watch('theme', val => {
            localStorage.setItem('theme', val);
            applyTheme(val);
        });
        
        // Dengarkan perubahan OS
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (this.theme === 'system') {
                applyTheme('system');
            }
        });

        // Panggil feather.replace() secara reaktif
        // 'feather' sekarang sudah terdefinisi karena di-import di atas
        Alpine.effect(() => {
            feather.replace();
        });
    }

}));

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
});