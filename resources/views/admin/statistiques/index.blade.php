<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Dashboard Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
                        Tableau de Bord - Statistiques
                    </h1>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-calendar mr-1"></i>
                    {{ date('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistiques générales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-ticket-alt text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Billets Vendus</dt>
                                <dd class="text-3xl font-bold text-gray-900">{{ number_format($totalBillets) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Revenu Total</dt>
                                <dd class="text-3xl font-bold text-gray-900">{{ number_format($revenuTotal, 2, ',', ' ') }} <span class="text-sm font-normal text-gray-500">DH</span></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-calendar-check text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Réservations</dt>
                                <dd class="text-3xl font-bold text-gray-900">{{ number_format($totalReservations) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Graphique des revenus par événement -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
                    Revenus par Événement
                </h3>
                <div class="h-64">
                    <canvas id="eventRevenueChart"></canvas>
                </div>
            </div>

            <!-- Graphique des billets par événement -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-pie mr-2 text-green-600"></i>
                    Répartition des Billets
                </h3>
                <div class="h-64">
                    <canvas id="ticketDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tableaux -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Statistiques par événement -->
            <div class="bg-white shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-list mr-2 text-indigo-600"></i>
                        Performance par Événement
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Événement</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Billets</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenu</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($statsParEvenement as $event)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $event->titre }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $event->billets_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $event->billets_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($event->revenu_total, 2, ',', ' ') }} DH
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Aucune donnée disponible
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top événements -->
            <div class="bg-white shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-trophy mr-2 text-yellow-600"></i>
                        Top 5 Événements Populaires
                    </h3>
                </div>
                <div class="p-6">
                    @forelse ($topEvenements as $index => $event)
                        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $event->titre }}</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                <span class="font-semibold">{{ $event->total_billets }}</span> billets
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-info-circle text-2xl mb-2"></i>
                            <p>Aucun événement avec des billets vendus</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @if($statsParMois->count() > 0)
        <!-- Graphique mensuel -->
        <div class="mt-8 bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-line mr-2 text-purple-600"></i>
                Évolution des Ventes (12 derniers mois)
            </h3>
            <div class="h-64">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
        @endif
    </div>

    <script>
        // Configuration des couleurs
        const colors = {
            primary: '#3B82F6',
            success: '#10B981',
            warning: '#F59E0B',
            danger: '#EF4444',
            info: '#6366F1'
        };

        // Graphique des revenus par événement
        const eventLabels = {!! json_encode($statsParEvenement->pluck('titre')->take(10)) !!};
        const eventRevenue = {!! json_encode($statsParEvenement->pluck('revenu_total')->take(10)) !!};

        if (eventLabels.length > 0) {
            const eventCtx = document.getElementById('eventRevenueChart').getContext('2d');
            new Chart(eventCtx, {
                type: 'bar',
                data: {
                    labels: eventLabels,
                    datasets: [{
                        label: 'Revenu (DH)',
                        data: eventRevenue,
                        backgroundColor: colors.primary,
                        borderRadius: 4,
                        maxBarThickness: 50
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => value + ' DH'
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45
                            }
                        }
                    }
                }
            });
        }

        // Graphique en secteurs des billets
        const ticketData = {!! json_encode($statsParEvenement->where('billets_count', '>', 0)->pluck('billets_count')) !!};
        const ticketLabels = {!! json_encode($statsParEvenement->where('billets_count', '>', 0)->pluck('titre')) !!};

        if (ticketData.length > 0) {
            const ticketCtx = document.getElementById('ticketDistributionChart').getContext('2d');
            new Chart(ticketCtx, {
                type: 'doughnut',
                data: {
                    labels: ticketLabels,
                    datasets: [{
                        data: ticketData,
                        backgroundColor: [
                            colors.primary,
                            colors.success,
                            colors.warning,
                            colors.danger,
                            colors.info,
                            '#8B5CF6',
                            '#EC4899',
                            '#14B8A6'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        @if($statsParMois->count() > 0)
        // Graphique mensuel
        const monthlyData = {!! json_encode($statsParMois->reverse()->values()) !!};
        const monthlyLabels = monthlyData.map(item => item.annee + '-' + String(item.mois).padStart(2, '0'));
        const monthlyTickets = monthlyData.map(item => item.nombre_billets);
        const monthlyRevenue = monthlyData.map(item => item.revenu);

        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Billets vendus',
                    data: monthlyTickets,
                    borderColor: colors.success,
                    backgroundColor: colors.success + '20',
                    tension: 0.4,
                    yAxisID: 'y'
                }, {
                    label: 'Revenu (DH)',
                    data: monthlyRevenue,
                    borderColor: colors.warning,
                    backgroundColor: colors.warning + '20',
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Billets'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Revenu (DH)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                }
            }
        });
        @endif
    </script>
</body>
</html>