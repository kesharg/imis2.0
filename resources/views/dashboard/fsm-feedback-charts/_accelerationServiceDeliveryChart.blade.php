@include('layouts.dashboard.chart-card',[
    'card_title' => "Acceleration/Efficiency Of Service Delivery",
    'export_chart_btn_id' => "exportaccelerationServiceDeliveryChart",
    'canvas_id' => "accelerationServiceDeliveryChart"
])
@push('scripts')
<script>
var ctx = document.getElementById("accelerationServiceDeliveryChart");
var myChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: [<?php echo implode(',', $accelerationServiceDeliveryChart['labels']); ?>],
    datasets: [
        {
            label: "Building Structures by building use",
            backgroundColor: [<?php echo implode(',', $accelerationServiceDeliveryChart['colors']); ?>],
            borderColor: [<?php echo implode(',', $accelerationServiceDeliveryChart['borderColor']); ?>],
            hoverBackgroundColor: [<?php echo implode(',', $accelerationServiceDeliveryChart['hoverBackgroundColor']); ?>],
            hoverBorderColor: [<?php echo implode(',', $accelerationServiceDeliveryChart['hoverBorderColor']); ?>],
            borderWidth: 1,
            data: [<?php echo implode(',', $accelerationServiceDeliveryChart['values']); ?>],
        }
    ]
},
  options: {
    animation:{
      animateScale:true
    }
  }
});
document.getElementById('exportaccelerationServiceDeliveryChart').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#accelerationServiceDeliveryChart');

    //create image from dummy canvas
    var newCanvasImg = newCanvas.toDataURL("image/png", 1.0);
    var a = document.createElement('a');
    a.href =newCanvas.toDataURL("image/png", 1.0);
    a.download = 'Acceleration/Efficiency of Service Delivery.png';

    // Trigger the download
    a.click();
      }
</script>
@endpush
