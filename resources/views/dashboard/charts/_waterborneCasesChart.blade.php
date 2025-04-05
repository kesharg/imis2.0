@include('layouts.dashboard.chart-card',[
    'card_title' => "Waterborne Cases",
    'export_chart_btn_id' => "exportwaterborneCasesChart",
    'canvas_id' => "waterborneCasesChart"
])
@push('scripts')
    <script>
        var ctx = document.getElementById("waterborneCasesChart");
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [<?php echo implode(',', $waterborneCasesChart['labels']); ?>],
                datasets: [
                    {
                        label: "Waterborne Cases",
                        borderColor: "rgba(54, 162, 235,0.5)",
                        hoverBackgroundColor: "rgba(54, 162, 235,0.7)",
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)', // Point color

                        data: [<?php echo implode(',', $waterborneCasesChart['values']); ?>],
                        fill: false,
                        cubicInterpolationMode: 'monotone' // Use 'monotone' for smooth curves
                    }
                ]
            },
            options: {

                animation:{
                    animateScale:true
                },
                responsive: true,
                legend: {
                   labels: {
                        boxWidth: 10
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            userCallback: function(label, index, labels) {
                     // when the floored value is the same as the value we have a whole number
                     if (Math.floor(label) === label) {
                         return label;
                     }

                 }
                        }
                    }]
                }
            }
        });
        document.getElementById('exportwaterborneCasesChart').addEventListener("click", downloadIMG);
        //donwload pdf from original canvas
        function downloadIMG() {
            var newCanvas = document.querySelector('#waterborneCasesChart');

            //create image from dummy canvas
            var newCanvasImg = newCanvas.toDataURL("image/png", 1.0);
            var a = document.createElement('a');
            a.href =newCanvas.toDataURL("image/png", 1.0);

            a.download = 'Waterborne Cases.png';

            // Trigger the download
            a.click();
        }
    </script>
@endpush
