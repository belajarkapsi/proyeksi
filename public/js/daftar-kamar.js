const formatRupiah = (n) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(n);

// ====== SELECTION LOGIC (dikembalikan seperti semula) ======
const longPressDuration = 500;
let pressTimer;
let isSelectionMode = false;

function handleCardClick(id, event) {
    if (event.target.closest(".detail-btn")) return;

    const card = document.getElementById("card-" + id);
    if (!card || card.getAttribute("data-available") != "1") return;

    if (isVillaMode) {
        toggleSelection(id);
    } else {
        if (isSelectionMode) {
            event.preventDefault();
            toggleSelection(id);
        } else {
            const url = card.getAttribute("data-booking-url");
            if (url) window.location.href = url;
        }
    }
}

function startPress(id) {
    if (isVillaMode) return;
    const card = document.getElementById("card-" + id);
    if (!card || card.getAttribute("data-available") != "1" || isSelectionMode)
        return;

    const loader = document.getElementById("loader-" + id);
    if (loader) {
        loader.style.width = "100%";
        loader.style.transitionDuration = "0.5s";
    }
    pressTimer = setTimeout(() => {
        enterSelectionMode(id);
    }, longPressDuration);
}

function cancelPress(id) {
    if (isVillaMode) return;
    const loader = document.getElementById("loader-" + id);
    if (loader) {
        loader.style.width = "0";
        loader.style.transitionDuration = "0.2s";
    }
    clearTimeout(pressTimer);
}

function enterSelectionMode(initialId) {
    isSelectionMode = true;
    if (navigator.vibrate) navigator.vibrate(50);
    toggleSelection(initialId);
}

function toggleSelection(id) {
    const checkbox = document.getElementById("check-" + id);
    const overlay = document.getElementById("overlay-" + id);
    const card = document.getElementById("card-" + id);
    if (!checkbox) return;

    checkbox.checked = !checkbox.checked;

    if (checkbox.checked) {
        if (overlay) overlay.classList.remove("hidden");
        if (card)
            card.classList.add("ring-2", "ring-green-600", "scale-[0.98]");
    } else {
        if (overlay) overlay.classList.add("hidden");
        if (card)
            card.classList.remove("ring-2", "ring-green-600", "scale-[0.98]");
    }

    if (!isVillaMode) {
        const checkedCount = document.querySelectorAll(
            'input[name="selected_rooms[]"]:checked'
        ).length;
        if (checkedCount === 0) exitSelectionMode();
    }

    recalcTotals();
}

function exitSelectionMode() {
    document
        .querySelectorAll('input[name="selected_rooms[]"]')
        .forEach((el) => (el.checked = false));
    document
        .querySelectorAll(".room-card")
        .forEach((el) =>
            el.classList.remove("ring-2", "ring-green-600", "scale-[0.98]")
        );
    document
        .querySelectorAll('[id^="overlay-"]')
        .forEach((el) => el.classList.add("hidden"));

    // Reset layanan
    document
        .querySelectorAll(".service-checkbox")
        .forEach((el) => (el.checked = false));
    document
        .querySelectorAll('input[name^="service_quantity"]')
        .forEach((el) => (el.value = 0));
    document
        .querySelectorAll('[id^="display-qty-"]')
        .forEach((el) => (el.innerText = "0"));
    document
        .querySelectorAll('[id^="visible-check-"]')
        .forEach((el) => (el.checked = false));

    isSelectionMode = false;
    recalcTotals();
}

// ====== SERVICES LOGIC ======
function updateServiceQty(id, change, maxLimit) {
    const qtyInput = document.getElementById("srv-qty-" + id);
    const displayEl = document.getElementById("display-qty-" + id);
    const checkbox = document.getElementById("srv-check-" + id);

    let currentQty = parseInt(qtyInput.value) || 0;
    let newQty = currentQty + change;
    if (newQty < 0) newQty = 0;
    if (typeof maxLimit === "number" && newQty > maxLimit) newQty = maxLimit;

    qtyInput.value = newQty;
    if (displayEl) displayEl.innerText = newQty;
    if (checkbox) checkbox.checked = newQty > 0;

    recalcTotals();
}

function toggleStandardService(id) {
    const visibleCheck = document.getElementById("visible-check-" + id);
    const hiddenCheck = document.getElementById("srv-check-" + id);
    const qtyInput = document.getElementById("srv-qty-" + id);

    if (!qtyInput || !hiddenCheck) return;

    hiddenCheck.checked = !!visibleCheck.checked;
    qtyInput.value = visibleCheck.checked ? 1 : 0;

    recalcTotals();
}

