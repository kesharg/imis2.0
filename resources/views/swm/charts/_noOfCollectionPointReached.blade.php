@include('layouts.dashboard.chart-card',[
    'card_title' => "No. of collection points reached",
    'export_chart_btn_id' => "exportNoOfCollectionPointReached",
    'canvas_id' => "noOfCollectionPointReached"
])
@push('scripts')
<script>
var ctx = document.getElementById("noOfCollectionPointReached");
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php echo implode(',', $noOfCollectionPointReached['labels']); ?>],
    datasets: [
        {
            label: "No. of collection points reached",
            backgroundColor: "rgba(90, 155, 212,0.2)",
            borderColor: "rgba(90, 155, 212,1)",
            borderWidth: 1,
            hoverBackgroundColor: "rgba(90, 155, 212,0.4)",
            hoverBorderColor: "rgba(90, 155, 212,1)",
            data: [<?php echo implode(',', $noOfCollectionPointReached['values']); ?>],
        }
    ]
},
  options: {
    animation:{
      animateScale:true
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
document.getElementById('exportNoOfCollectionPointReached').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#noOfCollectionPointReached');

    //create image from dummy canvas
    var newCanvasImg = newCanvas.toDataURL("image/jpeg", 1.0);
    var a = document.createElement('a');
    a.href =newCanvas.toDataURL("image/jpeg", 1.0);

    a.download = 'Building Structures by Ward.png';

    // Trigger the download
    a.click();
      }
</script>
@endpush
