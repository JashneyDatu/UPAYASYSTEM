document.addEventListener('DOMContentLoaded', () => {
  const productGrid = document.getElementById('product-grid');
  const orderSummaryBox = document.getElementById('order-summary-box');
  const hiddenOrderContainer = document.getElementById('hidden-order-inputs');
  const checkoutForm = document.getElementById('checkout-form');
  const clearBtn = document.querySelector('.clear');
  const voidBtn = document.querySelector('.void');

  const orderItems = [];
  let selectedIndex = -1; // Track selected order item index

  // Render order summary
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
        li.classList.add('selected'); // highlight selected item
      }

      li.addEventListener('click', () => {
        selectedIndex = (selectedIndex === index) ? -1 : index; // toggle selection
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
    selectedIndex = -1;
    renderOrderSummary();
  });

  // Clear all orders
  clearBtn.addEventListener('click', () => {
    orderItems.length = 0;
    selectedIndex = -1;
    renderOrderSummary();
  });

  // Void (remove) selected item
  voidBtn.addEventListener('click', () => {
    if (selectedIndex === -1) {
      alert('Please select an item in the order summary to void.');
      return;
    }
    orderItems.splice(selectedIndex, 1);
    selectedIndex = -1;
    renderOrderSummary();
  });

  // Before submitting form, add hidden inputs for PHP
  checkoutForm.addEventListener('submit', e => {
    hiddenOrderContainer.innerHTML = ''; // clear previous inputs
    if (orderItems.length === 0) {
      alert('Please add at least one item to your order.');
      e.preventDefault();
      return;
    }

    orderItems.forEach((item, index) => {
      // Name
      const inputName = document.createElement('input');
      inputName.type = 'hidden';
      inputName.name = `items[${index}][name]`;
      inputName.value = item.name;
      hiddenOrderContainer.appendChild(inputName);

      // Price
      const inputPrice = document.createElement('input');
      inputPrice.type = 'hidden';
      inputPrice.name = `items[${index}][price]`;
      inputPrice.value = item.price;
      hiddenOrderContainer.appendChild(inputPrice);

      // Quantity
      const inputQty = document.createElement('input');
      inputQty.type = 'hidden';
      inputQty.name = `items[${index}][qty]`;
      inputQty.value = item.qty;
      hiddenOrderContainer.appendChild(inputQty);
    });
  });

  // Initial render
  renderOrderSummary();
});
