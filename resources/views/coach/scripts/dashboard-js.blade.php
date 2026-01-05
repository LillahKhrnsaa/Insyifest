<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    let currentMemberId = null;
    let chartValue = null;
    let chartVolume = null;
    let chartRadar = null;
    let isEditMode = false;
    let coaches = [];

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') feather.replace(); 
        
        const closeFormBtn = document.getElementById('closeFormModalBtn');
        if(closeFormBtn) closeFormBtn.addEventListener('click', closeFormModal);
        
        const cancelFormBtn = document.getElementById('cancelFormBtn');
        if(cancelFormBtn) cancelFormBtn.addEventListener('click', closeFormModal);

        const gayaSelect = document.getElementById('gaya');
        if(gayaSelect) gayaSelect.addEventListener('change', loadRaportData);
        
        const yearInput = document.getElementById('year');
        if(yearInput) yearInput.addEventListener('input', loadRaportData);

        const formRaport = document.getElementById('raportForm');
        if(formRaport) formRaport.addEventListener('submit', handleFormSubmit);
    });

    function openRaportModal(memberId, memberName) {
        currentMemberId = memberId;
        document.getElementById('memberName').textContent = memberName;
        document.getElementById('raportModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        loadCoachesList();
        loadRaportData();
        
        if (typeof feather !== 'undefined') {
            setTimeout(() => feather.replace(), 100);
        }
    }

    function closeRaportModal() {
        document.getElementById('raportModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        if (chartValue) { chartValue.destroy(); chartValue = null; }
        if (chartVolume) { chartVolume.destroy(); chartVolume = null; }
    }

    function openCreateForm() {
        isEditMode = false;
        document.getElementById('formModalTitle').textContent = 'Tambah Data Raport';
        document.getElementById('raportForm').reset();
        document.getElementById('raport_id').value = '';
        document.getElementById('form_member_id').value = currentMemberId;
        document.getElementById('form_gaya').value = document.getElementById('gaya').value;
        document.getElementById('form_year').value = document.getElementById('year').value;
        document.getElementById('monthFieldWrapper').style.display = 'block';
        loadAvailableMonths();
        document.getElementById('raportFormModal').classList.remove('hidden');
        
        if (typeof feather !== 'undefined') {
            setTimeout(() => feather.replace(), 100);
        }
    }

    function openEditForm(id, month, value, volume, intensity, peaking, coachId, note) {
        isEditMode = true;
        document.getElementById('formModalTitle').textContent = 'Edit Data Raport';
        document.getElementById('raport_id').value = id;
        document.getElementById('value').value = parseFloat(value).toFixed(2);
        document.getElementById('volume').value = volume;
        document.getElementById('intensity').value = intensity;
        document.getElementById('peaking').value = peaking;
        document.getElementById('coach_id').value = coachId;
        document.getElementById('note').value = note;
        document.getElementById('form_member_id').value = currentMemberId;
        document.getElementById('form_gaya').value = document.getElementById('gaya').value;
        document.getElementById('form_year').value = document.getElementById('year').value;
        document.getElementById('monthFieldWrapper').style.display = 'none';
        document.getElementById('raportFormModal').classList.remove('hidden');
        
        if (typeof feather !== 'undefined') {
            setTimeout(() => feather.replace(), 100);
        }
    }

    function closeFormModal() {
        document.getElementById('raportFormModal').classList.add('hidden');
        document.getElementById('raportForm').reset();
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let url = isEditMode ? `/api/raport/update/${document.getElementById('raport_id').value}` : '/api/raport/create';
        let method = isEditMode ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(isEditMode ? 'Berhasil diupdate!' : 'Berhasil ditambahkan!', 'success');
                closeFormModal();
                loadRaportData();
            } else {
                showAlert(data.message || 'Gagal menyimpan data', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan sistem', 'error');
        });
    }

    function loadRaportData() {
        const gayaSelect = document.getElementById('gaya');
        const gaya = gayaSelect.value;
        const year = document.getElementById('year').value;
        
        if (!gaya) {
            const tbody = document.querySelector('#raport-table tbody');
            if(tbody) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Silakan pilih Kategori Gaya terlebih dahulu.</td></tr>';
            }
            return;
        }

        fetch(`/api/raport/chart-data?member_id=${currentMemberId}&gaya=${gaya}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateDetailInfo(data.raports);
                    updateTable(data.raports);
                    updateCharts(data.chartValue, data.chartVolume);
                } else {
                    showAlert('Gagal memuat data: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function updateTable(raports) {
        const tbody = document.querySelector('#raport-table tbody');
        tbody.innerHTML = '';
        
        if (raports.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada data.</td></tr>';
            return;
        }
        
        raports.forEach(r => {
            const formattedTime = `${String(Math.floor(r.value / 60)).padStart(2, '0')}:${(r.value % 60).toFixed(2).padStart(5, '0')}`;
            
            const peakingValue = r.peaking ? r.peaking : '-';

            const row = `
                <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                    <td class="px-5 py-3 font-bold text-slate-800 capitalize">${r.month}</td>
                    <td class="px-5 py-3 text-cyan-600 font-mono font-bold">${formattedTime}</td>
                    <td class="px-5 py-3 text-slate-600">${r.volume}m</td>
                    <td class="px-5 py-3"><span class="px-2 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">${r.intensity}%</span></td>
                    <td class="px-5 py-3 font-medium text-slate-700">${peakingValue}</td>
                    
                    <td class="px-5 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openEditForm(${r.id}, '${r.month}', '${r.value}', '${r.volume}', '${r.intensity}', '${r.peaking || ''}', '${r.coach_id}', '${r.note || ''}')" 
                                    class="btn-action btn-edit" 
                                    title="Edit Data">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg>
                            </button>
                            
                            <button onclick="confirmDelete(${r.id}, '${r.month}')" 
                                    class="btn-action btn-delete" 
                                    title="Hapus Data">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', row);
        });
        
        if (typeof feather !== 'undefined') {
            setTimeout(() => feather.replace(), 100);
        }
    }

    function updateCharts(valueData, volumeData) {
        if (chartValue) chartValue.destroy();
        if (chartVolume) chartVolume.destroy();

        if (typeof Chart === 'undefined') {
            console.error('Chart.js belum dimuat.');
            return;
        }

        const colorText = '#64748b';
        const colorGrid = '#e2e8f0';
        const primaryColor = '#0891b2';
        
        const barColors = [
            '#0891b2',
            '#10b981',
            '#8b5cf6',
            '#f59e0b',
            '#ef4444',
            '#06b6d4',
            '#84cc16',
            '#8b5cf6',
        ];

        const ctx1 = document.getElementById('chartValue').getContext('2d');
        chartValue = new Chart(ctx1, {
            type: 'line',
            data: valueData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                elements: {
                    line: { 
                        borderColor: primaryColor, 
                        borderWidth: 3, 
                        tension: 0.4 
                    }, 
                    point: { 
                        backgroundColor: '#ffffff', 
                        borderColor: primaryColor, 
                        borderWidth: 2, 
                        radius: 6,
                        hoverRadius: 8
                    }
                },
                scales: {
                    y: { 
                        reverse: true, 
                        ticks: { 
                            color: colorText,
                            font: { size: 12, weight: '600' }
                        }, 
                        grid: { 
                            color: colorGrid,
                            drawBorder: false
                        },
                        title: {
                            display: true,
                            text: 'Waktu (detik)',
                            color: colorText,
                            font: { size: 12, weight: '700' }
                        }
                    },
                    x: { 
                        ticks: { 
                            color: colorText,
                            font: { size: 12, weight: '600' }
                        }, 
                        grid: { 
                            display: false 
                        },
                        title: {
                            display: true,
                            text: 'Bulan',
                            color: colorText,
                            font: { size: 12, weight: '700' }
                        }
                    }
                },
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#f1f5f9',
                        bodyColor: '#f1f5f9',
                        borderColor: '#0891b2',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return `Waktu: ${context.parsed.y.toFixed(2)} detik`;
                            }
                        }
                    }
                }
            }
        });

        const ctx2 = document.getElementById('chartVolume').getContext('2d');
        
        if (volumeData && volumeData.datasets) {
            volumeData.datasets.forEach((dataset, index) => {
                if (index === 0) {
                    dataset.backgroundColor = barColors[0];
                    dataset.borderColor = barColors[0];
                    dataset.borderWidth = 2;
                    dataset.borderRadius = 6;
                    dataset.barPercentage = 0.7;
                }
                if (index === 1) {
                    dataset.backgroundColor = barColors[1];
                    dataset.borderColor = barColors[1];
                    dataset.borderWidth = 2;
                    dataset.borderRadius = 6;
                    dataset.barPercentage = 0.7;
                }
            });
        }

        chartVolume = new Chart(ctx2, {
            type: 'bar',
            data: volumeData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { 
                            color: colorText,
                            font: { size: 12, weight: '600' }
                        }, 
                        grid: { 
                            color: colorGrid,
                            drawBorder: false
                        },
                        title: {
                            display: true,
                            text: 'Nilai',
                            color: colorText,
                            font: { size: 12, weight: '700' }
                        }
                    },
                    x: { 
                        ticks: { 
                            color: colorText,
                            font: { size: 12, weight: '600' }
                        }, 
                        grid: { 
                            display: false 
                        },
                        title: {
                            display: true,
                            text: 'Bulan',
                            color: colorText,
                            font: { size: 12, weight: '700' }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: colorText,
                            font: { size: 12, weight: '600' },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'rect'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#f1f5f9',
                        bodyColor: '#f1f5f9',
                        borderColor: '#0891b2',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.parsed.y;
                                return `${label}: ${value}`;
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    function updateDetailInfo(raports) {
        const detailDiv = document.getElementById('raport-detail');
        if (!detailDiv) return;

        if (raports.length === 0) {
            detailDiv.innerHTML = '<p class="text-center italic mb-4">Belum ada data untuk periode ini.</p>';
            return;
        }
        
        let html = '<div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">';
        raports.slice(0, 4).forEach(r => {
                const formattedTime = `${String(Math.floor(r.value / 60)).padStart(2, '0')}:${(r.value % 60).toFixed(2).padStart(5, '0')}`;
                html += `
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wide">${r.month}</div>
                    <div class="text-xl font-bold text-cyan-600 font-mono mt-1">${formattedTime}</div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">${r.volume}m</div>
                </div>
                `;
        });
        html += '</div>';
        detailDiv.innerHTML = html;
    }

    function loadCoachesList() {
        fetch('/api/raport/coaches').then(r => r.json()).then(d => {
            if(d.success) {
                const s = document.getElementById('coach_id');
                s.innerHTML = '<option value="">-- Pilih Coach --</option>';
                d.coaches.forEach(c => s.innerHTML += `<option value="${c.id}">${c.name}</option>`);
            }
        });
    }

    function loadAvailableMonths() {
        const gaya = document.getElementById('gaya').value;
        const year = document.getElementById('year').value;
        fetch(`/api/raport/available-months?member_id=${currentMemberId}&gaya=${gaya}&year=${year}`)
            .then(r => r.json()).then(d => {
                if(d.success) {
                    const s = document.getElementById('month');
                    s.innerHTML = '<option value="">-- Pilih Bulan --</option>';
                    Object.entries(d.months).forEach(([k, v]) => s.innerHTML += `<option value="${k}">${v}</option>`);
                }
            });
    }

    function confirmDelete(id, month) {
        if(confirm(`Hapus data bulan ${month}?`)) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/api/raport/delete/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } })
                .then(r => r.json()).then(d => {
                    if(d.success) { showAlert('Dihapus!', 'success'); loadRaportData(); }
                    else { showAlert('Gagal hapus', 'error'); }
                });
        }
    }

    function showAlert(message, type = 'success') {
        const div = document.createElement('div');
        div.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-xl z-[100] text-white font-bold transition-all transform duration-500 translate-y-0 ${type === 'success' ? 'bg-cyan-600' : 'bg-red-500'}`;
        div.textContent = message;
        document.body.appendChild(div);
        setTimeout(() => { div.style.opacity = '0'; setTimeout(() => div.remove(), 500); }, 3000);
    }

    function openPhysicalModal(memberId, memberName) {
        currentMemberId = memberId;
        document.getElementById('physMemberName').textContent = memberName;
        document.getElementById('physicalModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        loadPhysicalData();
    }

    function closePhysicalModal() {
        document.getElementById('physicalModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        if (chartRadar) { chartRadar.destroy(); chartRadar = null; }
    }

    function loadPhysicalData() {
        const year = document.getElementById('phys_year').value;
        fetch(`/api/physical/data?member_id=${currentMemberId}&year=${year}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updatePhysicalTable(data.history);
                    renderRadarChart(data.radarData);
                }
            });
    }

    function updatePhysicalTable(history) {
        const tbody = document.querySelector('#phys-table tbody');
        tbody.innerHTML = history.length ? '' : '<tr><td colspan="5" class="px-4 py-10 text-center text-slate-400">Belum ada data fisik.</td></tr>';
        
        history.forEach(h => {
            tbody.insertAdjacentHTML('beforeend', `
                <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                    <td class="px-4 py-4 font-bold text-slate-800 capitalize">${h.month}</td>
                    <td class="px-4 py-4 text-rose-600 font-black">${h.vo2max || '-'}</td>
                    <td class="px-4 py-4 text-slate-600">${h.sprint_20m || '-'}s</td>
                    <td class="px-4 py-4 text-slate-600">${h.push_up || 0}/${h.sit_up || 0}</td>
                    <td class="px-4 py-4 text-slate-600">${h.shuttle_run || '-'}s</td>
                </tr>
            `);
        });
    }

    function renderRadarChart(radarData) {
        if (chartRadar) chartRadar.destroy();
        const ctx = document.getElementById('chartRadar').getContext('2d');
        chartRadar = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Speed', 'Strength', 'Endurance', 'Flexibility', 'Agility'],
                datasets: [{
                    label: 'Profil Atlet',
                    data: radarData,
                    backgroundColor: 'rgba(244, 63, 94, 0.2)',
                    borderColor: 'rgb(244, 63, 94)',
                    pointBackgroundColor: 'rgb(244, 63, 94)',
                }]
            },
            options: { scales: { r: { min: 0, max: 5, ticks: { display: false } } }, plugins: { legend: { display: false } } }
        });
    }

    function handlePhysSubmit(e) {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        fetch('/api/physical/store', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
            },
            body: JSON.stringify(data)
        }).then(res => res.json()).then(res => {
            if (res.success) {
                showAlert('Data Fisik Tersimpan!', 'success');
                document.getElementById('physFormModal').classList.add('hidden');
                loadPhysicalData();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const physForm = document.getElementById('physForm');
        if (physForm) {
            physForm.addEventListener('submit', handlePhysSubmit);
        }
    });

    function openPhysForm() {
        const form = document.getElementById('physForm');
        if (form) form.reset();

        const vo2Field = document.getElementById('vo2max');
        if (vo2Field) vo2Field.value = '';

        const memberField = document.getElementById('phys_form_member_id');
        const yearField = document.getElementById('phys_form_year');
        const physYearInput = document.getElementById('phys_year');

        if (memberField) memberField.value = currentMemberId;
        if (yearField && physYearInput) yearField.value = physYearInput.value;

        const modal = document.getElementById('physFormModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
        
        if (typeof feather !== 'undefined') feather.replace();
    }

    function closePhysFormModal() {
        const modal = document.getElementById('physFormModal');
        if (modal) modal.classList.add('hidden');
    }

    function calculateBleep() {
        const lvlInput = document.getElementById('bleep_level');
        const shtInput = document.getElementById('bleep_shuttle');
        const vo2Field = document.getElementById('vo2max');

        if (!lvlInput || !shtInput || !vo2Field) return;

        const lvl = parseInt(lvlInput.value) || 0;
        const sht = parseInt(shtInput.value) || 0;
        
        if (lvl > 0) {
            const shuttleTable = { 1: 9, 2: 8, 3: 8, 4: 9, 5: 9, 6: 10, 7: 10, 8: 11, 9: 11, 10: 11, 11: 12, 12: 12, 13: 13 };
            const tsl = shuttleTable[lvl] || 10;
            
            const vo2 = 3.46 * (lvl + (sht / tsl)) + 12.2;
            vo2Field.value = vo2.toFixed(2);
        } else {
            vo2Field.value = '';
        }
    }

    function renderRadarChart(radarData) {
        const canvas = document.getElementById('chartRadar');
        if (!canvas) return;

        if (chartRadar) chartRadar.destroy();
        
        const ctx = canvas.getContext('2d');
        chartRadar = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Speed', 'Strength', 'Endurance', 'Flexibility', 'Agility'],
                datasets: [{
                    label: 'Profil Atlet',
                    data: radarData || [0,0,0,0,0],
                    backgroundColor: 'rgba(244, 63, 94, 0.2)',
                    borderColor: 'rgb(244, 63, 94)',
                    pointBackgroundColor: 'rgb(244, 63, 94)',
                    pointBorderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        min: 0,
                        max: 5,
                        beginAtZero: true,
                        ticks: { display: false, stepSize: 1 },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        angleLines: { color: 'rgba(0,0,0,0.05)' },
                        pointLabels: { font: { size: 10, weight: 'bold' } }
                    }
                },
                plugins: { legend: { display: false } }
            }
        });
    }

    function openEditAttendanceModal(id, date, time, place, notes) {
        document.getElementById('edit_attendance_id').value = id;
        document.getElementById('edit_date').value = date;
        document.getElementById('edit_time').value = time.substring(0, 5); 
        document.getElementById('edit_place').value = place;
        document.getElementById('edit_notes').value = notes;

        document.getElementById('editAttendanceModal').classList.remove('hidden');
        if (typeof feather !== 'undefined') feather.replace();
    }

    function closeEditAttendanceModal() {
        document.getElementById('editAttendanceModal').classList.add('hidden');
    }

    document.getElementById('formEditAttendance').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('edit_attendance_id').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const data = {
            date: document.getElementById('edit_date').value,
            time: document.getElementById('edit_time').value,
            place: document.getElementById('edit_place').value,
            notes: document.getElementById('edit_notes').value
        };

        fetch(`/attendance/update/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert('Berhasil diperbarui!');
                location.reload();
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(err => console.error(err));
    });

    function deleteAttendance(id) {
        if(!confirm('Yakin ingin menghapus riwayat absensi ini? Data tidak bisa dikembalikan.')) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/attendance/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert('Riwayat berhasil dihapus.');
                location.reload();
            } else {
                alert('Gagal menghapus: ' + data.message);
            }
        })
        .catch(err => console.error(err));
    }
</script>