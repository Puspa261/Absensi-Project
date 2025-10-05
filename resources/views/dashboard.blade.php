@extends('layouts.main')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-body text-center py-5">
                    <h3 class="card-title mb-4">ABSENSI KEHADIRAN</h3>

                    <h4 id="hariSekarang" class="mb-2"></h4>
                    <h1 id="jamSekarang" class="my-4"></h1>

                    <button id="btnAbsen" class="btn btn-secondary btn-lg px-5 py-3" disabled>
                        Belum Waktunya Absen
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        // Data jadwal dari controller (hasil query dari ScheduleTemplates)
        const jadwalAbsen = @json($day);

        // Ambil hari ini (0 = Minggu, 1 = Senin, dst)
        const hariSekarangIndex = new Date().getDay();
        const hariMap = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        const hariIni = hariMap[hariSekarangIndex];

        // Cari jadwal sesuai hari dari database
        const jadwalHariIni = jadwalAbsen.find(item => item.day === hariIni);

        // Ambil jam mulai dan selesai (pastikan ada datanya)
        const jamMulaiAbsen = jadwalHariIni ? jadwalHariIni.start_time : "00:00";
        const jamSelesaiAbsen = jadwalHariIni ? jadwalHariIni.end_time : "00:00";

        console.log(`Hari ini: ${hariIni}`);
        console.log(`Jam mulai: ${jamMulaiAbsen}`);
        console.log(`Jam selesai: ${jamSelesaiAbsen}`);

        // Fungsi untuk update waktu realtime
        function updateWaktu() {
            const now = new Date();

            // Format hari & jam
            const hariArray = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            document.getElementById("hariSekarang").textContent = hariArray[now.getDay()];

            const jam = String(now.getHours()).padStart(2, "0");
            const menit = String(now.getMinutes()).padStart(2, "0");
            const detik = String(now.getSeconds()).padStart(2, "0");
            const waktuSekarang = `${jam}:${menit}:${detik}`;
            document.getElementById("jamSekarang").textContent = waktuSekarang;

            // ===== ✅ Perbandingan waktu yang benar =====
            // Konversi jam string menjadi detik total agar bisa dibandingkan dengan akurat
            const toSeconds = (waktu) => {
                const [h, m, s = 0] = waktu.split(":").map(Number);
                return h * 3600 + m * 60 + s;
            };

            const waktuSekarangDetik = toSeconds(`${jam}:${menit}:${detik}`);
            const mulaiDetik = toSeconds(`${jamMulaiAbsen}:00`);
            const selesaiDetik = toSeconds(`${jamSelesaiAbsen}:00`);

            const btn = document.getElementById("btnAbsen");

            if (waktuSekarangDetik >= mulaiDetik && waktuSekarangDetik <= selesaiDetik) {
                btn.disabled = false;
                btn.classList.remove("btn-secondary");
                btn.classList.add("btn-success");
                btn.textContent = "Absen Sekarang";
            } else {
                btn.disabled = true;
                btn.classList.remove("btn-success", "btn-primary");
                btn.classList.add("btn-secondary");
                btn.textContent = "Belum Waktunya Absen";
            }
        }

        // Jalankan realtime setiap 1 detik
        setInterval(updateWaktu, 1000);
        updateWaktu();
    </script> --}}

    <script>
        // Data jadwal dari controller (hasil query dari ScheduleTemplates)
        const jadwalAbsen = @json($day);

        // Ambil hari ini (0 = Minggu, 1 = Senin, dst)
        const hariSekarangIndex = new Date().getDay();
        const hariMap = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        const hariIni = hariMap[hariSekarangIndex];

        // Tampilkan hari di halaman
        document.getElementById("hariSekarang").textContent = hariIni;

        // Cari jadwal sesuai hari dari database
        const jadwalHariIni = jadwalAbsen.find(item => item.day === hariIni);

        // Ambil jam mulai dan selesai (pastikan ada datanya)
        const jamMulaiAbsen = jadwalHariIni ? jadwalHariIni.start_time : "00:00";
        const jamSelesaiAbsen = jadwalHariIni ? jadwalHariIni.end_time : "00:00";

        // Fungsi untuk update waktu realtime
        function updateWaktu() {
            const now = new Date();

            // Jam sekarang (format HH:mm:ss)
            const jam = String(now.getHours()).padStart(2, "0");
            const menit = String(now.getMinutes()).padStart(2, "0");
            const detik = String(now.getSeconds()).padStart(2, "0");
            const waktuSekarang = `${jam}:${menit}:${detik}`;
            document.getElementById("jamSekarang").textContent = waktuSekarang;

            // ===== ✅ Perbandingan waktu yang benar =====
            const toSeconds = (waktu) => {
                const [h, m, s = 0] = waktu.split(":").map(Number);
                return h * 3600 + m * 60 + s;
            };

            const waktuSekarangDetik = toSeconds(`${jam}:${menit}:${detik}`);
            const mulaiDetik = toSeconds(`${jamMulaiAbsen}:00`);
            const selesaiDetik = toSeconds(`${jamSelesaiAbsen}:00`);

            const btn = document.getElementById("btnAbsen");

            if (waktuSekarangDetik >= mulaiDetik && waktuSekarangDetik <= selesaiDetik) {
                btn.disabled = false;
                btn.classList.remove("btn-secondary");
                btn.classList.add("btn-success");
                btn.textContent = "Absen Sekarang";
            } else {
                btn.disabled = true;
                btn.classList.remove("btn-success", "btn-primary");
                btn.classList.add("btn-secondary");
                btn.textContent = "Belum Waktunya Absen";
            }
        }

        // Jalankan realtime setiap 1 detik
        setInterval(updateWaktu, 1000);
        updateWaktu();
    </script>
@endpush
