@include('layouts.dashboard.chart-card',[
    'card_title' => "Inclusion",
    'export_chart_btn_id' => "exportinclusionCharts",
    'canvas_id' => "inclusionCharts"
])

@push('scripts')
 <script>
var ctx = document.getElementById("inclusionCharts");
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php echo "'" . implode("', '", $inclusionCharts['labels']) ."'"; ?>],
    datasets: [
        {
          type: "bar",
            label: "Targets",
            backgroundColor: "rgba(251, 176, 64,0.8)",
            hoverBackgroundColor: "rgba(251, 176, 64,0.9)",
            data: [<?php echo implode(',', $inclusionCharts['target_values']); ?>],
        },
        {
          type: "bar",
            label: "Achievements",
            backgroundColor: "rgba(153, 202, 60,0.8)",
            hoverBackgroundColor: "rgba(153, 202, 60,0.9)",
            fill: false,
            data: [<?php echo implode(',', $inclusionCharts['achievement_values']); ?>],
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
                beginAtZero: true
            }
        }]
    }
  }
});
document.getElementById('exportinclusionCharts').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#inclusionCharts');

    //create image from dummy canvas
    var newCanvasImg = newCanvas.toDataURL("image/png", 1.0);
    var a = document.createElement('a');
    a.href =newCanvas.toDataURL("image/png", 1.0);
   
    a.download = 'PPE Compliance.png';

    // Trigger the download
    a.click();
      }
</script> 
@endpush 