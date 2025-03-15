import { Car } from 'lucide-react';

const cars = [
  {
    id: 1,
    name: 'Kia Rio',
    image: 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&q=80',
    price: 1500,
    year: 2022,
    transmission: 'Автомат',
    fuelType: 'Бензин'
  },
  {
    id: 2,
    name: 'Hyundai Solaris',
    image: 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?auto=format&fit=crop&q=80',
    price: 1600,
    year: 2023,
    transmission: 'Автомат',
    fuelType: 'Бензин'
  },
  {
    id: 3,
    name: 'Volkswagen Polo',
    image: 'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?auto=format&fit=crop&q=80',
    price: 1700,
    year: 2022,
    transmission: 'Автомат',
    fuelType: 'Бензин'
  },
];

export function CarsPage() {
  return (
    <div className="container mx-auto px-4 py-8">
      <div className="flex items-center gap-3 mb-8">
        <Car className="h-8 w-8 text-blue-600" />
        <h1 className="text-3xl font-bold">Наш автопарк</h1>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {cars.map((car) => (
          <div key={car.id} className="bg-white rounded-lg shadow-md overflow-hidden">
            <div className="h-48 overflow-hidden">
              <img
                src={car.image}
                alt={car.name}
                className="w-full h-full object-cover"
              />
            </div>
            <div className="p-4">
              <h3 className="text-xl font-semibold mb-2">{car.name}</h3>
              <div className="space-y-2 text-gray-600">
                <p>Год выпуска: {car.year}</p>
                <p>Коробка: {car.transmission}</p>
                <p>Топливо: {car.fuelType}</p>
                <p className="text-lg font-semibold text-blue-600 mt-4">
                  {car.price} ₽/день
                </p>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
