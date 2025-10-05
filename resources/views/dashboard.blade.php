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
    <script>
        // Data jadwal dari controller (hasil query dari ScheduleTemplates)
        const jadwalAbsen = @json($day);

        const hariMap = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        const hariIni = hariMap[new Date().getDay()];
        document.getElementById("hariSekarang").textContent = hariIni;

        const jadwalHariIni = jadwalAbsen.find(item => item.day === hariIni);
        const jamMulaiAbsen = jadwalHariIni ? jadwalHariIni.start_time : "00:00";
        const jamSelesaiAbsen = jadwalHariIni ? jadwalHariIni.end_time : "00:00";

        // Konversi jam ke detik untuk perbandingan
        const toSeconds = (waktu) => {
            const [h, m, s = 0] = waktu.split(":").map(Number);
            return h * 3600 + m * 60 + s;
        };

        function updateWaktu() {
            const now = new Date();
            const jam = String(now.getHours()).padStart(2, "0");
            const menit = String(now.getMinutes()).padStart(2, "0");
            const detik = String(now.getSeconds()).padStart(2, "0");
            const waktuSekarang = `${jam}:${menit}:${detik}`;
            document.getElementById("jamSekarang").textContent = waktuSekarang;

            const waktuSekarangDetik = toSeconds(`${jam}:${menit}:${detik}`);
            const mulaiDetik = toSeconds(`${jamMulaiAbsen}:00`);
            const selesaiDetik = toSeconds(`${jamSelesaiAbsen}:00`);

            const btn = document.getElementById("btnAbsen");
            let route = "";
            let detail = "";

            if (waktuSekarangDetik <= mulaiDetik + (2 * 3600)) {
                btn.textContent = "Absen Masuk Sekarang";
                btn.className = "btn btn-success";
                btn.disabled = false;
                route = "{{ route('absen.masuk') }}";
                detail = "Masuk";
            } else if (waktuSekarangDetik > mulaiDetik + (2 * 3600) && waktuSekarangDetik < selesaiDetik) {
                btn.textContent = "Absen Terlambat";
                btn.className = "btn btn-warning";
                btn.disabled = false;
                route = "{{ route('absen.masuk') }}";
                detail = "Terlambat";
            } else if (waktuSekarangDetik >= selesaiDetik && waktuSekarangDetik < selesaiDetik + (2 * 3600)) {
                btn.textContent = "Absen Pulang Sekarang";
                btn.className = "btn btn-primary";
                btn.disabled = false;
                route = "{{ route('absen.pulang') }}";
                detail = "Pulang";
            } else if (waktuSekarangDetik >= selesaiDetik + (3 * 3600) && waktuSekarangDetik <= selesaiDetik + (5 * 3600)) {
                btn.textContent = "Absen Lembur Sekarang";
                btn.className = "btn btn-info";
                btn.disabled = false;
                route = "{{ route('absen.pulang') }}";
                detail = "Lembur";
            } else {
                btn.textContent = "Belum Waktunya Absen";
                btn.className = "btn btn-secondary";
                btn.disabled = true;
            }

            btn.dataset.route = route;
            btn.dataset.detail = detail;
        }

        setInterval(updateWaktu, 1000);
        updateWaktu();

        document.getElementById("btnAbsen").addEventListener("click", async function() {
            const btn = this;
            const route = btn.dataset.route;
            const keterangan = btn.dataset.detail;
            const waktuSekarang = new Date().toLocaleTimeString("id-ID", {
                hour12: false
            }).replaceAll('.', ':');

            if (!route) return;

            navigator.geolocation.getCurrentPosition(async (pos) => {
                const latitude = pos.coords.latitude;
                const longitude = pos.coords.longitude;

                btn.disabled = true;
                const originalText = btn.textContent;
                btn.textContent = "Mengirim...";

                try {
                    const res = await fetch(route, {
                        method: "POST",
                        credentials: "same-origin",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            time_in: waktuSekarang,
                            detail: keterangan,
                            latitude_in: latitude,
                            longitude_in: longitude
                        })
                    });

                    const text = await res.text();
                    let data;

                    try {
                        data = JSON.parse(text);
                    } catch {
                        console.error("Server response:", text);
                        throw new Error("Respon server bukan JSON!");
                    }

                    if (!res.ok) throw new Error(data.message || "Gagal mengirim absen!");

                    alert(data.message);
                    btn.textContent = "Sudah Absen";
                    btn.disabled = true;
                } catch (err) {
                    alert(err.message);
                    btn.textContent = originalText;
                    btn.disabled = false;
                }
            }, () => {
                alert("Gagal mendapatkan lokasi. Pastikan GPS aktif!");
            });
        });
    </script>
@endpush
