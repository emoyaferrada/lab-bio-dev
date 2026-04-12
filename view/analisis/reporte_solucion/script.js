document.addEventListener('DOMContentLoaded', function() {
    // PDF Download Logic
    document.getElementById('download-pdf').addEventListener('click', function() {
        const element = document.getElementById('report-container');
        const opt = {
            margin:       5,
            filename:     'reporte_lab_bio.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    });

    // Chart.js Logic
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            title: { display: true }
        },
        scales: {
            y: { beginAtZero: true }
        }
    };

    const createChart = (ctxId, label, dataPoints, color,dataPoints2, color2,dataPoints3, color3) => {
        const ctx = document.getElementById(ctxId).getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['07-11-24', '10-12-24', '15-01-25'],
                datasets: [{
                    label: label,
                    data: dataPoints,
                    borderColor: color,
                    backgroundColor: color,
                    tension: 0.1,
                    pointRadius: 4
                },
                {
                    label: label,
                    data: dataPoints2,
                    borderColor: color2,
                    backgroundColor: color2,
                    tension: 0.1,
                    pointRadius: 4
                },
                {
                    label: label,
                    data: dataPoints3,
                    borderColor: color3,
                    backgroundColor: color3,
                    tension: 0.1,
                    pointRadius: 4
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    title: { display: true, text: label }
                }
            }
        });
    };

    // Data matching the screenshot roughly
    createChart('chart-n', 'Nitrógeno (mg/l)', [4.3, 9.8, 18.7], '#4472c4', [18, 18, 18], '#a5a5a5', [48, 48, 48], '#ed7d31');
    
    createChart('chart-p', 'Fósforo (mg/l)', [2.5, 2.7, 15.2], '#4472c4', [1, 1, 1], '#a5a5a5', [4, 4, 4], '#ed7d31');
    createChart('chart-k', 'Potasio (mg/l)', [14.3, 20.7, 33.3], '#4472c4', [18, 18, 18], '#a5a5a5', [60, 60, 60], '#ed7d31');
    createChart('chart-ca', 'Calcio (mg/l)', [22.6, 28.8, 19.2], '#4472c4', [11, 11, 11], '#a5a5a5', [45, 45, 45], '#ed7d31');
});
