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
                if(tbody) tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Silakan pilih Kategori Gaya terlebih dahulu.</td></tr>';
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
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada data untuk periode ini.</td></tr>';
                return;
            }
            
            raports.forEach(r => {
                const formattedTime = `${String(Math.floor(r.value / 60)).padStart(2, '0')}:${(r.value % 60).toFixed(2).padStart(5, '0')}`;
                const row = `
                    <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                        <td class="px-5 py-3 font-bold text-slate-800 capitalize">${r.month}</td>
                        <td class="px-5 py-3 text-cyan-600 font-mono font-bold">${formattedTime}</td>
                        <td class="px-5 py-3 text-slate-600">${r.volume}m</td>
                        <td class="px-5 py-3"><span class="px-2 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">${r.intensity}%</span></td>
                        <td class="px-5 py-3 font-medium text-slate-700">${r.peaking || '-'}</td>
                        <td class="px-5 py-3 text-xs text-slate-500 italic">${r.note || '-'}</td>
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

        function updateCharts(valueData, volumeData) {
            if (typeof Chart === 'undefined') return;

            if (chartValue) chartValue.destroy();
            if (chartVolume) chartVolume.destroy();

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } }
                }
            };

            const ctx1 = document.getElementById('chartValue').getContext('2d');
            chartValue = new Chart(ctx1, {
                type: 'line',
                data: valueData,
                options: {
                    ...commonOptions,
                    elements: { line: { borderColor: '#0891b2', borderWidth: 3, tension: 0.4 }, point: { radius: 4 } },
                    scales: { y: { reverse: true } }
                }
            });

            const ctx2 = document.getElementById('chartVolume').getContext('2d');
            if(volumeData.datasets) {
                const colors = ['#0891b2', '#10b981', '#8b5cf6'];
                volumeData.datasets.forEach((ds, i) => {
                    ds.backgroundColor = colors[i % colors.length];
                    ds.borderRadius = 4;
                });
            }

            chartVolume = new Chart(ctx2, {
                type: 'bar',
                data: volumeData,
                options: {
                    ...commonOptions,
                    plugins: { legend: { display: true } }
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
                        renderRadarChart(data.radarData);
                    }
                });
        }

        function updatePhysicalTable(history, selectedMonth) {
            const tbody = document.querySelector('#phys-table tbody');
            tbody.innerHTML = history.length ? '' : '<tr><td colspan="5" class="px-4 py-10 text-center text-slate-400">Belum ada data fisik.</td></tr>';
            
            history.forEach(h => {
                const isSelected = h.month === selectedMonth;
                tbody.insertAdjacentHTML('beforeend', `
                    <tr class="transition-colors border-b border-slate-100 ${isSelected ? 'bg-rose-50/50' : 'hover:bg-slate-50'}">
                        <td class="px-4 py-4 font-bold text-slate-800 capitalize">
                            ${h.month}
                            ${isSelected ? '<span class="ml-2 text-[8px] bg-rose-600 text-white px-1.5 py-0.5 rounded-full uppercase">Selected</span>' : ''}
                        </td>
                        <td class="px-4 py-4 text-rose-600 font-black">${h.vo2max || '-'}</td>
                        <td class="px-4 py-4 text-slate-600">${h.sprint_20m || '-'}s</td>
                        <td class="px-4 py-4 text-slate-600">${h.push_up || 0}/${h.sit_up || 0}</td>
                        <td class="px-4 py-4 text-slate-600">${h.shuttle_run || '-'}s</td>
                    </tr>
                `);
            });
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
                        borderWidth: 2
                    }]
                },
                options: { 
                    scales: { r: { min: 0, max: 5, ticks: { display: false } } }, 
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

                // Badge
                const badge = document.getElementById('member-status');
                badge.innerText = memberStatus;
                badge.classList.remove('status-active', 'status-inactive');
                badge.classList.add(
                    memberStatus === 'AKTIF' ? 'status-active' : 'status-inactive'
                );

                // Toggle UI
                const toggle = document.getElementById('status-toggle');
                const knob = document.getElementById('status-knob');

                toggle.classList.remove('bg-emerald-500', 'bg-slate-300');
                knob.classList.remove('translate-x-5', 'translate-x-1');

                if (memberStatus === 'AKTIF') {
                    toggle.classList.add('bg-emerald-500');
                    knob.classList.add('translate-x-5');
                } else {
                    toggle.classList.add('bg-slate-300');
                    knob.classList.add('translate-x-1');
                }
            });
        }

    </script>