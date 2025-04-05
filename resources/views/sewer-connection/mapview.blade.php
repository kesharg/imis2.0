<!-- Button trigger modal -->
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<style>
.map {
    width: 100%;
    height: 400px;
}
</style>
<style>
#kml-map {
    border: 1px solid #000000;
}

.layer-switcher {
    top: 0 !important;
}

a.skiplink {
    position: absolute;
    clip: rect(1px, 1px, 1px, 1px);
    padding: 0;
    border: 0;
    height: 1px;
    width: 1px;
    overflow: hidden;
}

a.skiplink:focus {
    clip: auto;
    height: auto;
    width: auto;
    background-color: #fff;
    padding: 0.3em;
}

#kml-map:focus {
    outline: #4A74A8 solid 0.15em;
}
</style>
<link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
<link rel="stylesheet" href="https://unpkg.com/ol-layerswitcher@3.8.3/dist/ol-layerswitcher.css" />
<link rel="stylesheet" href="{{asset('css/app.css')}}">
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Sewer Connection</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><button type="button"
                        class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>

            </div>
            <div class="modal-body">
                <div id="kml-map" class="map"></div>
            </div>
        </div>
    </div>
</div>
<script src="https://openlayers.org/en/v4.6.5/build/ol.js"></script>
<!-- Layer Switcher -->
<script src="https://unpkg.com/ol-layerswitcher@3.8.3"></script>
<script src="{{asset('js/app.js')}}"></script>

