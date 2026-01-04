// Fungsi Global untuk Villa Duration (Harus di luar DOMContentLoaded agar bisa diakses onclick HTML)
window.adjustVillaDuration = function (change) {
    const durationInput = document.getElementById("villa_duration");
    const displayNights = document.getElementById("villa_nights_disp");

    if (durationInput) {
        let currentVal = parseInt(durationInput.value) || 1;
        let newVal = currentVal + change;

        if (newVal < 1) newVal = 1;

        // Update tampilan input
        durationInput.value = newVal;

        // Update text durasi malam
        if (displayNights) displayNights.textContent = newVal;

        // Update semua hidden input durasi per kamar
        document
            .querySelectorAll(".villa-final-days-input")
            .forEach((input) => {
                input.value = newVal;
            });

        // Trigger event untuk recalculate total
        const event = new Event("input", { bubbles: true });
        durationInput.dispatchEvent(event);
    }
};

// Fungsi Global untuk UI Service
window.toggleServiceUI = function (id) {
    const checkbox = document.getElementById("svc-" + id);
    if (checkbox) {
        checkbox.checked = !checkbox.checked;
        // Trigger perubahan event manually
        const event = new Event("change", { bubbles: true });
        checkbox.dispatchEvent(event);
    }
};

window.updateServiceUI = function (id, change, max) {
    const qtyInput = document.getElementById("qty-" + id);
    const dispSpan = document.getElementById("disp-" + id);
    const checkbox = document.getElementById("svc-" + id);

    if (qtyInput && dispSpan) {
        let val = parseInt(qtyInput.value) || 0;
        val += change;
        if (val < 0) val = 0;

        qtyInput.value = val;
        dispSpan.innerText = val;

        // Auto check/uncheck based on qty
        if (checkbox) {
            checkbox.checked = val > 0;
            // Trigger change event
            const event = new Event("change", { bubbles: true });
            checkbox.dispatchEvent(event);
        }
    }
};

