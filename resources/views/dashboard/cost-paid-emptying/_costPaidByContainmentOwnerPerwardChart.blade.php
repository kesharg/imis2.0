<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
@include('layouts.dashboard.chart-card',[
    'card_title' => " Sum of Revenue Collected (Emptying Service)",
    'export_chart_btn_id' => "exportcostPaidByContainmentOwnerPerwardChart",
    'canvas_id' => "costPaidByContainmentOwnerPerwardChart"
])
@push('scripts')

<style>
#costPaidByContainmentOwnerPerwardChart{

height:600px !important;
  

}
</style>
<script>
var ctx = document.getElementById("costPaidByContainmentOwnerPerwardChart");
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php echo implode(',', $costPaidByContainmentOwnerPerwardChart['labels']); ?>],
    datasets: [
        {
            label: "Sum of Revenue Collected (Emptying Service)",
            backgroundColor: "rgba(54, 162, 235,0.5)",
            //borderColor: "rgba(90, 155, 212,1)",
            //borderWidth: 1,
            hoverBackgroundColor: "rgba(54, 162, 235,0.7)",
            //hoverBorderColor: "rgba(90, 155, 212,1)",
            data: [<?php echo implode(',', $costPaidByContainmentOwnerPerwardChart['values']); ?>],
        }
    ]
},
  options: {
    responsive: true,
    maintainAspectRatio: false,
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
        display: true,
        labelString: 'Wards',
        //fontSize: 10,
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
    }
  }
});
document.getElementById('exportcostPaidByContainmentOwnerPerwardChart').addEventListener("click", downloadIMG);
  //donwload pdf from original canvas
  function downloadIMG() {
    var newCanvas = document.querySelector('#costPaidByContainmentOwnerPerwardChart');

    //create image from dummy canvas
    var newCanvasImg = newCanvas.toDataURL("image/png", 1.0);
    var a = document.createElement('a');
    a.href =newCanvas.toDataURL("image/png", 1.0);

    a.download = 'Cost Paid for Emptying Services.png';

    // Trigger the download
    a.click();
      }
</script>
@endpush