// ====== CALCULATION (JANGAN HITUNG SERVICE JIKA TIDAK CHECKED) ======
function recalcTotals() {
    let roomTotal = 0;
    let serviceTotal = 0;
    let selectedRoomsCount = 0;
    let selectedServicesItems = 0;

    // A. Hitung kamar: prioritas ambil dari checkbox selected_rooms[] data-price
    const roomCheckboxes = document.querySelectorAll(
        'input[name="selected_rooms[]"]:checked'
    );
    roomCheckboxes.forEach((cb) => {
        let priceRaw =
            cb.getAttribute("data-price") || cb.dataset.price || null;

        // fallback: baca dari card dataset (jika ada)
        if ((!priceRaw || priceRaw === "" || priceRaw === "0") && cb.closest) {
            const card = cb.closest(".room-card");
            if (card) {
                priceRaw =
                    card.getAttribute("data-room-price") ||
                    card.getAttribute("data-price") ||
                    card.dataset.price ||
                    priceRaw;
            }
        }

        let price = 0;
        if (priceRaw !== null && priceRaw !== undefined) {
            const digits = String(priceRaw).replace(/\D/g, "");
            price = digits ? parseInt(digits, 10) : 0;
        }

        roomTotal += price;
        selectedRoomsCount++;
    });

    // B. Hitung layanan: hanya bila checkbox service-checkbox benar-benar dicentang
    document.querySelectorAll(".service-checkbox").forEach((cb) => {
        if (!cb.checked) return;
        const id = cb.value;
        const price = parseFloat(cb.getAttribute("data-price")) || 0;
        const qtyInput = document.getElementById("srv-qty-" + id);
        let qty = qtyInput ? parseInt(qtyInput.value || 0, 10) : 1;
        if (qty === 0) qty = 1; // jika checkbox checked tapi qty 0, treat as 1
        serviceTotal += price * qty;
        selectedServicesItems += qty;
    });

    const grandTotal = roomTotal + serviceTotal;
    const totalItems = selectedRoomsCount + selectedServicesItems;

    // Update panel & floating bar
    const panelRoom = document.getElementById("panel-room-total");
    const panelService = document.getElementById("panel-service-total");
    const panelGrand = document.getElementById("panel-grand-total");
    const panelRoomCount = document.getElementById("panel-room-count");
    const panelServiceCount = document.getElementById("panel-service-count");
    if (panelRoom) panelRoom.innerText = formatRupiah(roomTotal);
    if (panelService) panelService.innerText = formatRupiah(serviceTotal);
    if (panelGrand) panelGrand.innerText = formatRupiah(grandTotal);
    if (panelRoomCount) panelRoomCount.innerText = selectedRoomsCount + " unit";
    if (panelServiceCount)
        panelServiceCount.innerText = selectedServicesItems + " item";

    const floatGrand = document.getElementById("float-grand-total");
    const countBadge = document.getElementById("selected-count");
    const floatingBar = document.getElementById("floating-bar");
    const topCancel = document.getElementById("top-cancel-btn");
    if (floatGrand) floatGrand.innerText = formatRupiah(grandTotal);
    if (countBadge) countBadge.innerText = totalItems;

    const hasSelection = totalItems > 0;
    if (hasSelection) {
        floatingBar.classList.remove("translate-y-48");
        topCancel.classList.remove("hidden");
        topCancel.classList.add("flex");
    } else {
        floatingBar.classList.add("translate-y-48");
        topCancel.classList.add("hidden");
        topCancel.classList.remove("flex");
    }
}

