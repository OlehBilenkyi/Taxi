<?php
session_start();
require_once '../config/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($pdo)) {
    die("Ошибка: нет соединения с базой данных.");
}

try {
    $stmt = $pdo->prepare("SELECT * FROM cars_rental");
    $stmt->execute();
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Ошибка базы данных: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
   <style>
  :root {
      --primary-color: #2A5C83;
      --secondary-color: #5BA4E6;
      --success-color: #4CAF50;
      --danger-color: #f44336;
      --hover-light: rgba(0,0,0,0.05);
      --border-radius: 8px;
      --box-shadow: 0 2px 15px rgba(0,0,0,0.1);
  }

  body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      min-height: 100vh;
      padding: 2rem;
  }

  h2 {
      color: var(--primary-color);
      margin-bottom: 2rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
  }

  .tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
      background: white;
      padding: 1rem;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
  }

  .tab {
      padding: 1rem 2rem;
      cursor: pointer;
      border-radius: var(--border-radius);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: #6c757d;
  }

  .tab:hover {
      background: var(--hover-light);
      transform: translateY(-2px);
  }

  .tab.active {
      background: var(--primary-color);
      color: white;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  }

  .search-container {
      margin-bottom: 2rem;
      position: relative;
  }

  .search-container input {
      padding: 1rem 1rem 1rem 3rem;
      border: 2px solid #dee2e6;
      border-radius: var(--border-radius);
      width: 100%;
      transition: all 0.3s ease;
      background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="%236c757d" d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"/></svg>');
      background-repeat: no-repeat;
      background-position: 1rem center;
  }

  .search-container input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(42,92,131,0.1);
      outline: none;
  }

  .cars-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1.5rem;
      padding: 1rem 0;
  }

  .car-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: transform 0.2s, box-shadow 0.2s;
      overflow: hidden;
      display: flex;
      flex-direction: column;
  }

  .car-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.12);
  }

  .car-image {
      height: 200px;
      position: relative;
      background: #f8f9fa;
  }

  .car-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 12px 12px 0 0;
  }

  .car-badge {
      position: absolute;
      top: 12px;
      right: 12px;
      background: rgba(0,0,0,0.7);
      color: white;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.85em;
  }

  .car-content {
      padding: 1.5rem;
      flex-grow: 1;
  }

  .car-title {
      font-size: 1.2em;
      margin-bottom: 0.5rem;
      color: var(--primary-color);
  }

  .car-details {
      list-style: none;
      padding: 0;
      margin: 0 0 1rem 0;
      color: #6c757d;
  }

  .car-details li {
      display: flex;
      justify-content: space-between;
      padding: 0.3rem 0;
      font-size: 0.95em;
  }

  .car-details strong {
      color: #495057;
  }

  .card-actions {
      display: flex;
      gap: 0.5rem;
      margin-top: auto;
      padding: 0 1.5rem 1.5rem;
  }

  .status-badge {
      display: inline-block;
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-size: 0.85em;
      font-weight: 500;
  }

  .status-available {
      background: #d4edda;
      color: #155724;
  }

  .status-rented {
      background: #f8d7da;
      color: #721c24;
  }

  .btn-group {
      display: flex;
      gap: 0.5rem;
  }

  .btn {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: var(--border-radius);
      cursor: pointer;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 500;
  }

  .btn-primary {
      background: var(--primary-color);
      color: white;
  }

  .btn-primary:hover {
      background: #234866;
      transform: translateY(-1px);
  }

  .btn-success {
      background: var(--success-color);
      color: white;
  }

  .btn-danger {
      background: var(--danger-color);
      color: white;
  }

  .btn-icon {
      padding: 0.5rem;
      border-radius: 50%;
  }

  .pagination {
      margin-top: 2rem;
      display: flex;
      justify-content: center;
      gap: 0.5rem;
  }

  .page-item {
      padding: 0.5rem 1rem;
      border: 1px solid #dee2e6;
      border-radius: var(--border-radius);
      cursor: pointer;
      transition: all 0.2s ease;
  }

  .page-item:hover {
      background: var(--primary-color);
      color: white;
      border-color: var(--primary-color);
  }

  .page-item.active {
      background: var(--primary-color);
      color: white;
      border-color: var(--primary-color);
  }

  @media (max-width: 768px) {
      body {
          padding: 1rem;
      }

      .cars-grid {
          grid-template-columns: 1fr;
      }

      .car-image {
          height: 180px;
      }
  }

  @media (min-width: 1200px) {
      .cars-grid {
          grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      }
  }

  /* Анимации */
  @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
  }

  .tab-content > div {
      animation: fadeIn 0.3s ease-out;
  }

  /* Модальное окно улучшения */
  .modal-content {
      border-radius: var(--border-radius);
      border: none;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  }

  .modal-header {
      background: var(--primary-color);
      color: white;
      border-radius: var(--border-radius) var(--border-radius) 0 0;
  }

  /* Стили для модального окна редактирования */
  .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.7);
  }

  .modal-content {
      background: #fff;
      margin: 2% auto;
      padding: 25px;
      width: 80%;
      max-width: 900px;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      animation: modalOpen 0.3s ease;
  }

  .modal-body {
      display: flex;
      gap: 30px;
      margin: 20px 0;
  }

  .form-column {
      flex: 1;
      min-width: 300px;
  }

  .images-column {
      flex: 1;
      border-left: 1px solid #eee;
      padding-left: 30px;
  }

  .form-group {
      margin-bottom: 20px;
  }

  .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #333;
  }

  .form-input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
  }

  .form-input:focus {
      border-color: #007bff;
      outline: none;
      box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
  }

  .form-select {
      width: 100%;
      padding: 10px;
      background: white;
  }

  .upload-btn {
      display: inline-block;
      background: #f0f0f0;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.2s;
  }

  .upload-btn:hover {
      background: #e0e0e0;
  }

  #imageUpload {
      display: none;
  }

  .images-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
      gap: 10px;
      margin-top: 15px;
  }

  .image-item {
      position: relative;
      border-radius: 4px;
      overflow: hidden;
      transition: transform 0.2s;
  }

  .image-item:hover {
      transform: translateY(-3px);
  }

  .image-item img {
      width: 100%;
      height: 100px;
      object-fit: cover;
      border: 1px solid #ddd;
  }

  .delete-image {
      position: absolute;
      top: 5px;
      right: 5px;
      background: rgba(255,0,0,0.8);
      color: white;
      border: none;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      cursor: pointer;
      display: none;
  }

  .image-item:hover .delete-image {
      display: block;
  }

  .modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid #eee;
  }

  @keyframes modalOpen {
      from { transform: scale(0.95); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
  }

  /* Стили для галереи изображений */
  .image-gallery {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 20px;
  }

  .image-gallery img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      cursor: pointer;
      transition: transform 0.2s;
  }

  .image-gallery img:hover {
      transform: scale(1.1);
  }
