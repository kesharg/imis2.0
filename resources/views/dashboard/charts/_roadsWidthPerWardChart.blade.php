<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024) -->
@include('layouts.dashboard.chart-card',[
    'card_title' => "Road Length by Width",
    'export_chart_btn_id' => "exportroadWidthPerWardChart",
    'canvas_id' => "roadWidthPerWardChart"
])
@push('scripts')
<script>
var ctx = document.getElementById("roadWidthPerWardChart");
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php echo implode(',', $roadWidthPerWardChart['labels']); ?>],
    datasets: [
        @foreach($roadWidthPerWardChart['datasets'] as $dataset)
        {
            label: <?php echo $dataset['label']; ?>,
            backgroundColor: <?php echo $dataset['color']; ?>,
            data: [<?php echo implode(',', $dataset['data']); ?>],
            values:[<?php echo implode(',', $dataset['value']); ?>]
        },
        @endforeach
    ]
},
  options: {
    animation:{
      animateScale:true
    },
    scales: {
      xAxes: [{
        stacked: true,
        ticks: {
                beginAtZero: true
            },
            scaleLabel: {
                            display: true,
                            labelString: 'Wards'
                        },
      }],
      yAxes: [{
        stacked: true,
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
    },
    tooltips: {
        mode: 'index',
        callbacks: {
            label: function (tooltipItem, data) {
                var allData = data.datasets[tooltipItem.datasetIndex].data;
                var allValues = data.datasets[tooltipItem.datasetIndex].values;
                var tooltipLabel = data.datasets[tooltipItem.datasetIndex].label;
                var tooltipData = allData[tooltipItem.index];
                var tooltipValue = allValues[tooltipItem.index];
                return tooltipLabel + ": " +tooltipValue;
            },
        }
    }
  }
});
document.getElementById('exportroadWidthPerWardChart').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#roadWidthPerWardChart');

    //create image from dummy canvas
    var newCanvasImg = newCanvas.toDataURL("image/png", 1.0);
    var a = document.createElement('a');
    a.href =newCanvas.toDataURL("image/png", 1.0);

    a.download = 'Road Length by Width.png';

    // Trigger the download
    a.click();
      }
</script>
@endpush
