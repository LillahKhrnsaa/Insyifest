<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') feather.replace();
    });
    
    const currentMemberId = {{ $member->id }}; 
    
    let chartValue = null;
    let chartVolume = null;
    let chartRadar = null;

    function openRaportModal() {
        document.getElementById('raportModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        const gayaSelect = document.getElementById('gaya');
        if(gayaSelect.value) loadRaportData();
    }

    function closeRaportModal() {
        document.getElementById('raportModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        if (chartValue) { chartValue.destroy(); chartValue = null; }
        if (chartVolume) { chartVolume.destroy(); chartVolume = null; }
    }

    function loadRaportData() {
        const gayaSelect = document.getElementById('gaya');
        const gaya = gayaSelect.value;
        const year = document.getElementById('year').value;
        
        if (!gaya) {
            const tbody = document.querySelector('#raport-table tbody');
            const detail = document.getElementById('raport-detail');
            if(tbody) tbody.innerHTML = '<tr><td colspan="6" class="px-8 py-12 text-center text-slate-300 font-black uppercase tracking-widest text-xs">Pilih Kategori Gaya & Jarak</td></tr>';
            if(detail) detail.innerHTML = '';
            return;
        }

        fetch(`/member/performance-data?gaya=${gaya}&year=${year}`)
            .then(response => {
                if (!response.ok) throw new Error("Gagal mengambil data");
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateDetailInfo(data.raports);
                    updateTable(data.raports);
                    updateCharts(data.chartValue, data.chartVolume);
                    if (typeof feather !== 'undefined') feather.replace();
                } else {
                    alert('Gagal memuat data: ' + data.message);
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
            tbody.innerHTML = '<tr><td colspan="6" class="px-8 py-12 text-center text-slate-300 font-black uppercase tracking-widest text-xs italic">Data belum tersedia untuk periode ini</td></tr>';
            return;
        }
        
        raports.forEach(r => {
            const formattedTime = `${String(Math.floor(r.value / 60)).padStart(2, '0')}:${(r.value % 60).toFixed(2).padStart(5, '0')}`;
            const row = `
                <tr class="hover:bg-blue-50/30 transition-colors group">
                    <td class="px-8 py-5 font-black text-slate-700 uppercase tracking-tight group-hover:text-blue-600 transition-colors">${r.month}</td>
                    <td class="px-8 py-5 text-blue-600 font-black tracking-widest text-base">${formattedTime}</td>
                    <td class="px-8 py-5 text-indigo-500 font-black uppercase tracking-widest text-xs">${r.volume}m</td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 rounded-lg text-[10px] font-black bg-blue-50 text-blue-600 border border-blue-100 shadow-sm">${r.intensity}%</span>
                    </td>
                    <td class="px-8 py-5 font-bold text-slate-400 italic text-xs">${r.peaking || '-'}</td>
                    <td class="px-8 py-5 text-[11px] text-slate-500 font-medium leading-relaxed max-w-xs truncate" title="${r.note || ''}">${r.note || '-'}</td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }

    function updateDetailInfo(raports) {
        const detailDiv = document.getElementById('raport-detail');
        if (!detailDiv) return;

        if (raports.length === 0) {
            detailDiv.innerHTML = '';
            return;
        }
        
        let html = '<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">';
        raports.slice(0, 4).forEach(r => {
             const formattedTime = `${String(Math.floor(r.value / 60)).padStart(2, '0')}:${(r.value % 60).toFixed(2).padStart(5, '0')}`;
             html += `
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">${r.month}</div>
                    <div class="text-xl font-black text-blue-600 tracking-widest mt-1">${formattedTime}</div>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="w-1 h-1 rounded-full bg-indigo-400"></span>
                        <div class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">${r.volume}m</div>
                    </div>
                </div>
             `;
        });
        html += '</div>';
        detailDiv.innerHTML = html;
    }

    function updateCharts(valueData, volumeData) {
        if (typeof Chart === 'undefined') return;

        if (chartValue) chartValue.destroy();
        if (chartVolume) chartVolume.destroy();

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: '900', family: 'Nunito' }, color: '#94a3b8' }
                },
                y: {
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 10, weight: '700' }, color: '#94a3b8' }
                }
            }
        };

        const ctx1 = document.getElementById('chartValue').getContext('2d');
        const gradientBlue = ctx1.createLinearGradient(0, 0, 0, 400);
        gradientBlue.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
        gradientBlue.addColorStop(1, 'rgba(37, 99, 235, 0)');

        chartValue = new Chart(ctx1, {
            type: 'line',
            data: {
                ...valueData,
                datasets: valueData.datasets.map(ds => ({
                    ...ds,
                    borderColor: '#2563eb',
                    backgroundColor: gradientBlue,
                    fill: true,
                    borderWidth: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4
                }))
            },
            options: {
                ...commonOptions,
                scales: { 
                    ...commonOptions.scales,
                    y: { ...commonOptions.scales.y, reverse: true } 
                }
            }
        });

        const ctx2 = document.getElementById('chartVolume').getContext('2d');
        if(volumeData.datasets) {
            const colors = ['#3b82f6', '#6366f1', '#8b5cf6'];
            volumeData.datasets.forEach((ds, i) => {
                ds.backgroundColor = colors[i % colors.length];
                ds.borderRadius = 8;
                ds.borderSkipped = false;
            });
        }

        chartVolume = new Chart(ctx2, {
            type: 'bar',
            data: volumeData,
            options: {
                ...commonOptions,
                plugins: { legend: { display: true, labels: { font: { size: 10, weight: '900' } } } }
            }
        });
    }

    function openPhysicalModal() {
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
        const month = document.getElementById('phys_month').value;
        fetch(`/api/member/physical-data?year=${year}&month=${month}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updatePhysicalTable(data.history, data.selectedMonth);
                    renderRadarChart(data.radarData, data.radarLabels);
                    if (typeof feather !== 'undefined') feather.replace();
                }
            });
    }

    function updatePhysicalTable(history, selectedMonth) {
        const tbody = document.querySelector('#phys-table tbody');
        tbody.innerHTML = '';
        
        if (history.length === 0) {
            tbody.innerHTML = '<tr><td colspan="2" class="px-8 py-20 text-center text-slate-300 font-black uppercase tracking-widest text-xs italic">Belum ada riwayat tes fisik</td></tr>';
            return;
        }
        
        history.forEach(h => {
            const isSelected = h.month === selectedMonth;
            const rowClass = isSelected ? 'bg-blue-50/50' : 'hover:bg-slate-50';
            
            let resHtml = '<div class="grid grid-cols-2 md:grid-cols-4 gap-4">';
            const params = [
                { label: 'VO2 MAX', value: h.vo2max, color: 'text-blue-600' },
                { label: 'SPRINT', value: h.results ? (h.results['Sprint 20m'] || h.sprint_20m) : h.sprint_20m, unit: 's' },
                { label: 'PUSH/SIT', value: `${h.push_up || 0}/${h.sit_up || 0}` },
                { label: 'AGILITY', value: h.results ? (h.results['Agility'] || h.shuttle_run) : h.shuttle_run, unit: 's' }
            ];
            
            params.forEach(p => {
                resHtml += `
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">${p.label}</p>
                        <p class="text-sm font-black ${p.color || 'text-slate-700'}">${p.value || '-'}${p.unit || ''}</p>
                    </div>
                `;
            });
            resHtml += '</div>';

            tbody.insertAdjacentHTML('beforeend', `
                <tr class="transition-colors border-b border-slate-50 ${rowClass} group">
                    <td class="px-8 py-6 font-black text-slate-800 uppercase tracking-tight group-hover:text-blue-600 transition-colors">
                        <div class="flex items-center gap-3">
                            ${h.month}
                            ${isSelected ? '<span class="text-[9px] font-black bg-blue-600 text-white px-2 py-0.5 rounded-full uppercase tracking-widest shadow-sm shadow-blue-200">Current</span>' : ''}
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        ${resHtml}
                    </td>
                </tr>
            `);
        });
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
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderColor: '#2563eb',
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    borderWidth: 3
                }]
            },
            options: { 
                scales: { 
                    r: { 
                        min: 0, 
                        max: 5, 
                        ticks: { display: false },
                        grid: { color: '#f1f5f9' },
                        angleLines: { color: '#f1f5f9' },
                        pointLabels: { font: { size: 10, weight: '900', family: 'Nunito' }, color: '#64748b' }
                    } 
                }, 
                plugins: { legend: { display: false } } 
            }
        });
    }

    let memberStatus = "{{ $member->status }}";

    function toggleMemberStatus() {
        fetch("{{ route('member.toggle-status') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            memberStatus = data.status;

            // Toggle UI
            const toggle = document.getElementById('status-toggle');
            const knob = document.getElementById('status-knob');

            toggle.classList.remove('bg-emerald-500', 'bg-slate-400', 'shadow-[0_0_8px_rgba(52,211,153,0.8)]');
            knob.classList.remove('translate-x-5', 'translate-x-0');

            if (memberStatus === 'AKTIF') {
                toggle.classList.add('bg-emerald-500', 'shadow-[0_0_8px_rgba(52,211,153,0.8)]');
                knob.classList.add('translate-x-5');
            } else {
                toggle.classList.add('bg-slate-400');
                knob.classList.add('translate-x-0');
            }
            
            // Reload page or update other elements if needed
            // Location reload for simplicity as many elements depend on status
            window.location.reload();
        });
    }

</script>