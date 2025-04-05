<!-- Last Modified Date: 16-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2024) -->
@extends('layouts.dashboard')
@section('title', 'CWIS Dashboard')
@section('content')
<style>
        h1 {
            margin: 0;
            font-size: 36px;
            width: 100%;

        }
        .tabs {
            display: flex;
            flex-wrap: wrap;
            max-width: 100%;
            margin-bottom: 20px;
        }
        .card-header {
            width: 100%;
            text-align: left;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
        }
        .input {
            position: absolute;
            opacity: 0;
        }
        .label {
            width: 100%;
            padding: 20px 30px;
            cursor: pointer;
            font-weight: bold;
            font-size: 18px;
            color: #7f7f7f;
            transition: background 0.1s, color 0.1s;
            height: 50%;
        }
        .label:hover {
            background: #d8d8d8;
        }
        .label:active {
            background: #ccc;
        }
        .input:focus+.label {
            z-index: 1;
        }
        .input:checked+.label {
            background: #fff;
            color: #000;
        }

        @media (min-width: 600px) {
            .label {
                width: auto;
            }
        }
        .panel {
            display: none;
            padding: 20px 30px 30px;
            width: 100%;
        }
        @media (min-width: 600px) {
            .panel {
                display: none;
                padding: 20px 30px 30px;
                width: 100%;
                order: 99;
            }
        }
        .input:checked+.label+.panel {
            display: block;
        }
        @media (max-width: 600px) {
            .input:checked+.label+.panel {
                display: none;
            }
        }
        .select-dropdown,
        .select-dropdown * {
            margin-left: 10px;
            padding: 0;
            position: relative;
            box-sizing: border-box;
            height: 50%;
            margin-top: 1%;
            font-weight: bold;
            color: #424242;
            font-family: 'Bree Serif', serif;
            border: 1px #1d6086;
            border-style: groove;
        }
        .select-dropdown {
            position: relative;
            border-radius: 4px;
        }
        .select-dropdown select {
            font-size: 1rem;
            font-weight: normal;
            max-width: 100%;
            padding: 8px 24px 8px 10px;
            border: none;
            background-color: transparent;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        .select-dropdown select:active,
        .select-dropdown select:focus {
            outline: none;
            box-shadow: none;
        }
        .select-dropdown:after {
            content: "";
            position: absolute;
            top: 50%;
            right: 8px;
            width: 0;
            height: 0;
            margin-top: -2px;
            border-top: 5px solid #aaa;
            border-right: 5px solid transparent;
            border-left: 5px solid transparent;
        }
        .buttons-container {
            margin-left: auto;
            display: flex;
        }
        .export-button,.pdf {
            margin-top: 8%;
            height: 40px;
            padding: 5px;
            font-size: 15px;
            cursor: pointer;
            background: #17A1B7;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .export-button:hover,
        .pdf:hover{
            background: rgb(6, 110, 126);
        }
        .export-button,
         .pdf {
           margin-right: 10px;
         }
         .hi {
            display: flex;
            flex-wrap: wrap;

        }
</style>
<script>/**
 * Creates a doughnut chart with the given percentage value and color, and appends it to a specified container.
 *
 * @param {number} percent - The percentage value to represent in the chart.
 * @param {string} color - The color of the chart.
 * @param {string} canvasId - The ID of the canvas element to render the chart.
 * @param {string} containerId - The ID of the container element to append the chart's value display.
 * @returns {void}
 */
function createDoughnutChart(percent, color, canvasId, containerId) {
    // Retrieve canvas and container elements
    var canvas = document.getElementById(canvasId);
    var container = document.getElementById(containerId);

    // Ensure percent value is within bounds
    var percentValue = percent;
    var colorGreen = color;
    var animationTime = '1400';
    if (percent > 100) {
        percentValue = 100;
    }

    // Create a div element to display the chart's value
    var divElement = document.createElement('div');
    var domString = '<div class="chart__value"><p style="color: ' + colorGreen + '">' + percent + '%</p></div>';

    // Set data values for the chart
    var dataValues = percentValue > 100 ? [100, 0] : [percentValue, 100 - percentValue];

    // Create the doughnut chart using Chart.js library
    var doughnutChart = new Chart(canvas, {
        type: 'doughnut',
        data: {
            datasets: [
                {
                    data: dataValues,
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

    // Set animation duration for the chart
    Chart.defaults.global.animation.duration = animationTime;

    // Append the chart's value display to the container
    divElement.innerHTML = domString;
    container.appendChild(divElement.firstChild);
} </script>
    <div class="tabs">
            <input class="input" name="tabs" type="radio" id="tab-1" checked="checked" />
            <label class="label" for="tab-1">All</label>
            <div class="panel">
                <h1>Equity</h1>
                @include('cwis.cwis-dashboard.chart-layout.equity-layout')
                <h1>Safety</h1>
                @include('cwis.cwis-dashboard.chart-layout.safety-layout')
                <h1>Sustainability</h1>
                @include('cwis.cwis-dashboard.chart-layout.sustainability-layout')
            </div>
            <input class="input" name="tabs" type="radio" id="tab-2" />
            <label class="label" for="tab-2">Equity</label>
            <div class="panel">
                @include('cwis.cwis-dashboard.chart-layout.seperate-chart-layout.equity-card')
            </div>
            <input class="input" name="tabs" type="radio" id="tab-4" />
            <label class="label" for="tab-4">Safety</label>
            <div class="panel">
                <br>
                @include('cwis.cwis-dashboard.chart-layout.seperate-chart-layout.safety-card')
            </div>
            <input class="input" name="tabs" type="radio" id="tab-3" />
            <label class="label" for="tab-3">Sustainability</label>
            <div class="panel">
                <br>
                @include('cwis.cwis-dashboard.chart-layout.seperate-chart-layout.sustainability-card')
            </div>
        <div class="select-dropdown">
            <select style="text-align: center;" id="cwis-year-select">
               <option value="">Select year</option>
                @foreach($presentYears as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="buttons-container">
            <button class="export-button" style="margin-right:none" id="export">Export to Excel</button>
         <button class="pdf"onclick="downloadPDF()">Generate PDF</button>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>



  /**
 * Generates a safety image containing a doughnut chart with a percentage value.
 * This function is typically used to create an image representation of safety data.
 *
 * @returns {string} - Base64-encoded data URL of the generated safety image.
 */
 function generateSafetyImage() {
    // Create a temporary canvas
    var tempCanvas = document.createElement('canvas');
    var tempCtx = tempCanvas.getContext('2d');

    // Set canvas size to match the size of the doughnut chart canvas (sf1aCanvas)
    tempCanvas.width = sf1aCanvas.width;
    tempCanvas.height = sf1aCanvas.height;

    // Draw the doughnut chart onto the temporary canvas
    tempCtx.drawImage(sf1aCanvas, 0, 0);

    // Calculate font size dynamically based on canvas size or viewport size
    var baseFontSize = 24; // Base font size
    var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2; // Adjust font size relative to canvas size

    // Draw the percentage value onto the temporary canvas with the calculated font size
    var percentText = {{ html_entity_decode($sf1a[0]->data_value) }} + '%'; // Extract percentage value
    tempCtx.fillStyle = '#29ab87'; // Set color for the percentage text
    tempCtx.font = fontSize + 'px Arial'; // Set font size dynamically
    tempCtx.textAlign = 'center'; // Align text horizontally at the center
    tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2); // Draw the text at the center

    // Return the base64-encoded data URL of the generated image
    return tempCanvas.toDataURL();
}


 function generateSafety1bImage() {

            var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
            var tempCtx = tempCanvas.getContext('2d');

            // Set canvas size
            tempCanvas.width = sf1bCanvas.width;
            tempCanvas.height = sf1bCanvas.height;

            // Draw the doughnut chart
            tempCtx.drawImage(sf1bCanvas, 0, 0);
            var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
            // Draw the percentage value
            var percentText = {{ html_entity_decode($sf1b[0]->data_value) }} + '%';
            tempCtx.fillStyle = '#29ab87';
            tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
            tempCtx.textAlign = 'center';
            tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

            return tempCanvas.toDataURL();
}
function generateSafety1cImage() {

        var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
        var tempCtx = tempCanvas.getContext('2d');

        // Set canvas size
        tempCanvas.width = sf1cCanvas.width;
        tempCanvas.height = sf1cCanvas.height;

        // Draw the doughnut chart
        tempCtx.drawImage(sf1cCanvas, 0, 0);
        var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
        // Draw the percentage value
        var percentText = {{ html_entity_decode($sf1c[0]->data_value) }} + '%';
        tempCtx.fillStyle = '#29ab87';
        tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
        tempCtx.textAlign = 'center';
        tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

        return tempCanvas.toDataURL();
}
function generateSafety1dImage() {

                var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
                var tempCtx = tempCanvas.getContext('2d');

                // Set canvas size
                tempCanvas.width = sf1dCanvas.width;
                tempCanvas.height = sf1dCanvas.height;

                // Draw the doughnut chart
                tempCtx.drawImage(sf1dCanvas, 0, 0);
                var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
                // Draw the percentage value
                var percentText = {{ html_entity_decode($sf1d[0]->data_value) }} + '%';
                tempCtx.fillStyle = '#29ab87';
                tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
                tempCtx.textAlign = 'center';
                tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

                return tempCanvas.toDataURL();
}
function generateSafety1eImage() {

        var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
        var tempCtx = tempCanvas.getContext('2d');

        // Set canvas size
        tempCanvas.width = sf1eCanvas.width;
        tempCanvas.height = sf1eCanvas.height;

        // Draw the doughnut chart
        tempCtx.drawImage(sf1eCanvas, 0, 0);
        var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
        // Draw the percentage value
        var percentText = {{ html_entity_decode($sf1e[0]->data_value) }} + '%';
        tempCtx.fillStyle = '#29ab87';
        tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
        tempCtx.textAlign = 'center';
        tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

        return tempCanvas.toDataURL();
}
function generateSafety1fImage() {

    var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
    var tempCtx = tempCanvas.getContext('2d');

    // Set canvas size
    tempCanvas.width = sf1fCanvas.width;
    tempCanvas.height = sf1fCanvas.height;

    // Draw the doughnut chart
    tempCtx.drawImage(sf1fCanvas, 0, 0);
    var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
    // Draw the percentage value
    var percentText = {{ html_entity_decode($sf1f[0]->data_value) }} + '%';
    tempCtx.fillStyle = '#29ab87';
    tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
    tempCtx.textAlign = 'center';
    tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

    return tempCanvas.toDataURL();
}
function generateSafety1gImage() {

    var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
    var tempCtx = tempCanvas.getContext('2d');

    // Set canvas size
    tempCanvas.width = sf1gCanvas.width;
    tempCanvas.height = sf1gCanvas.height;

    // Draw the doughnut chart
    tempCtx.drawImage(sf1gCanvas, 0, 0);
    var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
    // Draw the percentage value
    var percentText = {{ html_entity_decode($sf1g[0]->data_value) }} + '%';
    tempCtx.fillStyle = '#29ab87';
    tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
    tempCtx.textAlign = 'center';
    tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

    return tempCanvas.toDataURL();
}
function generateSafety2cImage() {

    var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
    var tempCtx = tempCanvas.getContext('2d');

    // Set canvas size
    tempCanvas.width = sf2cCanvas.width;
    tempCanvas.height = sf2cCanvas.height;

    // Draw the doughnut chart
    tempCtx.drawImage(sf2cCanvas, 0, 0);
    var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
    // Draw the percentage value
    var percentText = {{ html_entity_decode($sf2c[0]->data_value) }} + '%';
    tempCtx.fillStyle = '#29ab87';
    tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
    tempCtx.textAlign = 'center';
    tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

    return tempCanvas.toDataURL();
}
function generateSafety2aImage() {

    var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
    var tempCtx = tempCanvas.getContext('2d');

    // Set canvas size
    tempCanvas.width = sf2aCanvas.width;
    tempCanvas.height = sf2aCanvas.height;

    // Draw the doughnut chart
    tempCtx.drawImage(sf2aCanvas, 0, 0);
    var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
    // Draw the percentage value
    var percentText = {{ html_entity_decode($sf2a[0]->data_value) }} + '%';
    tempCtx.fillStyle = '#29ab87';
    tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
    tempCtx.textAlign = 'center';
    tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

    return tempCanvas.toDataURL();
}
function generateSafety2bImage() {

        var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
        var tempCtx = tempCanvas.getContext('2d');

        // Set canvas size
        tempCanvas.width = sf2bCanvas.width;
        tempCanvas.height = sf2bCanvas.height;

        // Draw the doughnut chart
        tempCtx.drawImage(sf2bCanvas, 0, 0);
        var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
        // Draw the percentage value
        var percentText = {{ html_entity_decode($sf2b[0]->data_value) }} + '%';
        tempCtx.fillStyle = '#29ab87';
        tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
        tempCtx.textAlign = 'center';
        tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

        return tempCanvas.toDataURL();
}
function generateSafety3cImage() {

    var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
    var tempCtx = tempCanvas.getContext('2d');

    // Set canvas size
    tempCanvas.width = sf3cCanvas.width;
    tempCanvas.height = sf3cCanvas.height;

    // Draw the doughnut chart
    tempCtx.drawImage(sf3cCanvas, 0, 0);
    var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
    // Draw the percentage value
    var percentText = {{ html_entity_decode($sf3c[0]->data_value) }} + '%';
    tempCtx.fillStyle = '#29ab87';
    tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
    tempCtx.textAlign = 'center';
    tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

    return tempCanvas.toDataURL();
}
function generateSafety3aImage() {

    var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
    var tempCtx = tempCanvas.getContext('2d');

    // Set canvas size
    tempCanvas.width = sf3aCanvas.width;
    tempCanvas.height = sf3aCanvas.height;

    // Draw the doughnut chart
    tempCtx.drawImage(sf3aCanvas, 0, 0);
    var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
    // Draw the percentage value
    var percentText = {{ html_entity_decode($sf3a[0]->data_value) }} + '%';
    tempCtx.fillStyle = '#29ab87';
    tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
    tempCtx.textAlign = 'center';
    tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

    return tempCanvas.toDataURL();
}
function generateSafety3bImage() {

        var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
        var tempCtx = tempCanvas.getContext('2d');

        // Set canvas size
        tempCanvas.width = sf3bCanvas.width;
        tempCanvas.height = sf3bCanvas.height;

        // Draw the doughnut chart
        tempCtx.drawImage(sf3bCanvas, 0, 0);
        var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2; // Adjust font size relative to canvas size
        // Draw the percentage value
        var percentText = {{ html_entity_decode($sf3b[0]->data_value) }} + '%';
        tempCtx.fillStyle = '#29ab87';
        tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
        tempCtx.textAlign = 'center';
        tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

        return tempCanvas.toDataURL();
}

function generateSafety4dImage() {

        var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
        var tempCtx = tempCanvas.getContext('2d');

        // Set canvas size
        tempCanvas.width = sf4dCanvas.width;
        tempCanvas.height = sf4dCanvas.height;

        // Draw the doughnut chart
        tempCtx.drawImage(sf4dCanvas, 0, 0);
        var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2; // Adjust font size relative to canvas size
        // Draw the percentage value
        var percentText = {{ html_entity_decode($sf4d[0]->data_value) }} + '%';
        tempCtx.fillStyle = '#29ab87';
        tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
        tempCtx.textAlign = 'center';
        tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

        return tempCanvas.toDataURL();
}
function generateSafety4aImage() {

        var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
        var tempCtx = tempCanvas.getContext('2d');

        // Set canvas size
        tempCanvas.width = sf4aCanvas.width;
        tempCanvas.height = sf4aCanvas.height;

        // Draw the doughnut chart
        tempCtx.drawImage(sf4aCanvas, 0, 0);
        var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2; // Adjust font size relative to canvas size
        // Draw the percentage value
        var percentText = {{ html_entity_decode($sf4a[0]->data_value) }} + '%';
        tempCtx.fillStyle = '#29ab87';
        tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
        tempCtx.textAlign = 'center';
        tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

        return tempCanvas.toDataURL();
}
function generateSafety4bImage() {

        var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
        var tempCtx = tempCanvas.getContext('2d');

        // Set canvas size
        tempCanvas.width = sf4bCanvas.width;
        tempCanvas.height = sf4bCanvas.height;

        // Draw the doughnut chart
        tempCtx.drawImage(sf4bCanvas, 0, 0);
        var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2; // Adjust font size relative to canvas size
        // Draw the percentage value
        var percentText = {{ html_entity_decode($sf4b[0]->data_value) }} + '%';
        tempCtx.fillStyle = '#29ab87';
        tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
        tempCtx.textAlign = 'center';
        tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

        return tempCanvas.toDataURL();
}

function generateSafety5Image() {

        var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
        var tempCtx = tempCanvas.getContext('2d');

        // Set canvas size
        tempCanvas.width = sf5Canvas.width;
        tempCanvas.height = sf5Canvas.height;

        // Draw the doughnut chart
        tempCtx.drawImage(sf5Canvas, 0, 0);
        var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
        // Draw the percentage value
        var percentText = {{ html_entity_decode($sf5[0]->data_value) }} + '%';
        tempCtx.fillStyle = '#29ab87';
        tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
        tempCtx.textAlign = 'center';
        tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

        return tempCanvas.toDataURL();
}
function generateSafety6Image() {

        var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
        var tempCtx = tempCanvas.getContext('2d');

        // Set canvas size
        tempCanvas.width = sf6Canvas.width;
        tempCanvas.height = sf6Canvas.height;

        // Draw the doughnut chart
        tempCtx.drawImage(sf6Canvas, 0, 0);
        var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
        // Draw the percentage value
        var percentText = {{ html_entity_decode($sf6[0]->data_value) }} + '%';
        tempCtx.fillStyle = '#29ab87';
        tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
        tempCtx.textAlign = 'center';
        tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

        return tempCanvas.toDataURL();
}
function generateSafety7Image() {

        var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
        var tempCtx = tempCanvas.getContext('2d');

        // Set canvas size
        tempCanvas.width = sf7Canvas.width;
        tempCanvas.height = sf7Canvas.height;

        // Draw the doughnut chart
        tempCtx.drawImage(sf7Canvas, 0, 0);
        var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
        // Draw the percentage value
        var percentText = {{ html_entity_decode($sf7[0]->data_value) }} + '%';
        tempCtx.fillStyle = '#29ab87';
        tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
        tempCtx.textAlign = 'center';
        tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

        return tempCanvas.toDataURL();
}
function generateSs1Image()
{
    var tempCanvas = document.createElement('canvas'); // Create a temporary canvas
            var tempCtx = tempCanvas.getContext('2d');

            // Set canvas size
            tempCanvas.width = ss1Canva.width;
            tempCanvas.height = ss1Canva.height;

            // Draw the doughnut chart
            tempCtx.drawImage(ss1Canva, 0, 0);
            var fontSize = Math.min(tempCanvas.width, tempCanvas.height) * 0.2;
            // Draw the percentage value
            var percentText = {{ html_entity_decode($ss1[0]->data_value) }} + '%';
            tempCtx.fillStyle = '#E49B0F';
            tempCtx.font = fontSize +'px Arial'; // Customize font size and style as needed
            tempCtx.textAlign = 'center';
            tempCtx.fillText(percentText, tempCanvas.width / 2, tempCanvas.height / 2);

            return tempCanvas.toDataURL();
}

    function downloadPDF() {

        var safety1aImage = generateSafetyImage();
        var safety1bImage = generateSafety1bImage();
        var safety1cImage = generateSafety1cImage();
        var safety1dImage = generateSafety1dImage();
        var safety1eImage = generateSafety1eImage();
        var safety1fImage = generateSafety1fImage();
        var safety1gImage = generateSafety1gImage();
        var safety2aImage = generateSafety2aImage();
        var safety2bImage = generateSafety2bImage();
        var safety2cImage = generateSafety2cImage();
        var safety3aImage = generateSafety3aImage();
        var safety3bImage = generateSafety3bImage();
        var safety3cImage = generateSafety3cImage();
        var safety4aImage = generateSafety4aImage();
        var safety4bImage = generateSafety4bImage();
        var safety4dImage = generateSafety4dImage();
        var sf5Image = generateSafety5Image();
        var sf6Image = generateSafety6Image();
        var sf7Image = generateSafety7Image();
        var ss1Image = generateSs1Image();
        var sf3d = {{ html_entity_decode($sf3e[0]->data_value ) }};
var eq1 = {{ html_entity_decode($dataValues[0]) }};

        // Use Ajax to send the image data to the server
        fetch('/cwis/chart/download-pdf', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                eq1 :eq1,
                safety1aImage : safety1aImage,
                safety1bImage : safety1bImage,
                safety1cImage : safety1cImage,
                safety1dImage : safety1dImage,
                safety1eImage : safety1eImage,
                safety1fImage : safety1fImage,
                safety1gImage : safety1gImage,
                safety2aImage : safety2aImage,
                safety2bImage : safety2bImage,
                safety2cImage : safety2cImage,
                safety3aImage : safety3aImage,
                safety3bImage : safety3bImage,
                safety3cImage : safety3cImage,
                sf3d : sf3d,
                safety4aImage : safety4aImage,
                safety4bImage : safety4bImage,
                safety4dImage : safety4dImage,
                sf5Image : sf5Image,
                sf6Image : sf6Image,
                sf7Image : sf7Image,
                ss1Image : ss1Image

            }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to download PDF');
            }
            return response.blob();
        })
        .then(blob => {
            // Convert blob to downloadable file
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'cwis.pdf';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
    <script>
       var baseCwisUrl = "{{ url('cwis/cwis/getall') }}/";

$(document).ready(function() {
    $(document).ready(function() {
    // Handle dropdown change event
    $("#cwis-year-select").on("change", function () {
        var selected_value = $(this).val();
        localStorage.setItem("selected_year", selected_value); // Save selected year to local storage
        window.location.replace("{{ url('cwis/cwis/getall/') }}/" + selected_value);
    });

    // On page load, set the selected year in the dropdown from local storage
    var selected_year = localStorage.getItem("selected_year");
    var latestYear = <?php echo $latestYear; ?>;
    
    // Check if the page is being loaded after returning from another page
    if (selected_year) {
        // If it is, set the selected year in the dropdown
        $("#cwis-year-select").val(selected_year);
    } else {
        // If it's the first time loading the page, set the dropdown to the latest year
        $("#cwis-year-select").val(latestYear);
        // Save the latest year to local storage
        localStorage.setItem("selected_year", latestYear);
    }

    // Remove item from local storage when navigating away from the page
    $(window).on("beforeunload", function() {
        // Check if the current URL is the page where you want to clear local storage
        // Replace 'example.com' with your actual domain and route if necessary
        if (window.location.href.includes("cwis/cwis/getall/")) {
            localStorage.removeItem("selected_year");
        }
    });
});
$("#export").click(function () {
    var selected_year = localStorage.getItem("selected_year");
    if ($("#cwis-year-select").val() == 'Select a year') {
        alert('Select a year');
    } else {
        window.open("{{ url('cwis/export-csv') }}/" + $("#cwis-year-select").val());
    }
})


    });
    </script>
@stop