<script>
    var mapInitialized = false;
    var map;

    $(document).ready(function() {
        $('#exampleModal').on('shown.bs.modal', function(e) {
            var geomValue;
            var sewerGeomValue;

            var button = $(e.relatedTarget);
            var kml = button.data('id');
            var kml2 = button.data('sewer-code');
            var url = '/sewerconnection/sewerconnection/datageom/';
            var url2 = '/sewerconnection/sewerconnection/geomsewer/';

            var request1 = $.ajax({
                url: url + kml,
                method: 'GET'
            });

            var request2 = $.ajax({
                url: url2 + kml2,
                method: 'GET'
            });

            $.when(request1, request2).done(function(response1, response2) {
                if (response1[0].wkt_geom) {
                    geomValue = response1[0].wkt_geom;
                    console.log("Geom value: " + geomValue);

                    var format = new ol.format.WKT();
                    var feature = format.readFeature(geomValue, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    });

                    var buildingLayer = new ol.layer.Vector({
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#0000FF',
                                width: 4
                            }),
                            text: new ol.style.Text({
                            font: '12px Calibri,sans-serif',
                            fill: new ol.style.Fill({
                                color: '#000'
                            }),
                            stroke: new ol.style.Stroke({
                                color: '#fff',
                                width: 3
                            }),
                            text: 'BIN: ' + kml
                        })
                        })
                    });

                    buildingLayer.getSource().addFeature(feature);

                    addExtraLayer('building_layer', 'Building Layer', buildingLayer);

                    var extent = feature.getGeometry().getExtent();
                    map.getView().fit(extent, map.getSize());
                } else {
                    swal({
                        title: "Warning!",
                        text: "Building not found",
                        icon: "warning",
                        closeOnClickOutside: false
                    }).then(() => {
                        $('#exampleModal').modal('hide');
                        $('.modal-backdrop').remove();
                    });
                }

                if (response2[0].wkt_geom) {
                    sewerGeomValue = response2[0].wkt_geom;

                    var sewerFeature = format.readFeature(sewerGeomValue, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    });

                    var sewerLayer = new ol.layer.Vector({
                        source: new ol.source.Vector(),
                        style: new ol.style.Style({
                            stroke: new ol.style.Stroke({
                                color: '#FF00FF',
                                width: 3
                            }),
                            text: new ol.style.Text({
                            font: '12px Calibri,sans-serif',
                            fill: new ol.style.Fill({
                                color: '#000'
                            }),
                            stroke: new ol.style.Stroke({
                                color: '#fff',
                                width: 3
                            }),
                            text: 'Sewer Code: ' + kml2
                        })
                        })
                    });

                    sewerLayer.getSource().addFeature(sewerFeature);

                    addExtraLayer('sewer_layer', 'Sewer Layer', sewerLayer);
                } else {
                    swal({
                        title: "Warning!",
                        text: "Sewer information not found",
                        icon: "warning",
                        closeOnClickOutside: false
                    }).then(() => {
                        $('#exampleModal').modal('hide');
                        $('.modal-backdrop').remove();
                    });
                }
            }).fail(function() {
                swal({
                    title: "Error!",
                    text: "An error occurred while fetching data",
                    icon: "error",
                    closeOnClickOutside: false
                }).then(() => {
                    $('#exampleModal').modal('hide');
                    $('.modal-backdrop').remove();
                });
            });

            if (!mapInitialized) {
                initializeMap();
                mapInitialized = true;
            }
        });
    });

    function initializeMap() {
        var workspace = '<?php echo Config::get("constants.GEOSERVER_WORKSPACE"); ?>';
        var gurl = "<?php echo Config::get("constants.GEOSERVER_URL"); ?>/";
        var gurl_wms = gurl + 'wms';
        var gurl_wfs = gurl + 'wfs';
        var authkey = '<?php echo Config::get("constants.AUTH_KEY"); ?>';
        var gurl_legend = gurl_wms +
            "?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20";
            var buildingsLayer = new ol.layer.Image({
            visible: false,
            title: "Buildings",
            source: new ol.source.ImageWMS({
                url: gurl_wms,
                params: {
                    'LAYERS': workspace + ':' + 'buildings_layer',
                    'TILED': true,
                },
                serverType: 'geoserver',
                transition: 0,
            })
        });
        var containmentsLayer = new ol.layer.Image({
            visible: false,
            title: "Containments",
            source: new ol.source.ImageWMS({
                url: gurl_wms,
                params: {
                    'LAYERS': workspace + ':' + 'containments_layer',
                    'TILED': true,
                },
                serverType: 'geoserver',
                transition: 0,
            })
        });
        var wardsLayer = new ol.layer.Image({
            visible: true,
            title: "Wards",
            source: new ol.source.ImageWMS({
                url: gurl_wms,
                params: {
                    'LAYERS': workspace + ':' + 'wards_layer',
                    'TILED': true,
                    'STYLES': 'wards_layer_none'
                },
                serverType: 'geoserver',
                transition: 0,
            })
        });
        var sewersLayer = new ol.layer.Image({
            visible: true,
            title: "Sewers",
            source: new ol.source.ImageWMS({
                url: gurl_wms,
                params: {
                    'LAYERS': workspace + ':' + 'sewerlines_layer',
                    'TILED': true,
                },
                serverType: 'geoserver',
                transition: 0,
            })
        });
        var roadslayer = new ol.layer.Image({
            visible: true,
            title: "Roads",
            source: new ol.source.ImageWMS({
                url: gurl_wms,
                params: {
                    'LAYERS': workspace + ':' + 'roadlines_layer',
                    'TILED': true,
                },
                serverType: 'geoserver',
                transition: 0,
            })
        });
        var googleLayerHybrid = new ol.layer.Tile({
            visible: false,
            title: "Google Satellite & Roads",
            type: "base",
            source: new ol.source.TileImage({
                url: 'http://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}'
            }),
        });

        var googleLayerRoadmap = new ol.layer.Tile({
            title: "Google Road Map",
            type: "base",
            source: new ol.source.TileImage({
                url: 'http://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}'
            }),
        });

        var layerSwitcher = new LayerSwitcher({
            startActive: true,
            reverse: true,
            groupSelectStyle: 'group'
        });

        map = new ol.Map({
            interactions: ol.interaction.defaults({
                altShiftDragRotate: false,
                dragPan: false,
                rotate: false,
                doubleClickZoom: false
            }).extend([new ol.interaction.DragPan({
                kinetic: null
            })]),
            target: 'kml-map',
            controls: ol.control.defaults({
                attribution: false
            }),
            layers: [
                new ol.layer.Group({
                    title: 'Base maps',
                    layers: [
                        googleLayerHybrid, googleLayerRoadmap
                    ]
                }),
                new ol.layer.Group({
                    title: 'Layers',
                    fold: 'open',
                    layers: [
                        sewersLayer, roadslayer, wardsLayer, containmentsLayer, buildingsLayer,
                    ]
                }),
            ],
            view: new ol.View({
                center: ol.proj.transform([85.37004580498977, 27.643296216592432], 'EPSG:4326',
                    'EPSG:3857'),
                minZoom: 12.5,
                maxZoom: 21.5,
                extent: ol.proj.transformExtent([85.32348539192756, 27.58711426558866,
                    85.44082675863419, 27.684646263435823
                ], 'EPSG:4326', 'EPSG:3857')
            })
        });

        map.addControl(layerSwitcher);
    }

    function addExtraLayer(key, name, layer) {
        if (!map) return;
        var existingLayer = map.getLayers().getArray().find(l => l.get('name') === name);
        if (existingLayer) map.removeLayer(existingLayer);
        layer.set('name', name);
        map.addLayer(layer);
    }
</script>


@endpush