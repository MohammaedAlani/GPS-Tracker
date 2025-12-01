@extends('layouts.in')

@section('body')
    <div class="relative w-full h-screen">
        <div id="map" class="w-full h-full"></div>

        <div class="absolute top-4 left-4 bg-white rounded-lg shadow-lg max-w-xs w-72 max-h-96 overflow-y-auto z-[1000]">
            <div class="p-4">
                <h2 class="text-lg font-bold text-gray-800 mb-3 border-b pb-2">Vehicles</h2>
                <div id="vehicle-list" class="space-y-2">
                    @foreach($vehicles as $vehicle)
                        <div id="vehicle-item-{{ $vehicle->id }}"
                             class="p-3 bg-gray-50 rounded-md hover:bg-gray-100 transition cursor-pointer border border-gray-200">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800 text-sm">{{ $vehicle->name }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">{{ $vehicle->plate }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center gap-1">
                                        <span id="speed-{{ $vehicle->id }}" class="text-sm font-bold text-blue-600">--</span>
                                        <span class="text-xs text-gray-500">km/h</span>
                                    </div>
                                    <div id="status-{{ $vehicle->id }}" class="text-xs text-gray-400 mt-1">Loading...</div>
                                </div>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full" id="indicator-{{ $vehicle->id }}"></div>
                                <span id="last-update-{{ $vehicle->id }}" class="text-xs text-gray-400">--</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const map = L.map('map').setView([36.20366, 44.09511], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        const markers = {};
        const vehicles = @json($vehicles);

        const vehicleIcon = L.divIcon({
            className: 'custom-vehicle-marker',
            html: `<div style="background-color: #3B82F6; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
                <svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                    <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                </svg>
               </div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        function humanizeTime(dateStr) {
            if (!dateStr) return "No data";

            const date = new Date(dateStr);
            if (isNaN(date)) return "Invalid";

            const now = new Date();
            const diff = now - date;
            const mins = Math.floor(diff / 60000);

            if (mins < 1) return "Just now";
            if (mins < 60) return `${mins}m ago`;

            const hours = Math.floor(mins / 60);
            if (hours < 24) return `${hours}h ago`;

            const days = Math.floor(hours / 24);
            return `${days}d ago`;
        }

        async function updateAllVehicles() {
            try {
                const vehicleIds = vehicles.map(v => v.id);

                const response = await fetch('/vehicle-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ vehicles: vehicleIds })
                });

                const statuses = await response.json();

                statuses.forEach(data => {
                    const vehicleId = data.vehicle_id;
                    const speed = parseFloat(data.speed).toFixed(2);

                    document.getElementById(`speed-${vehicleId}`).textContent = speed;

                    const indicator = document.getElementById(`indicator-${vehicleId}`);
                    const statusText = document.getElementById(`status-${vehicleId}`);

                    // --- Humanize last_seen ---
                    const lastSeen = data.last_seen ? new Date(data.last_seen) : null;
                    const humanized = humanizeTime(data.last_seen);
                    const fullFormat = lastSeen
                        ? lastSeen.toISOString().replace('T', ' ').substring(0, 19)
                        : "No data";

                    document.getElementById(`last-update-${vehicleId}`).textContent = humanized;
                    document.getElementById(`last-update-${vehicleId}`).title = fullFormat;

                    // --- Detect old data ---
                    let isOldData = false;
                    if (speed > 0 && lastSeen) {
                        const minDiff = (new Date() - lastSeen) / 60000;
                        if (minDiff > 10) {     // old data threshold
                            isOldData = true;
                        }
                    }

                    // --- Status/UI coloring ---
                    if (isOldData) {
                        indicator.className = 'w-2 h-2 rounded-full bg-yellow-500';
                        statusText.textContent = 'Old Data';
                        statusText.className = 'text-xs text-yellow-600';
                    }
                    else if (parseFloat(speed) > 0) {
                        indicator.className = 'w-2 h-2 rounded-full bg-green-500 animate-pulse';
                        statusText.textContent = 'Moving';
                        statusText.className = 'text-xs text-green-600';
                    }
                    else {
                        indicator.className = 'w-2 h-2 rounded-full bg-gray-400';
                        statusText.textContent = 'Stopped';
                        statusText.className = 'text-xs text-gray-500';
                    }

                    // --- Map update ---
                    const lat = parseFloat(data.latitude);
                    const lng = parseFloat(data.longitude);

                    if (markers[vehicleId]) {
                        markers[vehicleId].setLatLng([lat, lng]);
                    } else {
                        const vehicle = vehicles.find(v => v.id === vehicleId);
                        const marker = L.marker([lat, lng], { icon: vehicleIcon })
                            .addTo(map)
                            .bindPopup(`
                            <div class="text-sm">
                                <strong>${vehicle.name}</strong><br>
                                Speed: ${speed} km/h<br>
                                Direction: ${data.direction}°<br>
                                Last Seen: ${fullFormat}
                            </div>
                        `);
                        markers[vehicleId] = marker;
                    }

                    document.getElementById(`vehicle-item-${vehicleId}`).onclick = () => {
                        map.setView([lat, lng], 15);
                        markers[vehicleId].openPopup();
                    };
                });

            } catch (error) {
                console.error('Error fetching vehicle statuses:', error);
            }
        }

        updateAllVehicles();
        setInterval(updateAllVehicles, 5000);

        setTimeout(() => {
            if (Object.keys(markers).length > 0) {
                const group = L.featureGroup(Object.values(markers));
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }, 1000);
    </script>

    <style>
        .leaflet-container {
            z-index: 1;
        }

        .custom-vehicle-marker {
            background: transparent;
            border: none;
        }
    </style>
@endsection
