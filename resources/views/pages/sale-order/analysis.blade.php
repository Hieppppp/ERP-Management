<!-- <div class="" style="width: 800px; height: 400px;">
    <canvas id="revenueChart"></canvas>
</div>
<script src="path/to/chartjs/dist/chart.umd.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Doanh thu (VND)',
                data: @json($chartData['data']),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    })
</script> -->