</style>

</head>
<body>
    <h2>Админ-панель</h2>

    <input type="text" id="searchInput" placeholder="Поиск автомобилей...">

    <div class="tabs">
        <div class="tab active" data-tab="car-list">Список авто</div>
        <div class="tab" data-tab="rental-requests">Заявки на аренду</div>
    </div>

    <div class="tab-content">
        <div id="car-list" class="car-list active">
            <div class="cars-grid">
                <?php foreach ($cars as $car): ?>
                    <div class="car-card">
                        <div class="car-image">
                            <?php
                            $images = json_decode($car['images'], true);
                            if (!is_array($images)) {
                                $images = explode(',', $car['images']);
                            }
                            $firstImage = !empty($images[0]) ? trim($images[0], '[]"') : null;
                            ?>
                            <?php if ($firstImage): ?>
                                <img src="https://test1.foodcasecatering.net/uploads_img/<?= rawurlencode(basename($firstImage)) ?>"
                                     alt="<?= htmlspecialchars($car['name']) ?>" loading="lazy">
                            <?php else: ?>
                                <div class="no-image">Нет фото</div>
                            <?php endif; ?>
                            <div class="car-badge">ID: <?= htmlspecialchars($car['id']) ?></div>
                        </div>
                        <div class="car-content">
                            <h3><?= htmlspecialchars($car['name']) ?></h3>
                            <ul class="car-details">
                                <li><span>Версия:</span> <strong><?= htmlspecialchars($car['version'] ?? 'Не указано') ?></strong></li>
                                <li><span>Цена/день:</span> <strong>$<?= htmlspecialchars($car['rental_price'] ?? 'Не указана') ?></strong></li>
                                <li>
                                    <span>Статус:</span>
                                    <span class="status-badge status-<?= $car['is_available'] ? 'available' : 'rented' ?>">
                                        <?= $car['is_available'] ? 'Доступен' : 'Арендован' ?>
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-primary edit-car" data-id="<?= htmlspecialchars($car['id']) ?>">Редактировать</button>
                            <button class="btn btn-danger delete-car" data-id="<?= htmlspecialchars($car['id']) ?>">Удалить</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="rental-requests" class="rental-requests">
            <?php include 'rental_requests.php'; ?>
        </div>
    </div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Редактировать автомобиль</h2>

        <div class="modal-body">
            <!-- Левая колонка - Форма -->
            <div class="form-column">
                <form id="editCarForm" class="car-form">
                    <input type="hidden" id="carId" name="car_id">

                    <div class="form-group">
                        <label class="form-label">Марка:</label>
                        <input type="text" id="make" name="make" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Модель:</label>
                        <input type="text" id="model" name="model" class="form-input" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group half-width">
                            <label class="form-label">Год выпуска:</label>
                            <input type="number" id="year" name="year" class="form-input" min="1900" max="2024" required>
                        </div>

                        <div class="form-group half-width">
                            <label class="form-label">Цена/день ($):</label>
                            <input type="number" id="rental_price" name="rental_price" class="form-input" step="0.01" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Статус:</label>
                        <select id="is_available" name="is_available" class="form-select" required>
                            <option value="1">Доступен</option>
                            <option value="0">Арендован</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Правая колонка - Изображения -->
            <div class="images-column">
                <div class="upload-section">
                    <h3>Галерея изображений</h3>
                    <label class="upload-btn">
                        <input type="file" id="imageUpload" multiple accept="image/*">
                        Загрузить новые фото
                    </label>
                </div>

                <div id="imageGallery" class="image-gallery">
                    <!-- Прелоадер для изображений -->
                    <div class="gallery-loader" style="display: none;">
                        <div class="loader"></div>
                    </div>

                    <!-- Контейнер для превью изображений -->
                    <div class="images-grid"></div>

                    <!-- Сообщение при отсутствии изображений -->
                    <div class="no-images">Нет загруженных изображений</div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Отмена</button>
            <button type="submit" form="editCarForm" class="btn btn-primary">Сохранить изменения</button>
        </div>
    </div>
