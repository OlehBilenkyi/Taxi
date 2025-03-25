<!-- car_form.php -->
<div id="car-form-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden">
  <div class="bg-white rounded-lg w-full max-w-md">
    <div class="flex justify-between items-center p-4 border-b">
      <h2 id="form-title" class="text-xl font-semibold"></h2>
      <button id="close-button" class="text-gray-500 hover:text-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <form id="car-form" class="p-4">
      <input type="hidden" id="car-id" name="car_id">
      <input type="hidden" id="deleted_images" name="deleted_images" value="[]">
      <input type="file" id="images" name="images[]" multiple style="display: none;">

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Name</label>
          <input type="text" id="car-name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Description</label>
          <textarea id="car-description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3" required></textarea>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Version</label>
          <input type="text" id="car-version" name="version" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Rental Price</label>
            <input type="number" id="car-rental-price" name="rental_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Deposit Price</label>
            <input type="number" id="car-deposit-price" name="deposit_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Image URL</label>
          <input type="url" id="car-image-url" name="image_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Status</label>
          <select id="car-status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="available">Available</option>
            <option value="rented">Rented</option>
          </select>
        </div>
      </div>
      <div class="mt-6 flex justify-end gap-2">
        <button type="button" id="cancel-button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">Cancel</button>
        <button type="submit" id="submit-button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
  const carFormModal = document.getElementById('car-form-modal');
  const carForm = document.getElementById('car-form');
  const closeButton = document.getElementById('close-button');
  const cancelButton = document.getElementById('cancel-button');
  const submitButton = document.getElementById('submit-button');
  const formTitle = document.getElementById('form-title');

  function openCarForm(car = null) {
    carFormModal.classList.remove('hidden');
    formTitle.textContent = car ? 'Edit Car' : 'Add New Car';
    if (car) {
      document.getElementById('car-id').value = car.id;
      document.getElementById('car-name').value = car.name;
      document.getElementById('car-description').value = car.description;
      document.getElementById('car-version').value = car.version;
      document.getElementById('car-rental-price').value = car.rental_price;
      document.getElementById('car-deposit-price').value = car.deposit_price;
      document.getElementById('car-image-url').value = car.image_url;
      document.getElementById('car-status').value = car.status;
    } else {
      carForm.reset();
    }
  }

  function closeCarForm() {
    carFormModal.classList.add('hidden');
  }

  closeButton.addEventListener('click', closeCarForm);
  cancelButton.addEventListener('click', closeCarForm);

  carForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(carForm);

    fetch('save_car.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        closeCarForm();
        fetchCars(); // Обновить список автомобилей
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  });
</script>
