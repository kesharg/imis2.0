

<div class="hi">
    <div class="sus sus1">
        <div class="card-header">
            {{ html_entity_decode($ss1[0]->heading ) }}
        </div>
        <div class="chart" id="ss1cContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="ss1cCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($ss1[0]->data_value) }}, '#E49B0F', 'ss1cCanva', 'ss1cContain');
            });
        </script>
    </div>






  </div>


