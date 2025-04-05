@include('layouts.dashboard.chart-card',[
    'card_title' => "Emptying Service By Year In Last 5 Years",
    'export_chart_btn_id' => "exportemptyingServiceByTypeYearChart",
    'canvas_id' => "emptyingServiceByTypeYearChart"
])
@push('scripts')
<script>
var ctx = document.getElementById("emptyingServiceByTypeYearChart");
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php echo implode(',', $emptyingServiceByTypeYearChart['labels']); ?>],
    datasets: [
        @foreach($emptyingServiceByTypeYearChart['datasets'] as $dataset)
        {
            label: <?php echo $dataset['label']; ?>,
            backgroundColor: <?php echo $dataset['color']; ?>,
            data: [<?php echo implode(',', $dataset['data']); ?>],
        },
        @endforeach
    ]
},
  options: {
    animation:{
      animateScale:true
    },
    responsive: true,
      legend: {
         display: true,
         position: 'bottom',
         align: 'start',
         labels: {
              boxWidth: 10
          }
      },
    scales: {
        xAxes: [{
            stacked: true,
            ticks: {
                beginAtZero: true
            }
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
        mode: 'index'
    }
  }
});
document.getElementById('exportemptyingServiceByTypeYearChart').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#emptyingServiceByTypeYearChart');

    //create image from dummy canvas
    var newCanvasImg = newCanvas.toDataURL("image/png", 1.0);
    var a = document.createElement('a');
    a.href =newCanvas.toDataURL("image/png", 1.0);

    a.download = 'Emptying service by Year.png';

    // Trigger the download
    a.click();
      }
</script>
@endpush
