@include('layouts.dashboard.chart-card',[
    'card_title' => "No of households served (wrt collection points serving households)",
    'export_chart_btn_id' => "exportNoOfCollectionPointHouseHoldServed",
    'canvas_id' => "noOfCollectionPointHouseHoldServed"
])
@push('scripts')
<script>
var ctx = document.getElementById("noOfCollectionPointHouseHoldServed");
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php echo implode(',', $noOfCollectionPointHouseHoldServed['labels']); ?>],
    datasets: [
        {
            label: "No of households served (wrt collection points serving households)",
            backgroundColor: "rgba(90, 155, 212,0.2)",
            borderColor: "rgba(90, 155, 212,1)",
            borderWidth: 1,
            hoverBackgroundColor: "rgba(90, 155, 212,0.4)",
            hoverBorderColor: "rgba(90, 155, 212,1)",
            data: [<?php echo implode(',', $noOfCollectionPointHouseHoldServed['values']); ?>],
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
document.getElementById('exportNoOfCollectionPointHouseHoldServed').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#noOfCollectionPointHouseHoldServed');

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
