@include('layouts.dashboard.chart-card',[
    'card_title' => "Application Response",
    'export_chart_btn_id' => "exportapplicationResponseEfficiency",
    'canvas_id' => "applicationResponseEfficiencyCharts"
])
@push('scripts')

 <script>
var ctx = document.getElementById("applicationResponseEfficiencyCharts");
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php echo "'" . implode("', '", $applicationResponseEfficiencyCharts['labels']) . "'"; ?>],
    datasets: [
        {
          type: "bar",
            label: "Targets",
            backgroundColor: "rgba(251, 176, 64,0.8)",
            hoverBackgroundColor: "rgba(251, 176, 64,0.9)",
            data: [<?php echo implode(',', $applicationResponseEfficiencyCharts['target_values']); ?>],
        },
        {
          type: "bar",
            label: "Achievements",
            backgroundColor: "rgba(153, 202, 60,0.8)",
            hoverBackgroundColor: "rgba(153, 202, 60,0.9)",
            data: [<?php echo implode(',', $applicationResponseEfficiencyCharts['achievement_values']); ?>],
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
        xAxes: [{
            // barPercentage: 0.6,
            // categoryPercentage: 0.7,
        }],
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    }
  }
});
document.getElementById('exportapplicationResponseEfficiency').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#applicationResponseEfficiencyCharts');

    //create image from dummy canvas
    var newCanvasImg = newCanvas.toDataURL("image/png", 1.0);
    var a = document.createElement('a');
    a.href =newCanvas.toDataURL("image/png", 1.0);

    a.download = 'Application Response Efficiency.png';

    // Trigger the download
    a.click();
      }
</script>
@endpush