</div>



  <script>
document.addEventListener('DOMContentLoaded', () => {
    setupTabs();
    setupSearch();
    setupEditCarHandlers();
    setupModalHandlers();
    setupImageUpload();
});

// Функция переключения вкладок
function setupTabs() {
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelector('.tab.active')?.classList.remove('active');
            this.classList.add('active');
            document.querySelectorAll('.tab-content > div').forEach(content => {
                content.classList.toggle('active', content.id === this.dataset.tab);
            });
        });
    });
}

// Функция поиска автомобилей
function setupSearch() {
    document.getElementById('searchInput')?.addEventListener('input', function () {
        const searchTerm = this.value.trim().toLowerCase();
        document.querySelectorAll('.car-card').forEach(car => {
            car.style.display = car.textContent.toLowerCase().includes(searchTerm) ? 'flex' : 'none';
        });
    });
}

// Функция загрузки данных автомобиля
async function loadCarData(carId) {
    try {
        const response = await fetch(`/admin/get_cars.php?id=${carId}`);
        if (!response.ok) throw new Error(`Ошибка сервера: ${response.status}`);
        
        const car = await response.json();
        if (!car || car.error || !car.id) {
            alert("Ошибка: данные автомобиля не получены!");
            return;
        }
        
        fillCarForm(car);
        displayCarImages(car.images);
        openModal();
    } catch (error) {
        console.error('Ошибка загрузки авто:', error);
        alert('Не удалось загрузить данные автомобиля.');
    }
}

