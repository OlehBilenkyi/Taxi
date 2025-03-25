<!-- rental_requests.php -->
<div id="rental-requests" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

<script>
  const rentalRequests = [
    {
      id: '1',
      carId: '1',
      customerName: 'John Doe',
      customerPhone: '123-456-7890',
      startDate: '2023-03-01',
      endDate: '2023-03-05',
      status: 'pending',
      createdAt: '2023-02-25',
    },
    // Добавьте больше объектов запросов на аренду по мере необходимости
  ];

  const rentalRequestsContainer = document.getElementById('rental-requests');

  function renderRentalRequests() {
    rentalRequestsContainer.innerHTML = '';
    rentalRequests.forEach((request) => {
      const requestElement = document.createElement('div');
      requestElement.classList.add('bg-white', 'rounded-lg', 'shadow-md', 'overflow-hidden', 'rental-request');
      requestElement.innerHTML = `
        <h3 class="p-4 text-lg font-semibold">${request.customerName}</h3>
        <p class="p-4 text-gray-600 text-sm mb-2">Phone: ${request.customerPhone}</p>
        <p class="p-4 text-gray-600 text-sm mb-2">Dates: ${request.startDate} - ${request.endDate}</p>
        <p class="p-4 text-gray-600 text-sm mb-2">Status: ${request.status}</p>
        <div class="flex justify-end gap-2 p-4">
          <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-full" onclick="onViewRequest('${request.id}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4l4 4M4 16l4 4M14 4h6v6M4 4h6v6"></path>
            </svg>
          </button>
          <button class="p-2 text-red-600 hover:bg-red-50 rounded-full" onclick="onDeleteRequest('${request.id}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 4H3m0 0L4 21h16l1-17H3z"></path>
            </svg>
          </button>
        </div>
      `;
      rentalRequestsContainer.appendChild(requestElement);
    });
  }

  function onViewRequest(requestId) {
    const request = rentalRequests.find((req) => req.id === requestId);
    console.log('Viewing request:', request);
  }

  function onDeleteRequest(requestId) {
    const index = rentalRequests.findIndex((req) => req.id === requestId);
    if (index !== -1) {
      rentalRequests.splice(index, 1);
      renderRentalRequests();
    }
  }

  renderRentalRequests(); // Рендерим запросы на аренду при загрузке скрипта
</script>
