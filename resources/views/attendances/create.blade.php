@extends('layouts.main')
@section('content')
    {{-- <style>
        #map {
            height: 100vh;
            /* atau nilai lain */
        }
    </style> --}}

    <form action="{{ route('attendances.store') }}" method="POST" autocomplete="on">
        @csrf
        <div id="map" style="height: 500px;"></div>

        <label>Latitude:</label>
        <input type="text" id="latitude" name="latitude" readonly><br>

        <label>Longitude:</label>
        <input type="text" id="longitude" name="longitude" readonly><br>

        <button type="submit">Simpan Lokasi</button>
    </form>
@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var map = L.map('map').setView([0, 0], 19);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var marker, circle;

            function onLocationFound(e) {
                const latlng = e.latlng;

                map.setView(latlng, 20);

                if (marker) map.removeLayer(marker);
                if (circle) map.removeLayer(circle);

                marker = L.marker(latlng).addTo(map)
                    .bindPopup("Lokasimu").openPopup();

                circle = L.circle(latlng, {
                    radius: 20,
                    color: 'blue',
                    fillColor: '#3f51b5',
                    fillOpacity: 0.2
                }).addTo(map);

                // Isi input form
                document.getElementById('latitude').value = latlng.lat;
                document.getElementById('longitude').value = latlng.lng;
            }

            function onLocationError(e) {
                alert("Gagal mendapatkan lokasi: " + e.message);
            }

            map.on('locationfound', onLocationFound);
            map.on('locationerror', onLocationError);

            map.locate({
                setView: true,
                maxZoom: 20,
                watch: true,
                enableHighAccuracy: true
            });
        });
    </script>
@endpush
