document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("heroGallery");
    const items = document.querySelectorAll(".gallery-item");

    function updateZoom() {
        const center = container.scrollLeft + container.offsetWidth / 2;

        items.forEach(item => {
            const boxCenter = item.offsetLeft + item.offsetWidth / 2;

            const distance = Math.abs(center - boxCenter);

            const maxDistance = container.offsetWidth / 2;

            let scale = 1 - (distance / maxDistance); 
            scale = Math.max(0.75, scale); // minimal scale

            item.style.transform = `scale(${scale})`;
        });
    }

    container.addEventListener("scroll", updateZoom);

    updateZoom(); // initial
});