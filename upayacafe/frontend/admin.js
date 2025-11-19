document.addEventListener("DOMContentLoaded", () => {
    const voidButton = document.querySelector(".void");
    const modal = document.getElementById("pin-modal");
    const closeModal = document.querySelector(".modal .close");
    const submitPin = document.getElementById("pin-submit");
    const pinInput = document.getElementById("admin-pin-input");

    const ADMIN_PIN = "1234"; // Replace with secure server-side verification later

    // Show modal on Void click
    voidButton.addEventListener("click", (e) => {
        e.preventDefault();
        pinInput.value = ""; // Clear previous input
        modal.style.display = "flex";
    });

    // Close modal when clicking the X
    closeModal.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Submit PIN
    submitPin.addEventListener("click", () => {
        const pin = pinInput.value;
        if (pin === ADMIN_PIN) {
            modal.style.display = "none";
            // Void the order
            const orderSummary = document.getElementById("order-summary-box");
            orderSummary.innerHTML = "<p>Order has been voided.</p>";
            
            // Optionally, clear session via AJAX
            fetch("void_order.php", { method: "POST" })
                .then(response => response.text())
                .then(data => console.log(data))
                .catch(err => console.error(err));
        } else {
            alert("Incorrect PIN! You cannot void the order.");
        }
    });

    // Close modal if user clicks outside content
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});