// Функция заполнения формы
function fillCarForm(car) {
    document.getElementById('carId').value = car.id;
    document.getElementById('make').value = car.make || '';
    document.getElementById('model').value = car.model || '';
    document.getElementById('year').value = car.year || '';
    document.getElementById('rental_price').value = car.rental_price || '';
    document.getElementById('is_available').value = car.is_available ? '1' : '0';
}

// Функция отображения изображений автомобиля
function displayCarImages(images) {
    const gallery = document.querySelector('#imageGallery .images-grid');
    gallery.innerHTML = '';
    
    if (!images || images.length === 0) {
        document.querySelector('#imageGallery .no-images').style.display = 'block';
        return;
    }
    
    images.forEach((img, index) => {
        const wrapper = document.createElement('div');
        wrapper.className = 'image-item';
        wrapper.innerHTML = `
            <img src="${img.startsWith('http') ? img : `/uploads_img/${img}`}" alt="Фото авто ${index + 1}" class="thumbnail">
            <button class="delete-image" data-index="${index}">&times;</button>
        `;
        gallery.appendChild(wrapper);
    });
    
    document.querySelectorAll('.delete-image').forEach(btn => {
        btn.addEventListener('click', () => deleteImage(btn.dataset.index));
    });
}

// Функция загрузки изображений
function setupImageUpload() {
    document.getElementById('imageUpload')?.addEventListener('change', function (e) {
        const files = Array.from(e.target.files);
        if (files.length === 0) return;

        const gallery = document.querySelector('#imageGallery .images-grid');
        const loader = document.querySelector('.gallery-loader');
        loader.style.display = 'flex';

        setTimeout(() => {
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'image-item';
                    wrapper.innerHTML = `
                        <img src="${e.target.result}" alt="Новое фото">
                        <button class="delete-image">&times;</button>
                    `;
                    gallery.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
            loader.style.display = 'none';
            document.querySelector('#imageGallery .no-images').style.display = 'none';
        }, 1000);
    });
}

// Удаление изображения
function deleteImage(index) {
    if (confirm('Удалить это изображение?')) {
        const carId = document.getElementById('carId').value;
        fetch(`/admin/delete_image.php?car=${carId}&index=${index}`, { method: 'DELETE' })
            .then(response => {
                if (response.ok) {
                    document.querySelector(`.image-item:nth-child(${+index + 1})`).remove();
                }
            });
    }
}

// Обработчик отправки формы
document.getElementById('editCarForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('/admin/update_car.php', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            alert('Изменения успешно сохранены!');
            closeModal();
            location.reload();
        } else {
            throw new Error('Ошибка сохранения');
        }
    } catch (error) {
        console.error('Ошибка:', error);
        alert('Ошибка при сохранении данных');
    }
});

// Обработчики модального окна
function setupEditCarHandlers() {
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('edit-car')) {
            loadCarData(event.target.dataset.id);
        }
    });
}

function setupModalHandlers() {
    const modal = document.getElementById('editModal');
    document.querySelector('.close')?.addEventListener('click', closeModal);
    window.addEventListener('keydown', e => e.key === 'Escape' && closeModal());
    window.addEventListener('click', e => e.target === modal && closeModal());
}

function openModal() {
    document.getElementById('editModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}


</script>

</body>
</html>
