@extends('layouts.main')
@section('content')
    {{-- <style>
        #map {
            height: 100vh;
            /* atau nilai lain */
        }
    </style> --}}

    <form action="{{ route('attendances_in.store') }}" method="POST" autocomplete="on">
        @csrf

        <div class="mb-3">
            <label for="user" class="form-label">Pegawai</label>
            <select id="user" class="form-select" name="id_user" autofocus required>
                <option value="">Pilih Pegawai</option>
                @foreach ($users as $user)
                    <option value="{{ $user['id'] }}">{{ $user['name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Tanggal</label>
            <input type="date" name="date" class="form-control" id="date" readonly required>
        </div>

        <div class="mb-3">
            <label for="time_in" class="form-label">Waktu Hadir</label>
            <input type="time" name="time_in" class="form-control" id="time_in" readonly required>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Lokasi</label>
            <div id="map" style="height: 400px;"></div>
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="text" name="latitude_in" class="form-control" id="latitude" readonly required>
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="text" name="longitude_in" class="form-control" id="longitude" readonly required>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Set tanggal hari ini
            const today = new Date();
            const formattedDate = today.toISOString().split('T')[0];
            document.getElementById("date").value = formattedDate;

            // Set waktu saat ini
            const hours = today.getHours().toString().padStart(2, '0');
            const minutes = today.getMinutes().toString().padStart(2, '0');
            const formattedTime = `${hours}:${minutes}`;
            document.getElementById("time_in").value = formattedTime;
        });
    </script>
@endpush
