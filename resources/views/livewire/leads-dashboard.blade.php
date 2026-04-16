<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold clr-text-primary">Leads Dashboard</h2>
            <p class="text-sm text-gray-500">Track sent leads and manual replies by bulk campaign.</p>
        </div>
        <a href="{{ route('compose') }}" class="btn clr-bg-accent text-base-100">+ New Bulk Email</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs uppercase tracking-wide text-gray-500">Total Sent</p>
            <h3 class="text-3xl font-bold clr-text-primary mt-2">{{ number_format($totalSent) }}</h3>
            <p class="text-xs text-gray-500 mt-1">Leads included across all bulk campaigns</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs uppercase tracking-wide text-gray-500">No Reply</p>
            <h3 class="text-3xl font-bold text-amber-500 mt-2">{{ number_format($totalNoReply) }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ $totalSent > 0 ? round(($totalNoReply / $totalSent) * 100) : 0 }}% of total sent</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs uppercase tracking-wide text-gray-500">Reply Rate</p>
            <h3 class="text-3xl font-bold text-emerald-600 mt-2">{{ $replyRate }}%</h3>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($totalReplied) }} leads replied</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4"
         wire:ignore
         x-data="leadDashboardChart(@js($chartData))"
         x-init="initChart()">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold clr-text-primary">Email sent vs replies (last 8 weeks)</h3>
            <span class="text-xs text-gray-500">Global trend across all campaigns</span>
        </div>
        <div class="h-72">
            <canvas id="leadDashboardChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold clr-text-primary">All Sends</h4>
                <select wire:model.live="selectedCampaignId" class="select select-sm select-bordered w-52 h-10">
                    <option value="all">All Bulk Emails</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">
                            {{ \Illuminate\Support\Str::limit($campaign->subject, 28) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="overflow-auto max-h-80">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Campaign</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allSends as $recipient)
                            <tr>
                                <td class="text-xs">{{ $recipient->email }}</td>
                                <td class="text-xs">{{ \Illuminate\Support\Str::limit($recipient->campaign->subject ?? 'N/A', 20) }}</td>
                                <td>
                                    <span class="badge badge-xs {{ $recipient->status === 'replied' ? 'badge-success' : 'badge-warning' }} p-3">
                                        {{ $recipient->status === 'replied' ? 'Replied' : 'No Reply' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-xs text-gray-400 py-4">No sent leads.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold clr-text-primary">No Reply</h4>
                <select wire:model.live="selectedCampaignId" class="select select-sm select-bordered w-52 h-10">
                    <option value="all">All Bulk Emails</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">
                            {{ \Illuminate\Support\Str::limit($campaign->subject, 28) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="overflow-auto max-h-80">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Campaign</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($noReplySends as $recipient)
                            <tr>
                                <td class="text-xs">{{ $recipient->email }}</td>
                                <td class="text-xs">{{ \Illuminate\Support\Str::limit($recipient->campaign->subject ?? 'N/A', 18) }}</td>
                                <td>
                                    <button wire:click="markAsReplied({{ $recipient->id }})"
                                            class="btn btn-xs clr-bg-accent text-base-100 p-2">
                                        Reply
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-xs text-gray-400 py-4">No pending replies.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold clr-text-primary">Replied</h4>
                <select wire:model.live="selectedCampaignId" class="select select-sm select-bordered w-52 h-10">
                    <option value="all">All Bulk Emails</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">
                            {{ \Illuminate\Support\Str::limit($campaign->subject, 28) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="overflow-auto max-h-80">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Campaign</th>
                            <th>Moved</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($repliedSends as $recipient)
                            <tr>
                                <td class="text-xs">{{ $recipient->email }}</td>
                                <td class="text-xs">{{ \Illuminate\Support\Str::limit($recipient->campaign->subject ?? 'N/A', 18) }}</td>
                                <td class="text-xs text-emerald-600 font-medium">Replied</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-xs text-gray-400 py-4">No replied leads yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    let leadDashboardChartJsLoader;

    function ensureLeadDashboardChartJs() {
        if (window.Chart) {
            return Promise.resolve();
        }

        if (leadDashboardChartJsLoader) {
            return leadDashboardChartJsLoader;
        }

        leadDashboardChartJsLoader = new Promise((resolve, reject) => {
            const existingScript = document.querySelector('script[data-chartjs="lead-dashboard"]');
            if (existingScript) {
                existingScript.addEventListener('load', () => resolve());
                existingScript.addEventListener('error', () => reject(new Error('Failed to load Chart.js')));
                return;
            }

            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.async = true;
            script.dataset.chartjs = 'lead-dashboard';
            script.onload = () => resolve();
            script.onerror = () => reject(new Error('Failed to load Chart.js'));
            document.head.appendChild(script);
        });

        return leadDashboardChartJsLoader;
    }

    function leadDashboardChart(initialData) {
        return {
            chart: null,
            chartData: initialData,
            async initChart() {
                const canvas = document.getElementById('leadDashboardChart');
                if (!canvas) {
                    return;
                }

                await ensureLeadDashboardChartJs();

                if (this.chart) {
                    this.chart.destroy();
                    this.chart = null;
                }

                this.chart = new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: this.chartData.labels,
                        datasets: [
                            {
                                label: 'Sent',
                                data: this.chartData.sent,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.08)',
                                tension: 0.35,
                                fill: false,
                                borderDash: [6, 4],
                                borderWidth: 2,
                                pointRadius: 2,
                                pointHoverRadius: 4,
                            },
                            {
                                label: 'Replied',
                                data: this.chartData.replied,
                                borderColor: '#16a34a',
                                backgroundColor: 'rgba(22, 163, 74, 0.15)',
                                tension: 0.35,
                                fill: false,
                                borderWidth: 2,
                                pointRadius: 2,
                                pointHoverRadius: 4,
                            },
                            {
                                label: 'No Reply',
                                data: this.chartData.noReply,
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245, 158, 11, 0.22)',
                                tension: 0.35,
                                fill: false,
                                borderWidth: 3,
                                pointRadius: 3,
                                pointHoverRadius: 5,
                                pointBackgroundColor: '#f59e0b',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 1,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } }
                        }
                    }
                });
            },
            updateChart(data) {
                this.chartData = data;
                if (!this.chart) {
                    this.initChart();
                    return;
                }

                this.chart.data.labels = data.labels;
                this.chart.data.datasets[0].data = data.sent;
                this.chart.data.datasets[1].data = data.replied;
                this.chart.data.datasets[2].data = data.noReply;
                this.chart.update();
            }
        };
    }
</script>
