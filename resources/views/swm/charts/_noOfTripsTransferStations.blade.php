@include('layouts.dashboard.chart-card',[
    'card_title' => "Number of trips to transfer stations",
    'export_chart_btn_id' => "exportNoOfTripsTransferStations",
    'canvas_id' => "noOfTripsTransferStations"
])
@push('scripts')
<script>
var ctx = document.getElementById("noOfTripsTransferStations");
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: [<?php echo implode(',', $noOfTripsTransferStations['labels']); ?>],
    datasets: [
        {
            label: "Transfer Stations chart",
            backgroundColor: [<?php echo implode(',', $noOfTripsTransferStations['colors']); ?>],
            hoverBackgroundColor: [<?php echo implode(',', $noOfTripsTransferStations['colors']); ?>],
            borderWidth: 1,
            data: [<?php echo implode(',', $noOfTripsTransferStations['values']); ?>],
        }
    ]
},
  options: {
    animation:{
      animateScale:true
    }
  }
});
document.getElementById('exportNoOfTripsTransferStations').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#noOfTripsTransferStations');

    //create image from dummy canvas
    var newCanvasImg = newCanvas.toDataURL("image/jpeg", 1.0);
    var a = document.createElement('a');
    a.href =newCanvas.toDataURL("image/jpeg", 1.0);

    a.download = 'Containment Types.png';

    // Trigger the download
    a.click();
      }
</script>
@endpush
