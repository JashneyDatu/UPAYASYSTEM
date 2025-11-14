document.addEventListener('DOMContentLoaded', () => {
  const productGrid = document.getElementById('product-grid');
  const orderSummaryBox = document.getElementById('order-summary-box');
  const hiddenOrderContainer = document.getElementById('hidden-order-inputs');
  const checkoutForm = document.getElementById('checkout-form');
  const clearBtn = document.querySelector('.clear');
  const voidBtn = document.querySelector('.void');

  const orderItems = [];
  let selectedIndex = -1; // Track selected order item index

  function renderOrderSummary() {
    if (orderItems.length === 0) {
      orderSummaryBox.innerHTML = '<p>No items added yet.</p>';
      selectedIndex = -1;
      return;
    }

    const ul = document.createElement('ul');
    ul.style.listStyle = 'none';
    ul.style.padding = 0;

    orderItems.forEach((item, index) => {
      const li = document.createElement('li');
      li.textContent = `${item.name} – ₱${item.price} x ${item.qty}`;
      li.style.marginBottom = '8px';
      li.style.cursor = 'pointer';

      if (index === selectedIndex) {
        li.classList.add('selected'); // Add CSS class for highlight
      }

      li.addEventListener('click', () => {
        selectedIndex = (selectedIndex === index) ? -1 : index; // Toggle selection
        renderOrderSummary();
      });

      ul.appendChild(li);
    });

    orderSummaryBox.innerHTML = '';
    orderSummaryBox.appendChild(ul);
  }

  // Add product to order
  productGrid.addEventListener('click', e => {
    const target = e.target.closest('.item');
    if (!target) return;

    const nameText = target.textContent.trim();
    // Parse product name and price from text like "Name - Price"
    const splitIndex = nameText.lastIndexOf(' - ');
    if (splitIndex === -1) return;
    const name = nameText.substring(0, splitIndex);
    const price = Number(nameText.substring(splitIndex + 3));
    if (!name || isNaN(price)) return;

    const existingIndex = orderItems.findIndex(i => i.name === name);
    if (existingIndex !== -1) {
      orderItems[existingIndex].qty++;
    } else {
      orderItems.push({ name, price, qty: 1 });
    }
    selectedIndex = -1; // Clear selection on new item add
    renderOrderSummary();
  });

  // Clear all orders
  clearBtn.addEventListener('click', () => {
  orderItems.length = 0;       // Clears the array
  selectedIndex = -1;          // Resets selection
  renderOrderSummary();        // Re-renders UI
});


  // Void (remove) selected item only
  voidBtn.addEventListener('click', () => {
    if (selectedIndex === -1) {
      alert('Please select an item in the order summary to void.');
      return;
    }
    orderItems.splice(selectedIndex, 1);
    selectedIndex = -1;
    renderOrderSummary();
  });

  // Before form submission, add hidden inputs for order items
  checkoutForm.addEventListener('submit', e => {
    hiddenOrderContainer.innerHTML = ''; // Clear old inputs
    if (orderItems.length === 0) {
      alert('Please add at least one item to your order.');
      e.preventDefault();
      return;
    }
    orderItems.forEach(item => {
      // Name input
      const inputName = document.createElement('input');
      inputName.type = 'hidden';
      inputName.name = `order[${item.name}][name]`;
      inputName.value = item.name;
      hiddenOrderContainer.appendChild(inputName);

      // Price input
      const inputPrice = document.createElement('input');
      inputPrice.type = 'hidden';
      inputPrice.name = `order[${item.name}][price]`;
      inputPrice.value = item.price;
      hiddenOrderContainer.appendChild(inputPrice);

      // Quantity input
      const inputQty = document.createElement('input');
      inputQty.type = 'hidden';
      inputQty.name = `order[${item.name}][qty]`;
      inputQty.value = item.qty;
      hiddenOrderContainer.appendChild(inputQty);
    });
  });

  // Initial render empty summary
  renderOrderSummary();
});