document.addEventListener("DOMContentLoaded", function () {
    const bookingForm = document.getElementById("bookingForm");
    if (!bookingForm) return;

    // --- Inisialisasi Elemen ID ---
    const grandTotalElement = document.getElementById("grand_total"); // Menggunakan ID yang benar: grand_total
    const villaRoomTotalElement = document.getElementById("villa_room_total");
    const villaServiceTotalElement = document.getElementById(
        "villa_service_total"
    );

    // --- LOGIKA KALENDER (Format dd-mm-yy & Min Date) ---
    const tanggalInput = document.getElementById("tanggal");
    if (tanggalInput) {
        // 1. Set minimal tanggal hari ini (Native HTML5 validation)
        const today = new Date().toISOString().split("T")[0];
        tanggalInput.setAttribute("min", today);

        // 2. Format dd-mm-yy menggunakan Flatpickr (Jika library tersedia)
        // Jika tidak ada flatpickr, akan tetap menggunakan native datepicker (yyyy-mm-dd) namun tetap dengan validasi min date
        if (typeof flatpickr !== "undefined") {
            flatpickr(tanggalInput, {
                dateFormat: "d-m-Y", // Format sesuai permintaan
                minDate: "today",
                defaultDate: tanggalInput.value || "today",
                disableMobile: "true",
            });
        }
    }

    // --- LOGIKA KALKULASI UTAMA ---
    function updateTotal() {
        let totalRoom = 0;
        let totalService = 0;

        // 1. Hitung Harga Kamar / Villa
        const villaDurationInput = document.getElementById("villa_duration");
        let duration = 1;

        if (villaDurationInput) {
            // Mode Villa (Satu durasi untuk semua unit)
            duration = parseInt(villaDurationInput.value) || 1;
        }

        // Ambil elemen kamar dari list visual (Rincian Pesanan)
        // Kita menggunakan class .villa-room-item (mode villa) dan .room-item (mode biasa)
        const roomItems = document.querySelectorAll(
            ".villa-room-item, .room-item"
        );

        if (roomItems.length > 0) {
            roomItems.forEach((item) => {
                const price = parseFloat(item.dataset.price) || 0;
                let itemDurationDays = duration; // default: villa mode

                // Cek jika ini adalah item mode biasa yang punya input durasi sendiri
                const specificDurationInput =
                    item.querySelector(".final-days-input");
                if (specificDurationInput) {
                    // itemDurationDays = jumlah hari sebenarnya = unitCount * unitMultiplier
                    itemDurationDays =
                        parseInt(specificDurationInput.value) || 1;

                    // Update tampilan subtotal per item (Mode Biasa) menggunakan hari sebenarnya
                    const subDisplay = item.querySelector(".subtotal-display");
                    if (subDisplay) {
                        subDisplay.innerText =
                            "Rp" +
                            new Intl.NumberFormat("id-ID").format(
                                price * itemDurationDays
                            );
                    }
                }

                totalRoom += price * itemDurationDays;
            });
        }

        // 2. Hitung Harga Service
        const serviceCheckboxes = document.querySelectorAll(
            'input[name="services[]"].service-check'
        );
        if (serviceCheckboxes.length > 0) {
            // --- KASUS A: MODE INTERAKTIF (ADA CHECKBOX) ---
            serviceCheckboxes.forEach((cb) => {
                // Hanya hitung jika dicentang
                if (cb.checked) {
                    const price = parseFloat(cb.dataset.price) || 0;
                    const id = cb.value;
                    const qtyInput = document.getElementById("qty-" + id);
                    const qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;

                    totalService += price * qty;
                }
            });
        } else {
            // --- KASUS B: MODE SERVICE ONLY (HIDDEN INPUT) ---
            const hiddenServices = document.querySelectorAll(
                'input[name="services[]"][type="hidden"][data-price]'
            );

            hiddenServices.forEach((input) => {
                // Input hidden dianggap pasti dipilih (karena user sudah di halaman konfirmasi)
                const price = parseFloat(input.dataset.price) || 0;
                const id = input.value;

                // Cari qty pasangannya. Di blade kamu ID-nya: 'hidden_qty_{id}'
                // atau fallback ke 'qty-{id}' jika struktur ID berbeda
                let qtyInput = document.getElementById("hidden_qty_" + id);
                if (!qtyInput) qtyInput = document.getElementById("qty-" + id);

                const qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;

                totalService += price * qty;
            });
        }

        // 3. Update UI
        const grandTotal = totalRoom + totalService;
        const formatter = new Intl.NumberFormat("id-ID");

        // const grandTotalElement = document.getElementById('grand_total');
        if (grandTotalElement) {
            grandTotalElement.innerText = "Rp" + formatter.format(grandTotal);
        }

        // const villaRoomTotalElement = document.getElementById('villa_room_total');
        // const villaServiceTotalElement = document.getElementById('villa_service_total');

        // Update rincian sub-total (Khusus Mode Villa)
        if (villaRoomTotalElement)
            villaRoomTotalElement.innerText =
                "Rp" + formatter.format(totalRoom);
        if (villaServiceTotalElement)
            villaServiceTotalElement.innerText =
                "Rp" + formatter.format(totalService);
    }

    // --- EVENT LISTENERS ---

    // Listen perubahan pada input durasi villa
    const villaInput = document.getElementById("villa_duration");
    if (villaInput) {
        villaInput.addEventListener("input", updateTotal);
        villaInput.addEventListener("change", updateTotal);
    }

    // Listen perubahan checkbox layanan (delegasi event)
    const services = document.querySelectorAll('input[name="services[]"]');
    services.forEach((s) => s.addEventListener("change", updateTotal));

    // ======= NEW: Handlers untuk mode BIASA (per-kamar + / - dan unit-select) =======
    // Menangani tombol plus/minus per room, update .display-amount, .final-days-input, detail-text, subtotal-display
    // Perubahan penting:
    //  - final-days-input tetap menyimpan jumlah UNIT (mis. 2 jika memilih 2 Minggu) -> supaya server-side logic TETAP sama
    //  - perhitungan harga di UI (subtotal & grand total) memakai multiplier unit (1,7,30,365)
    document.querySelectorAll(".room-item").forEach((roomEl) => {
        const btnPlus = roomEl.querySelector(".btn-plus");
        const btnMinus = roomEl.querySelector(".btn-minus");
        const displaySpan = roomEl.querySelector(".display-amount");
        const finalInput = roomEl.querySelector(".final-days-input");
        const detailText = roomEl.querySelector(".detail-text");
        const unitSelect = roomEl.querySelector(".unit-select");
        const subDisplay = roomEl.querySelector(".subtotal-display");

        // Helper untuk meng-update tampilan dan input
        function setUnits(newUnits) {
            // Jaga minimal 1 unit
            if (newUnits < 1) newUnits = 1;

            // Update tampilan unit count dan input yang dikirim ke server (jumlah unit)
            if (displaySpan) displaySpan.innerText = newUnits;

            // Hitung multiplier berdasarkan unitSelect (1,7,30,365)
            const multiplier = unitSelect ? parseInt(unitSelect.value) || 1 : 1;

            // Input hidden (yang dikirim ke Controller/Database) diisi Total Hari
            // Contoh: 1 Minggu -> value = 7. Controller akan menghitung 7 * HargaPerMalam
            if (finalInput) finalInput.value = newUnits * multiplier;

            // Update detail text (mis. "2 Minggu")
            let unitLabel = "Hari";
            if (unitSelect) {
                const u = unitSelect.value;
                if (u === "7") unitLabel = "Minggu";
                else if (u === "30") unitLabel = "Bulan";
                else if (u === "365") unitLabel = "Tahun";
            }
            if (detailText) detailText.innerText = `${newUnits} ${unitLabel}`;

            // Update subtotal visible (harga_per_hari * jumlah_unit * multiplier)
            const price = parseFloat(roomEl.dataset.price) || 0;
            const subtotal = price * newUnits * multiplier;
            if (subDisplay)
                subDisplay.innerText =
                    "Rp" + new Intl.NumberFormat("id-ID").format(subtotal);

            // Recalculate totals (grand total)
            updateTotal();
        }

        // Attach click events
        if (btnPlus) {
            btnPlus.addEventListener("click", () => {
                const current = parseInt(displaySpan?.innerText || "1") || 1;
                setUnits(current + 1);
            });
        }
        if (btnMinus) {
            btnMinus.addEventListener("click", () => {
                const current = parseInt(displaySpan?.innerText || "1") || 1;
                setUnits(current - 1);
            });
        }

        // Jika user mengubah unit (Hari/Minggu/Bulan/Tahun), refresh perhitungan
        if (unitSelect) {
            unitSelect.addEventListener("change", () => {
                const current = parseInt(displaySpan?.innerText || "1") || 1;
                setUnits(current);
            });
        }

        // Initial sync (agar saat load nilai 1 tercermin di detail & subtotal)
        (function initRoom() {
            const current = parseInt(displaySpan?.innerText || "1") || 1;
            setUnits(current);
        })();
    });

    // Panggil sekali saat load
    updateTotal();
});
