<!-- car_list.php -->
<div id="car-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

<script>
  let cars = [];
  const carListContainer = document.getElementById('car-list');

  function renderCarList() {
    carListContainer.innerHTML = '';
    cars.forEach((car) => {
      const carElement = document.createElement('div');
      carElement.classList.add('bg-white', 'rounded-lg', 'shadow-md', 'overflow-hidden', 'car-item');
      carElement.innerHTML = `
        <div class="car-images">
            ${car.images.map(image => `<img src="${image}" alt="${car.name}" class="w-full h-48 object-cover">`).join('')}
        </div>
        <div class="details p-4">
          <h3 class="text-lg font-semibold">${car.name}</h3>
          <p class="text-gray-600 text-sm mb-2">${car.description}</p>
          <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
            <span>Version: ${car.version}</span>
            <span>Rental: $${car.rental_price}/day</span>
            <span>Deposit: $${car.deposit_price}</span>
          </div>
          <div class="flex justify-end gap-2">
            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-full" onclick="openEditModal(${car.id})">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4l4 4M4 16l4 4M14 4h6v6M4 4h6v6"></path>
              </svg>
            </button>
            <button class="p-2 text-red-600 hover:bg-red-50 rounded-full" onclick="onDeleteCar(${car.id})">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 4H3m0 0L4 21h16l1-17H3z"></path>
              </svg>
            </button>
          </div>
        </div>
      `;
      carListContainer.appendChild(carElement);
    });
  }

  function onEditCar(carId) {
    const car = cars.find((car) => car.id === carId);
    openCarForm(car);
  }

  function onDeleteCar(carId) {
    if (confirm('Are you sure you want to delete this car?')) {
      fetch('delete_car.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: carId })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          cars = cars.filter(car => car.id !== carId);
          renderCarList();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
    }
  }

  function fetchCars() {
    fetch('get_cars.php')
      .then(response => response.json())
      .then(data => {
        cars = data;
        renderCarList();
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  fetchCars(); // Загружаем список машин при загрузке скрипта
</script>
