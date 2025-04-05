@include('layouts.dashboard.chart-card',[
    'card_title' => "Volume of waste recycled",
    'export_chart_btn_id' => "exportvolumeOfWasteRecycled",
    'canvas_id' => "volumeOfWasteRecycled"
])
@push('scripts')
<script>
var ctx = document.getElementById("volumeOfWasteRecycled");
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: [<?php echo implode(',', $volumeOfWasteRecycled['labels']); ?>],
    datasets: [
        {
            label: "Volume of waste recycled chart",
            backgroundColor: [<?php echo implode(',', $volumeOfWasteRecycled['colors']); ?>],
            hoverBackgroundColor: [<?php echo implode(',', $volumeOfWasteRecycled['colors']); ?>],
            borderWidth: 1,
            data: [<?php echo implode(',', $volumeOfWasteRecycled['values']); ?>],
        }
    ]
},
  options: {
    animation:{
      animateScale:true
    }
  }
});
document.getElementById('exportvolumeOfWasteRecycled').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#volumeOfWasteRecycled');

    //create image from dummy canvas
    var newCanvasImg = newCanvas.toDataURL("image/jpeg", 1.0);
    var a = document.createElement('a');
    a.href =newCanvas.toDataURL("image/jpeg", 1.0);

    a.download = 'Volume of waste recycled.png';

    // Trigger the download
    a.click();
      }
</script>
@endpush