// ====== PRE-SUBMIT HELPER (tetap ada, disederhanakan untuk robust) ======
(function attachPreSubmitHelper() {
    const bookingForm = document.getElementById("bookingForm");
    if (!bookingForm) return;

    function removeDynamic() {
        Array.from(
            bookingForm.querySelectorAll("input[data-dynamic-service]")
        ).forEach((n) => n.remove());
    }

    function addHidden(name, value, attrs = {}) {
        const inp = document.createElement("input");
        inp.type = "hidden";
        inp.name = name;
        inp.value = value;
        Object.keys(attrs).forEach((k) => inp.setAttribute(k, attrs[k]));
        bookingForm.appendChild(inp);
        return inp;
    }

    // SIMPLIFIED: scan all srv-qty-* inputs and add services[] only when qty>0
    function buildServiceHiddenInputs() {
        removeDynamic();

        // visible toggles
        document
            .querySelectorAll('input[id^="visible-check-"]')
            .forEach((el) => {
                const m = el.id.match(/visible-check-(\d+)/);
                if (!m) return;
                const id = m[1];
                const qtyEl =
                    document.getElementById("srv-qty-" + id) ||
                    document.getElementById("display-qty-" + id) ||
                    document.getElementById("disp-" + id);
                let rawQty = qtyEl
                    ? qtyEl.value !== undefined
                        ? qtyEl.value
                        : qtyEl.innerText
                    : "";
                let qty = parseInt(String(rawQty).replace(/\D/g, ""), 10);
                if (isNaN(qty)) qty = 0;
                // if user ticked visible checkbox but qty is 0 -> assume 1
                if (el.checked && qty === 0) qty = 1;
                if (qty > 0) {
                    addHidden("services[]", id, {
                        "data-dynamic-service": "1",
                    });
                    addHidden("service_quantity[" + id + "]", qty, {
                        "data-dynamic-qty": "1",
                    });
                }
            });

        // hidden srv-check inputs (gazebo and others)
        document.querySelectorAll('input[id^="srv-check-"]').forEach((el) => {
            const id = el.value;
            const qtyEl =
                document.getElementById("srv-qty-" + id) ||
                document.getElementById("display-qty-" + id) ||
                document.getElementById("disp-" + id);
            let rawQty = qtyEl
                ? qtyEl.value !== undefined
                    ? qtyEl.value
                    : qtyEl.innerText
                : "";
            let qty = parseInt(String(rawQty).replace(/\D/g, ""), 10);
            if (isNaN(qty)) qty = 0;
            if (el.checked && qty === 0) qty = 1;
            if (el.checked && qty > 0) {
                addHidden("services[]", id, { "data-dynamic-service": "1" });
                addHidden("service_quantity[" + id + "]", qty, {
                    "data-dynamic-qty": "1",
                });
            }
        });

        // counters (safety: pick up any visible counter elements)
        document
            .querySelectorAll('[id^="display-qty-"], [id^="disp-"]')
            .forEach((el) => {
                const mm = el.id.match(/(?:display-qty-|disp-)(\d+)/);
                if (!mm) return;
                const id = mm[1];
                let raw =
                    (el.value !== undefined ? el.value : el.innerText) || "0";
                let qty = parseInt(String(raw).replace(/\D/g, ""), 10);
                if (isNaN(qty)) qty = 0;
                if (qty > 0) {
                    if (
                        !bookingForm.querySelector(
                            'input[name="services[]"][value="' + id + '"]'
                        )
                    )
                        addHidden("services[]", id, {
                            "data-dynamic-service": "1",
                        });
                    addHidden("service_quantity[" + id + "]", qty, {
                        "data-dynamic-qty": "1",
                    });
                }
            });

        // fallback hidden patterns
        document
            .querySelectorAll('input[id^="hidden_srv_"], input[id^="svc-"]')
            .forEach((h) => {
                const val = h.value;
                if (!val) return;
                const id = String(val);
                const qEl =
                    document.getElementById("hidden_qty_" + id) ||
                    document.getElementById("srv-qty-" + id) ||
                    document.getElementById("qty-" + id);
                let qv = qEl
                    ? qEl.value !== undefined
                        ? qEl.value
                        : qEl.innerText
                    : "";
                let qty = parseInt(String(qv).replace(/\D/g, ""), 10);
                if (isNaN(qty)) qty = 0;
                // if hidden srv exists but qty==0, assume 1 (only if the element appears intentionally)
                if (qty === 0) qty = 1;
                if (
                    !bookingForm.querySelector(
                        'input[name="services[]"][value="' + id + '"]'
                    )
                )
                    addHidden("services[]", id, {
                        "data-dynamic-service": "1",
                    });
                addHidden("service_quantity[" + id + "]", qty || 0, {
                    "data-dynamic-qty": "1",
                });
            });

        const hasServiceHidden =
            bookingForm.querySelectorAll('input[name="services[]"]').length > 0;
        const hasKamarHidden =
            bookingForm.querySelectorAll(
                'input[name="kamar_ids[]"], input[name="selected_rooms[]"]'
            ).length > 0;
        if (hasServiceHidden && !hasKamarHidden)
            addHidden("service_only", "1", { "data-dynamic-flag": "1" });
    }

    bookingForm.addEventListener("submit", function (e) {
        try {
            buildServiceHiddenInputs();
        } catch (err) {
            console.error(err);
        }
    });

    Array.from(
        bookingForm.querySelectorAll(
            'button[type="submit"], input[type="submit"]'
        )
    ).forEach((btn) => {
        btn.addEventListener("click", function (evt) {
            try {
                buildServiceHiddenInputs();
            } catch (err) {
                console.error(err);
            }
        });
    });
})();

// run initial calc after DOM ready
document.addEventListener("DOMContentLoaded", function () {
    recalcTotals();
});
