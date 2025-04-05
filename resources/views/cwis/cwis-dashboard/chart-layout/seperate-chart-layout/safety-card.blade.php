

<style>
    /* Breakpoints */
    @media screen and (max-width: 540px) {
        .chart__figure {
            flex-direction: column;
            height: auto;
        }

        .chart__caption {
            margin: 15px auto auto;
            text-align: center;
            min-width: 160px;
        }

        .chart {
            width: 100%;
            margin-right: 0;
            margin-left: 0; /* Center-align on smaller screens */
        }

        .safety {
            width: 100%;
        }
    }

    /* Fonts (Google fonts) */
    .font--barlow {
        font-family: "Barlow Condensed", sans-serif;
    }

    .font--montserrat {
        font-family: "Montserrat", sans-serif;
    }

    .color--grey {
        color: #334466;
    }

    .color--green {
        color: #01713c;
    }

    /* Values */
    .canvas-size {
        width: 160px;
        height: 50px;
    }

    .font-weight--900 {
        font-weight: 900;
    }

    .animation-time--1400ms {
        animation-duration: 1400ms;
    }

    /* Fading animation */
    @keyframes fadein {
        0% {
            opacity: 0;
        }
        40% {
            opacity: 0;
        }
        80% {
            opacity: 1;
        }
        100% {
            opacity: 1;
        }
    }

    .main {
        display: grid;
    }

    .chart {
        position: relative;
        font-weight: 500;
        margin-right: auto; /* Center-align */
        margin-left: auto; /* Center-align */
        width: 50%;

        @media screen and (max-width: 540px) {
            width: 100%;
            margin-right: 0;
            margin-left: 0; /* Center-align on smaller screens */
        }

        .chart__figure {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            height: 100%;
        }

        .chart__canvas {
            width: 160px;
            height: 140px;
        }

        .chart__caption {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-left: auto; /* Center-align */
            margin-right: auto; /* Center-align */
            font-size: 36px;
            line-height: 56px;
            height: 100%;
            width: calc(80px + 160px);
            font-family: "Barlow Condensed", sans-serif;
            color: #01713c;
            border-bottom: 1px solid #ccc;
        }

        .chart__value {
            display: grid;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: calc(40px + 160px);
            width: 160px;
            animation: fadein 1400ms;
        }

        p {
            font-size: 20px;
            margin: auto;
            font-family: "Barlow Condensed", sans-serif;
        }
    }



    /* Styles for each safety container */
    /* .safety {
        border: 1px solid rgb(231, 227, 227);
        width: 420px;
        height: 350px;
        margin: 10px 10px;
        display: grid;
        padding: 2px;
        background-color: #F4F9F7;
    } */

    .safety1, .safety2, .safety3, .safety4 {
        width: 100%;
        height: auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .sf1, .sf2, .sf3, .sf4 {
        width: calc(33.33% - 20px);
        margin-bottom: 20px;
        box-sizing: border-box;
    }

    .sf1:last-child, .sf2:last-child,.sf3:last-child, .sf4:last-child {
        margin-right: auto;
        margin-left: 2.5%;
    }

    .sf1 img, .sf2 img, .sf3 img, .sf4 img {
        width: 100%;
        height: auto;
        margin-bottom: 10px;
    }

    .safety img {
        width: 200px;
        height: 200px;
    }

    .card-header {
        width: 100%;
        text-align: left;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid #ccc;
        height: 60px;
    }

    /* heading */
    .safety h2 {
        font-size: auto;
    }

    /* paragraph */
    .safety p {
        font-size: 15px;
        text-align: center;
    }

    span {
        position: absolute;
        top: 50%;
        left: 50%;
        text-align: center;
        font-size: 30px;
        margin-left: -25px;
        margin-top: -20px;
    }

    .chart-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
</style>



<div class="hi">
<div class="safety safety1">
    <div class="card-header">{{ html_entity_decode($sf1[0]->heading ) }}</div>
    <br>
    {{-- <div class="sf1">
        <div class="chart" id="sf1Contain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf1Canva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf1[0]->data_value) }}, '#29ab87', 'sf1Canva', 'sf1Contain');
            });
        </script>
        <p>{{ html_entity_decode($sf1[0]->assmntmtrc_dtpnt ) }}</p>
    </div> --}}
    <div class="sf1">
        <div class="chart" id="sf1aContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf1aCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf1a[0]->data_value) }}, '#29ab87', 'sf1aCanva', 'sf1aContain');
            });
        </script><p>{{ html_entity_decode($sf1a[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf1">
        <div class="chart" id="sf1bContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf1bCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf1b[0]->data_value) }}, '#29ab87', 'sf1bCanva', 'sf1bContain');
            });
        </script>
        <p>{{ html_entity_decode($sf1b[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf1">
        <div class="chart" id="sf1cContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf1cCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf1c[0]->data_value) }}, '#29ab87', 'sf1cCanva', 'sf1cContain');
            });
        </script>
       <p>{{ html_entity_decode($sf1c[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf1">
        <div class="chart" id="sf1dContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf1dCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf1d[0]->data_value) }}, '#29ab87', 'sf1dCanva', 'sf1dContain');
            });
        </script>
       <p>{{ html_entity_decode($sf1d[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf1">
        <div class="chart" id="sf1eContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf1eCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf1e[0]->data_value) }}, '#29ab87', 'sf1eCanva', 'sf1eContain');
            });
        </script>
       <p>{{ html_entity_decode($sf1e[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf1">
        <div class="chart" id="sf1fContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf1fCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf1f[0]->data_value) }}, '#29ab87', 'sf1fCanva', 'sf1fContain');
            });
        </script>
       <p>{{ html_entity_decode($sf1f[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf1">
        <div class="chart" id="sf1gContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf1gCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf1g[0]->data_value) }}, '#29ab87', 'sf1gCanva', 'sf1gContain');
            });
        </script>
       <p>{{ html_entity_decode($sf1g[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
</div>

<div class="safety safety2">
    <div class="card-header">
        {{ html_entity_decode($sf2[0]->heading ) }}
    </div>
    <br>
    {{-- <div class="sf2">
         <div class="chart" id="sf2Contain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf2Canva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf2[0]->data_value) }}, '#29ab87', 'sf2Canva', 'sf2Contain');
            });
        </script>
        <p>{{ html_entity_decode($sf2[0]->assmntmtrc_dtpnt ) }}</p>
    </div> --}}
    <div class="sf2">
          <div class="chart" id="sf2aContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf2aCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf2a[0]->data_value) }}, '#29ab87', 'sf2aCanva', 'sf2aContain');
            });
        </script>
        <p>{{ html_entity_decode($sf2a[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf2">
          <div class="chart" id="sf2bContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf2bCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf2b[0]->data_value) }}, '#29ab87', 'sf2bCanva', 'sf2bContain');
            });
        </script>
        <p>{{ html_entity_decode($sf2b[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf2">

          <div class="chart" id="sf2cContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf2cCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf2c[0]->data_value) }}, '#29ab87', 'sf2cCanva', 'sf2cContain');
            });
        </script>
        <p>{{ html_entity_decode($sf2c[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
</div>

<div class="safety safety3">
    <div class="card-header">
        {{ html_entity_decode($sf3[0]->heading ) }}
    </div>
    <br>
    {{-- <div class="sf3">
        <div class="chart" id="sf3Contain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf3Canva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf3[0]->data_value) }}, '#29ab87', 'sf3Canva', 'sf3Contain');
            });
        </script>
        <p>{{ html_entity_decode($sf3[0]->assmntmtrc_dtpnt ) }}</p>
    </div> --}}
    <div class="sf3">
        <div class="chart" id="sf3aContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf3aCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf3a[0]->data_value) }}, '#29ab87', 'sf3aCanva', 'sf3aContain');
            });
        </script>
        <p>{{ html_entity_decode($sf3a[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf3">
        <div class="chart" id="sf3bContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf3bCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf3b[0]->data_value) }}, '#29ab87', 'sf3bCanva', 'sf3bContain');
            });
        </script>
        <p>{{ html_entity_decode($sf3b[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf3">

        <div class="chart" id="sf3cContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf3cCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf3c[0]->data_value) }}, '#29ab87', 'sf3cCanva', 'sf3cContain');
            });
        </script>
        <p>{{ html_entity_decode($sf3c[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf3">
         @include("cwis.cwis-dashboard.charts.seperate-chart.sf-3.sf-3e-chart")<p>{{ html_entity_decode($sf3e[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    {{-- <div class="sf3">

        <img src="img3.jpeg" alt=""><h2>Special Offer</h2><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam, optio. Lorem ipsum dolor sit amet.</p>
    </div> --}}
</div>

<div class="safety safety4">
    <div class="card-header">
        {{ html_entity_decode($sf4[0]->heading ) }}
    </div>
    <br>
    {{-- <div class="sf4">
        <div class="chart" id="sf4Contain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf4Canva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf4[0]->data_value) }}, '#29ab87', 'sf4Canva', 'sf4Contain');
            });
        </script>
        <p>{{ html_entity_decode($sf4[0]->assmntmtrc_dtpnt ) }}</p>
    </div> --}}
    <div class="sf4">
        <div class="chart" id="sf4aContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf4aCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf4a[0]->data_value) }}, '#29ab87', 'sf4aCanva', 'sf4aContain');
            });
        </script>
        <p>{{ html_entity_decode($sf4a[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf4">
        <div class="chart" id="sf4bContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf4bCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf4b[0]->data_value) }}, '#29ab87', 'sf4bCanva', 'sf4bContain');
            });
        </script>
        <p>{{ html_entity_decode($sf4b[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    <div class="sf4">

        <div class="chart" id="sf4dContain">
            <figure class="chart__figure">
                <canvas class="chart__canvas" id="sf4dCanva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                createDoughnutChart({{ html_entity_decode($sf4d[0]->data_value) }}, '#29ab87', 'sf4dCanva', 'sf4dContain');
            });
        </script>
        <p>{{ html_entity_decode($sf4d[0]->assmntmtrc_dtpnt ) }}</p>
    </div>
    {{-- <div class="sf4">

        <img src="img3.jpeg" alt=""><h2>Special Offer</h2><p>{{ html_entity_decode($sf4[0]->assmntmtrc_dtpnt ) }}</p>
    </div> --}}
</div>



<div class="safety safety5">
    <div class="card-header">
        {{ html_entity_decode($sf5[0]->heading ) }}
    </div>
    <div class="chart" id="sf5Contain">
        <figure class="chart__figure">
            <canvas class="chart__canvas" id="sf5Canva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

        </figure>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            createDoughnutChart({{ html_entity_decode($sf5[0]->data_value) }}, '#29ab87', 'sf5Canva', 'sf5Contain');
        });
    </script>
</div>

<div class="safety safety6">
    <div class="card-header">
        {{ html_entity_decode($sf6[0]->heading ) }}
    </div>
    <div class="chart" id="sf6Contain">
        <figure class="chart__figure">
            <canvas class="chart__canvas" id="sf6Canva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

        </figure>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            createDoughnutChart({{ html_entity_decode($sf6[0]->data_value) }}, '#29ab87', 'sf6Canva', 'sf6Contain');
        });
    </script>
</div>


<div class="safety safety7">
    <div class="card-header">
        {{ html_entity_decode($sf7[0]->heading ) }}
    </div>
    <div class="chart" id="sf7Contain">
        <figure class="chart__figure">
            <canvas class="chart__canvas" id="sf7Canva" width="160" height="140" aria-label="Example doughnut chart showing data as a percentage" role="img"></canvas>

        </figure>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            createDoughnutChart({{ html_entity_decode($sf7[0]->data_value) }}, '#29ab87', 'sf7Canva', 'sf7Contain');
        });
    </script>
</div>

{{-- <script>
    // chart-script.js

function createDoughnutChart(percent, color, canvasId, containerId) {

var canvas = document.getElementById(canvasId);
var container = document.getElementById(containerId);

var percentValue = percent;
var colorGreen = color;
var animationTime = '1400';

var divElement = document.createElement('div');
var domString = '<div class="chart__value"><p style="color: ' + colorGreen + '">' + percentValue + '%</p></div>';

var doughnutChart = new Chart(canvas, {
    type: 'doughnut',
    data: {
        datasets: [
            {
                data: [percentValue, 100 - percentValue],
                backgroundColor: [colorGreen],
                borderWidth: 0
            }
        ]
    },
    options: {
        cutoutPercentage: 78,
        responsive: true,
        tooltips: {
            enabled: false
        }
    }
});

Chart.defaults.global.animation.duration = animationTime;

divElement.innerHTML = domString;
container.appendChild(divElement.firstChild);
}

</script> --}}
</div>


