@extends('layouts.in')

@section('body')
    <div class="min-h-screen bg-gray-50">
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Trip Playback</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ $data['vehicle']['name'] }}
                            - {{ $data['trip']['name'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Distance: <span class="font-semibold text-gray-900">{{ number_format($data['trip']['distance'] / 1000, 2) }} km</span>
                        </p>
                        <p class="text-sm text-gray-600">Duration: <span
                                class="font-semibold text-gray-900">{{ gmdate('H:i:s', $data['trip']['time']) }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-3 space-y-4">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div id="map" class="w-full h-[600px]"></div>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="space-y-4">
                            <div class="relative">
                                <input type="range"
                                       id="progressSlider"
                                       min="0"
                                       max="{{ count($data['positions']) - 1 }}"
                                       value="0"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                                <div class="flex justify-between text-xs text-gray-600 mt-2">
                                    <span id="currentTime">{{ $data['trip']['start_at'] }}</span>
                                    <span>{{ $data['trip']['end_at'] }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-center space-x-4">
                                <button id="playPauseBtn"
                                        class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all transform hover:scale-105">
                                    <svg id="playIcon" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                    </svg>
                                    <svg id="pauseIcon" class="w-6 h-6 hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M5.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75A.75.75 0 007.25 3h-1.5zM12.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75a.75.75 0 00-.75-.75h-1.5z"/>
                                    </svg>
                                </button>

                                <button id="stopBtn"
                                        class="bg-gray-600 hover:bg-gray-700 text-white rounded-full p-4 shadow-lg transition-all transform hover:scale-105">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <rect x="5" y="5" width="10" height="10" rx="1"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm text-gray-600">Playback Speed:</label>
                                    <span id="speedDisplay" class="text-sm font-semibold text-gray-900">1.0x</span>
                                </div>
                                <input type="range"
                                       id="playbackSpeed"
                                       min="1"
                                       max="50"
                                       value="10"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Slower</span>
                                    <span>Faster</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-4">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Stats</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-blue-600 rounded-full p-2">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600">Speed</p>
                                        <p id="currentSpeed" class="text-lg font-bold text-gray-900">0 km/h</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-green-600 rounded-full p-2">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600">Direction</p>
                                        <p id="currentDirection" class="text-lg font-bold text-gray-900">0°</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-purple-600 rounded-full p-2">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600">Signal</p>
                                        <p id="currentSignal" class="text-lg font-bold text-gray-900">0</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">Coordinates</p>
                                <p id="currentCoords" class="text-sm font-mono text-gray-900">0.00000, 0.00000</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Trip Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Time</span>
                                <span
                                    class="font-semibold text-gray-900">{{ gmdate('H:i:s', $data['trip']['stats']['time']['total']) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Moving Time</span>
                                <span
                                    class="font-semibold text-gray-900">{{ gmdate('H:i:s', $data['trip']['stats']['time']['movement']) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Stopped Time</span>
                                <span
                                    class="font-semibold text-gray-900">{{ gmdate('H:i:s', $data['trip']['stats']['time']['stopped']) }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Avg Speed</span>
                                <span class="font-semibold text-gray-900">{{ number_format($data['trip']['stats']['speed']['avg'], 1) }} km/h</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Max Speed</span>
                                <span class="font-semibold text-gray-900">{{ $data['trip']['stats']['speed']['max'] }} km/h</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Avg Moving Speed</span>
                                <span class="font-semibold text-gray-900">{{ number_format($data['trip']['stats']['speed']['avg_movement'], 1) }} km/h</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Info</h3>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="text-gray-600">Name:</span> <span
                                    class="font-semibold">{{ $data['vehicle']['name'] }}</span></p>
                            <p class="text-sm"><span class="text-gray-600">Plate:</span> <span
                                    class="font-semibold">{{ $data['vehicle']['plate'] }}</span></p>
                            <p class="text-sm"><span class="text-gray-600">Timezone:</span> <span
                                    class="font-semibold">{{ $data['vehicle']['timezone']['zone'] }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-rotatedmarker@0.2.0/leaflet.rotatedMarker.js"></script>

    <script>
        const tripData = @json($data);
        const positions = tripData.positions;

        let map, marker, polyline;
        let currentIndex = 0;
        let isPlaying = false;
        let playbackInterval;
        let playbackDelay = 1000;

        function createVehicleIcon() {
            return L.divIcon({
                className: 'custom-vehicle-marker',
                html: `<div style="background-color: #3B82F6; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
                    <svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                        <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                    </svg>
                   </div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });
        }

        function initMap() {
            const firstPos = positions[0];

            map = L.map('map').setView([firstPos.latitude, firstPos.longitude], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            marker = L.marker([firstPos.latitude, firstPos.longitude], {
                icon: createVehicleIcon(),
                zIndexOffset: 1000,
                rotationAngle: firstPos.direction || 0,
                rotationOrigin: 'center'
            }).addTo(map);

            const routeCoords = positions.map(pos => [pos.latitude, pos.longitude]);
            polyline = L.polyline(routeCoords, {
                color: '#3B82F6',
                weight: 4,
                opacity: 0.7,
                dashArray: '10, 5'
            }).addTo(map);

            const startIcon = L.divIcon({
                className: 'custom-flag-icon',
                html: `<div class="flag-marker">
                    <svg width="32" height="40" viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="4" y1="5" x2="4" y2="40" stroke="#059669" stroke-width="2"/>
                        <path d="M4 5 L28 5 L28 20 L16 15 L4 20 Z" fill="#10B981" stroke="#047857" stroke-width="1.5"/>
                        <text x="10" y="15" fill="white" font-size="10" font-weight="bold">S</text>
                    </svg>
                   </div>`,
                iconSize: [32, 40],
                iconAnchor: [4, 40]
            });

            L.marker([positions[0].latitude, positions[0].longitude], {
                icon: startIcon,
                zIndexOffset: 500
            }).addTo(map).bindPopup('<b>Start Point</b><br>' + positions[0].date_at);

            const endIcon = L.divIcon({
                className: 'custom-flag-icon',
                html: `<div class="flag-marker">
                    <svg width="32" height="40" viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="4" y1="5" x2="4" y2="40" stroke="#DC2626" stroke-width="2"/>
                        <path d="M4 5 L28 5 L28 20 L16 15 L4 20 Z" fill="#EF4444" stroke="#B91C1C" stroke-width="1.5"/>
                        <text x="10" y="15" fill="white" font-size="10" font-weight="bold">E</text>
                    </svg>
                   </div>`,
                iconSize: [32, 40],
                iconAnchor: [4, 40]
            });

            L.marker([positions[positions.length - 1].latitude, positions[positions.length - 1].longitude], {
                icon: endIcon,
                zIndexOffset: 500
            }).addTo(map).bindPopup('<b>End Point</b><br>' + positions[positions.length - 1].date_at);

            map.fitBounds(polyline.getBounds(), {padding: [50, 50]});
        }

        function updatePosition(index) {
            if (index < 0 || index >= positions.length) return;

            currentIndex = index;
            const pos = positions[index];

            marker.setLatLng([pos.latitude, pos.longitude]);
            marker.setRotationAngle(pos.direction || 0);

            map.panTo([pos.latitude, pos.longitude], {
                animate: true,
                duration: 0.3
            });

            document.getElementById('currentSpeed').textContent = `${pos.speed} km/h`;
            document.getElementById('currentDirection').textContent = `${pos.direction}°`;
            document.getElementById('currentSignal').textContent = pos.signal;
            document.getElementById('currentCoords').textContent = `${pos.latitude.toFixed(5)}, ${pos.longitude.toFixed(5)}`;
            document.getElementById('currentTime').textContent = pos.date_at;
            document.getElementById('progressSlider').value = index;
        }

        function togglePlayPause() {
            isPlaying = !isPlaying;

            const playIcon = document.getElementById('playIcon');
            const pauseIcon = document.getElementById('pauseIcon');

            if (isPlaying) {
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
                play();
            } else {
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                pause();
            }
        }

        function play() {
            playbackInterval = setInterval(() => {
                if (currentIndex < positions.length - 1) {
                    updatePosition(currentIndex + 1);
                } else {
                    stop();
                }
            }, playbackDelay);
        }

        function pause() {
            clearInterval(playbackInterval);
        }

        function stop() {
            pause();
            isPlaying = false;
            document.getElementById('playIcon').classList.remove('hidden');
            document.getElementById('pauseIcon').classList.add('hidden');
            updatePosition(0);
        }

        document.getElementById('playPauseBtn').addEventListener('click', togglePlayPause);
        document.getElementById('stopBtn').addEventListener('click', stop);

        document.getElementById('progressSlider').addEventListener('input', (e) => {
            pause();
            isPlaying = false;
            document.getElementById('playIcon').classList.remove('hidden');
            document.getElementById('pauseIcon').classList.add('hidden');
            updatePosition(parseInt(e.target.value));
        });

        document.getElementById('playbackSpeed').addEventListener('input', (e) => {
            const speedValue = parseInt(e.target.value);
            playbackDelay = 2050 - (speedValue * 40);

            const displaySpeed = (speedValue / 10).toFixed(1);
            document.getElementById('speedDisplay').textContent = `${displaySpeed}x`;

            if (isPlaying) {
                pause();
                play();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            initMap();
            updatePosition(0);
        });
    </script>

    <style>
        .custom-vehicle-marker, .custom-flag-icon, .custom-direction-arrow {
            background: none !important;
            border: none !important;
        }

        .flag-marker {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        #progressSlider::-webkit-slider-thumb {
            appearance: none;
            width: 20px;
            height: 20px;
            background: #3B82F6;
            cursor: pointer;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        #progressSlider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: #3B82F6;
            cursor: pointer;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .leaflet-popup-content-wrapper {
            border-radius: 8px;
        }
    </style>
@stop
