@include('layouts.dashboard.chart-card',[
    'card_title' => "Total volume of waste flow in Landfill Sites",
    'export_chart_btn_id' => "exportLandFillSitesChart",
    'canvas_id' => "landFillSitesChart"
])
@push('scripts')
<script>
var ctx = document.getElementById("landFillSitesChart");
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: [<?php echo implode(',', $landFillSitesChart['labels']); ?>],
    datasets: [
        {
            label: "Transfer Stations chart",
            backgroundColor: [<?php echo implode(',', $landFillSitesChart['colors']); ?>],
            hoverBackgroundColor: [<?php echo implode(',', $landFillSitesChart['colors']); ?>],
            borderWidth: 1,
            data: [<?php echo implode(',', $landFillSitesChart['values']); ?>],
        }
    ]
},
  options: {
    animation:{
      animateScale:true
    }
  }
});
document.getElementById('exportLandFillSitesChart').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#landFillSitesChart');

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
