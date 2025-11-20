document.addEventListener('DOMContentLoaded', () => {
  const productGrid = document.getElementById('product-grid');
  const orderSummaryBox = document.getElementById('order-summary-box');
  const hiddenOrderContainer = document.getElementById('hidden-order-inputs');
  const checkoutForm = document.getElementById('checkout-form');
  const clearBtn = document.querySelector('.clear');
  const voidBtn = document.querySelector('.void');

  let orderItems = [];
  let selectedIndex = -1; // Track selected order item index

  // Function to update session storage with current order items
  function updateSession() {
    sessionStorage.setItem('orderItems', JSON.stringify(orderItems));
  }

  // Function to load order items from session storage
  function loadSession() {
    const storedItems = sessionStorage.getItem('orderItems');
    if (storedItems) {
      orderItems = JSON.parse(storedItems);
    }
  }

  // Load order items on page load
  loadSession();

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
    updateSession();
  });

  // Clear all orders
  clearBtn.addEventListener('click', () => {
    orderItems.length = 0;
    selectedIndex = -1;
    renderOrderSummary();
    updateSession();
  });

  // Open popup when clicking VOID button, but only if there's an order
  voidBtn.addEventListener('click', function(e) {
    e.preventDefault();
    if (orderItems.length === 0) {
      alert('No order to void.');
    } else {
      document.getElementById('voidModal').style.display = 'flex';
    }
  });

  // Close popup
  document.getElementById('cancelVoid').addEventListener('click', function() {
    document.getElementById('voidModal').style.display = 'none';
  });

  // Confirm password and void selected item or entire order
  document.getElementById('confirmVoid').addEventListener('click', function() {
    const enteredPass = document.getElementById('voidPassword').value;

    // ADMIN PASSWORD (can later be verified server-side)
    const correctPass = 'admin123';

    if (enteredPass === correctPass) {
      document.getElementById('voidModal').style.display = 'none';

      if (selectedIndex !== -1) {
        // Void selected item
        orderItems.splice(selectedIndex, 1);
        selectedIndex = -1;
      } else {
        // Void entire order
        orderItems.length = 0;
        selectedIndex = -1;
      }

      renderOrderSummary();
      updateSession();

      // OPTIONAL: Send void request to server to clear session/order
      fetch('void_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'pin=' + encodeURIComponent(enteredPass)
      })
      .then(response => response.text())
      .then(data => console.log(data))
      .catch(err => console.error(err));

    } else {
      alert('Incorrect password!');
    }
  });

  // Close modal if user clicks outside the modal content
  window.addEventListener('click', function(e) {
    const modal = document.getElementById('voidModal');
    if (e.target === modal) {
      modal.style.display = 'none';
    }
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
