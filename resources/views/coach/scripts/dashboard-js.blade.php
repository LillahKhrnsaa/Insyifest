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

        const physForm = document.getElementById('physForm');
        if (physForm) {
            physForm.addEventListener('submit', handlePhysSubmit);
        }

        // Filter Pencarian Coach
        const coachSearch = document.getElementById('coach_search');
        if (coachSearch) {
            coachSearch.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                const coachSelect = document.getElementById('coach_id');
                const filtered = coaches.filter(c => c.name.toLowerCase().includes(term));
                
                // Simpan nilai yang sedang terpilih agar tidak hilang jika masih ada dalam filter
                const currentVal = coachSelect.value;
                
                coachSelect.innerHTML = '<option value="">-- Pilih Coach --</option>';
                filtered.forEach(c => {
                    const selected = c.id == currentVal ? 'selected' : '';
                    coachSelect.insertAdjacentHTML('beforeend', `<option value="${c.id}" ${selected}>${c.name}</option>`);
                });
            });
        }
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
        if (coaches.length > 0) return;
        fetch('/api/raport/coaches').then(r => r.json()).then(d => {
            if(d.success) {
                coaches = d.coaches;
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

    let physicalVariables = [];

    function openPhysicalModal(memberId, memberName) {
        currentMemberId = memberId;
        const nameEl = document.getElementById('physMemberName');
        if (nameEl) nameEl.textContent = memberName;
        
        const modal = document.getElementById('physicalModal');
        if (modal) modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Show loading state in table
        const thead = document.getElementById('phys-table-header');
        if (thead) thead.innerHTML = '<th class="px-6 py-4">Bulan</th><th class="px-6 py-4 text-center">Memuat Parameter...</th>';
        
        loadPhysicalVariables().then(() => {
            loadPhysicalData();
        });
    }

    function closePhysicalModal() {
        document.getElementById('physicalModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        if (chartRadar) { chartRadar.destroy(); chartRadar = null; }
    }

    function loadPhysicalVariables() {
        return fetch('/api/physical/variables')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    physicalVariables = data.variables;
                }
                return data.variables;
            });
    }

    function loadPhysicalData() {
        const year = document.getElementById('phys_year').value;
        const month = document.getElementById('phys_month').value;
        fetch(`/api/physical/data?member_id=${currentMemberId}&year=${year}&month=${month}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updatePhysicalTable(data.history, data.selectedMonth);
                    renderRadarChart(data.radarData, data.radarLabels);
                }
            });
    }

    function updatePhysicalTable(history, selectedMonth) {
        const thead = document.getElementById('phys-table-header');
        const tbody = document.querySelector('#phys-table tbody');
        if (!tbody || !thead) return;

        // 1. Render Headers
        let headerHtml = '<th class="px-10 py-5">Bulan</th>';
        physicalVariables.forEach(v => {
            const isRose = v.name.toLowerCase().includes('vo2');
            headerHtml += `<th class="px-10 py-5 text-center ${isRose ? 'text-rose-600' : ''}">${v.name}</th>`;
        });
        headerHtml += '<th class="px-10 py-5 text-center">Aksi</th>';
        thead.innerHTML = headerHtml;

        // 2. Render Rows
        tbody.innerHTML = history.length ? '' : `<tr><td colspan="${physicalVariables.length + 2}" class="px-10 py-24 text-center text-slate-400">
            <div class="flex flex-col items-center gap-4">
                <div class="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-slate-800 flex items-center justify-center border-4 border-white dark:border-slate-700 shadow-xl">
                    <i data-feather="database" class="w-10 h-10 opacity-30"></i>
                </div>
                <div>
                    <p class="font-black text-slate-600 dark:text-slate-400 text-sm uppercase tracking-widest">Belum ada data fisik</p>
                    <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold">Silakan input hasil tes pertama untuk atlet ini</p>
                </div>
            </div>
        </td></tr>`;
        
        history.forEach(h => {
            const isSelected = h.month === selectedMonth;
            let rowHtml = `
                <tr class="transition-colors border-b border-slate-50 dark:border-slate-700 ${isSelected ? 'bg-rose-50/40 dark:bg-rose-900/10' : 'hover:bg-slate-50 dark:hover:bg-slate-800/50'}">
                    <td class="px-10 py-6">
                        <div class="flex items-center gap-3">
                            <span class="font-black text-slate-700 dark:text-slate-200 capitalize text-base">${h.month}</span>
                            ${isSelected ? '<span class="px-2 py-0.5 bg-rose-600 text-white text-[8px] font-black rounded-md shadow-lg shadow-rose-200">ACTIVE</span>' : ''}
                        </div>
                    </td>
            `;

            physicalVariables.forEach(v => {
                let val = '-';
                if (h.results && h.results[v.name] !== undefined) {
                    val = h.results[v.name];
                } else if (h[v.name.toLowerCase().replace(' ', '_')]) {
                    val = h[v.name.toLowerCase().replace(' ', '_')];
                }
                
                rowHtml += `<td class="px-10 py-6 text-center font-black text-slate-800 dark:text-slate-200 text-lg">${val}<span class="text-[10px] ml-1 text-slate-400 font-bold uppercase tracking-tighter">${v.unit || ''}</span></td>`;
            });

            rowHtml += `
                    <td class="px-10 py-6 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick='editPhysicalData(${JSON.stringify(h)})' class="w-10 h-10 flex items-center justify-center text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-600 hover:text-white rounded-xl transition-all shadow-sm">
                                <i data-feather="edit-3" class="w-4 h-4"></i>
                            </button>
                            <button onclick="deletePhysicalData(${h.id})" class="w-10 h-10 flex items-center justify-center text-rose-600 bg-rose-50 dark:bg-rose-900/20 hover:bg-rose-600 hover:text-white rounded-xl transition-all shadow-sm">
                                <i data-feather="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', rowHtml);
        });
        if (typeof feather !== 'undefined') feather.replace();
    }

    function renderRadarChart(radarData, labels) {
        const canvas = document.getElementById('chartRadar');
        if (!canvas) return;

        if (chartRadar) chartRadar.destroy();
        
        const ctx = canvas.getContext('2d');
        chartRadar = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels && labels.length ? labels : ['Speed', 'Strength', 'Endurance', 'Flexibility', 'Agility'],
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

    function openPhysForm() {
        const container = document.getElementById('dynamic-variables-container');
        container.innerHTML = '<div class="flex justify-center p-4"><i data-feather="loader" class="animate-spin"></i></div>';
        if (typeof feather !== 'undefined') feather.replace();

        loadPhysicalVariables().then(vars => {
            renderDynamicFormFields(vars);
            
            const form = document.getElementById('physForm');
            if (form) {
                // If not in edit mode (triggered by openPhysForm from button)
                if (!document.getElementById('phys_id').value) {
                    form.reset();
                    document.getElementById('phys_id').value = '';
                    document.querySelector('#physFormModal h3').textContent = 'Input Data Fisik';
                }
            }

            document.getElementById('phys_form_member_id').value = currentMemberId;
            document.getElementById('phys_form_year').value = document.getElementById('phys_year').value;
            
            document.getElementById('physFormModal').classList.remove('hidden');
            if (typeof feather !== 'undefined') feather.replace();
        });
    }

    function renderDynamicFormFields(vars, values = {}) {
        const container = document.getElementById('dynamic-variables-container');
        if (vars.length === 0) {
            container.innerHTML = `
                <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100 text-center">
                    <p class="text-amber-700 text-xs font-bold uppercase mb-2">Variabel Belum Diatur</p>
                    <p class="text-[10px] text-amber-600 mb-3">Silakan atur variabel tes fisik terlebih dahulu melalui ikon gear di atas.</p>
                    <button type="button" onclick="openConfigModal()" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-[10px] font-black uppercase">Atur Sekarang</button>
                </div>
            `;
            return;
        }

        let html = '';
        
        // Cek apakah ada variabel khusus Bleep Test
        const hasBleep = vars.some(v => v.name.toLowerCase().includes('bleep'));
        
        if (hasBleep) {
            html += `
                <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-3">
                    <div class="text-[10px] font-black text-rose-600 uppercase mb-1 flex items-center gap-2">
                        <i data-feather="zap" class="w-3 h-3"></i> Bleep Test (VO2 Max)
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold uppercase">Level</label>
                            <input type="number" name="results[Bleep Level]" id="bleep_level" oninput="calculateBleep()" value="${values['Bleep Level'] || ''}" placeholder="8" class="input-field w-full py-2 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold uppercase">Shuttle</label>
                            <input type="number" name="results[Bleep Shuttle]" id="bleep_shuttle" oninput="calculateBleep()" value="${values['Bleep Shuttle'] || ''}" placeholder="5" class="input-field w-full py-2 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase">Hasil Estimasi VO2 Max</label>
                        <input type="text" id="vo2max" readonly value="${values['VO2 Max'] || ''}" class="input-field w-full py-2 bg-rose-50 dark:bg-rose-900/20 border-none font-black text-rose-600 text-center rounded-xl">
                    </div>
                </div>
            `;
        }

        // Render variabel lainnya
        html += '<div class="grid grid-cols-2 gap-3">';
        vars.forEach(v => {
            if (v.name.toLowerCase().includes('bleep') || v.name.toLowerCase() === 'vo2 max') return;
            
            html += `
                <div class="col-span-1">
                    <label class="text-[10px] text-slate-500 font-bold uppercase">${v.name} ${v.unit ? '('+v.unit+')' : ''}</label>
                    <input type="number" step="0.01" name="results[${v.name}]" value="${values[v.name] || ''}" class="input-field w-full py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm">
                </div>
            `;
        });
        html += '</div>';
        
        container.innerHTML = html;
        if (typeof feather !== 'undefined') feather.replace();
    }

    function editPhysicalData(data) {
        document.getElementById('phys_id').value = data.id;
        document.getElementById('phys_month').value = data.month;
        document.getElementById('phys_note').value = data.note || '';
        
        loadPhysicalVariables().then(vars => {
            renderDynamicFormFields(vars, data.results || {});
            document.querySelector('#physFormModal h3').textContent = 'Edit Data Fisik';
            document.getElementById('phys_form_member_id').value = currentMemberId;
            document.getElementById('phys_form_year').value = document.getElementById('phys_year').value;
            document.getElementById('physFormModal').classList.remove('hidden');
        });
    }

    function handlePhysSubmit(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const id = document.getElementById('phys_id').value;
        const url = id ? `/api/physical/update/${id}` : '/api/physical/store';
        const method = id ? 'PUT' : 'POST';
        
        // Convert FormData to nested object for 'results'
        const obj = {
            results: {},
            note: formData.get('note'),
            month: formData.get('month'),
            member_id: formData.get('member_id'),
            year: formData.get('year')
        };
        
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('results[')) {
                const realKey = key.match(/\[(.*?)\]/)[1];
                obj.results[realKey] = value;
            }
        }

        fetch(url, {
            method: method,
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
            },
            body: JSON.stringify(obj)
        }).then(res => res.json()).then(res => {
            if (res.success) {
                showAlert(res.message, 'success');
                closePhysFormModal();
                loadPhysicalData();
            } else {
                showAlert(res.message || 'Gagal menyimpan data', 'error');
            }
        });
    }

    // --- CONFIGURATION FUNCTIONS ---
    function openConfigModal() {
        document.getElementById('configPhysModal').classList.remove('hidden');
        loadPhysicalVariables().then(vars => {
            const list = document.getElementById('config-variables-list');
            list.innerHTML = '';
            if (vars.length === 0) {
                // Add default placeholders
                ['Bleep Level', 'Bleep Shuttle', 'Sprint 20m', 'Push Up', 'Sit Up', 'Agility'].forEach(name => {
                    addVariableRow({ name: name, goal_value: name.includes('Sprint') || name.includes('Agility') ? 5 : 50, unit: name.includes('s') ? 's' : 'x' });
                });
            } else {
                vars.forEach(v => addVariableRow(v));
            }
        });
    }

    function closeConfigModal() {
        document.getElementById('configPhysModal').classList.add('hidden');
    }

    function addVariableRow(data = { name: '', goal_value: 100, unit: '' }) {
        const list = document.getElementById('config-variables-list');
        const id = 'var-' + Date.now() + Math.floor(Math.random() * 1000);
        const row = `
            <div id="${id}" class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm space-y-4 relative group transition-all hover:border-indigo-300">
                <button onclick="document.getElementById('${id}').remove()" class="absolute -top-2 -right-2 w-8 h-8 bg-white dark:bg-slate-800 shadow-lg rounded-full flex items-center justify-center text-slate-300 hover:text-rose-500 transition-colors border border-slate-100 dark:border-slate-700">
                    <i data-feather="x" class="w-4 h-4"></i>
                </button>
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 md:col-span-6">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Nama Parameter</label>
                        <input type="text" placeholder="Misal: Push Up" value="${data.name}" class="var-name w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm font-bold p-3 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    </div>
                    <div class="col-span-6 md:col-span-3">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Target Goal</label>
                        <input type="number" placeholder="100" value="${data.goal_value}" class="var-goal w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm font-black p-3 text-center text-rose-600 focus:ring-2 focus:ring-rose-500/20 transition-all">
                    </div>
                    <div class="col-span-6 md:col-span-3">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Satuan (Unit)</label>
                        <input type="text" placeholder="Misal: kali" value="${data.unit || ''}" class="var-unit w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-xs font-bold p-3 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                    </div>
                </div>
            </div>
        `;
        list.insertAdjacentHTML('beforeend', row);
        if (typeof feather !== 'undefined') feather.replace();
    }

    function savePhysicalVariables() {
        const rows = document.querySelectorAll('#config-variables-list > div');
        const variables = [];
        rows.forEach(row => {
            const name = row.querySelector('.var-name').value.trim();
            const goal = row.querySelector('.var-goal').value;
            const unit = row.querySelector('.var-unit').value;
            if (name) {
                variables.push({ name: name, goal_value: goal, unit: unit });
            }
        });

        if (variables.length === 0) {
            alert('Minimal harus ada satu variabel.');
            return;
        }

        fetch('/api/physical/variables/store', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
            },
            body: JSON.stringify({ variables: variables })
        }).then(res => res.json()).then(res => {
            if (res.success) {
                showAlert(res.message, 'success');
                closeConfigModal();
                loadPhysicalVariables().then(vars => {
                    if (!document.getElementById('physFormModal').classList.contains('hidden')) {
                        renderDynamicFormFields(vars);
                    }
                    loadPhysicalData();
                });
            }
        });
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

    function deleteMember(id, name) {
        Swal.fire({
            title: 'Hapus Atlet?',
            text: `Apakah Anda yakin ingin menghapus ${name}? Semua data raport, fisik, dan jadwal terkait akan dihapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/coach/member/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Terhapus!', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                });
            }
        });
    }
</script>