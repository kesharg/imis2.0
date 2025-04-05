@include('layouts.dashboard.chart-card',[
    'card_title' => "Sanitation Systems",
    'export_chart_btn_id' => "exportsanitationSystemsChart",
    'canvas_id' => "sanitationSystemsChart"
])
@push('scripts')
<script>
var ctx = document.getElementById("sanitationSystemsChart");
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php echo implode(',', $sanitationSystemsChart['labels']); ?>],
    datasets: [
        {
            label: "No. of buildings",
            backgroundColor: "rgba(54, 162, 235,0.5)",
            hoverBackgroundColor: "rgba(54, 162, 235,0.7)",
            data: [<?php echo implode(',', $sanitationSystemsChart['values']); ?>],
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

scaleLabel: {

            },
}],
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
document.getElementById('exportsanitationSystemsChart').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#sanitationSystemsChart');

    //create image from dummy canvas
    var newCanvasImg = newCanvas.toDataURL("image/png", 1.0);
    var a = document.createElement('a');
    a.href =newCanvas.toDataURL("image/png", 1.0);

    a.download = 'Building Structures .png';

    // Trigger the download
    a.click();
      }
</script>
@endpush
