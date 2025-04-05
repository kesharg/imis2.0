@include('layouts.dashboard.chart-card',[
    'card_title' => "Total volume of waste flow in Transfer Station",
    'export_chart_btn_id' => "exportTransferStationsChart",
    'canvas_id' => "transferStationChart"
])
@push('scripts')
<script>
var ctx = document.getElementById("transferStationChart");
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: [<?php echo implode(',', $transferStationChart['labels']); ?>],
    datasets: [
        {
            label: "Transfer Stations chart",
            backgroundColor: [<?php echo implode(',', $transferStationChart['colors']); ?>],
            hoverBackgroundColor: [<?php echo implode(',', $transferStationChart['colors']); ?>],
            borderWidth: 1,
            data: [<?php echo implode(',', $transferStationChart['values']); ?>],
        }
    ]
},
  options: {
    animation:{
      animateScale:true
    }
  }
});
document.getElementById('exportTransferStationsChart').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#transferStationChart');

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
