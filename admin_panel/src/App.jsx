import { Link } from "react-router-dom";
import "./App.css";

function App() {
  return (
    <div className="container">
      <h1>Админ-панель</h1>
      <nav>
        <ul>
          <li><Link to="/dashboard">📊 Дашборд</Link></li>
          <li><Link to="/orders">📦 Заказы</Link></li>
          <li><Link to="/cars">🚗 Автомобили</Link></li>
        </ul>
      </nav>
    </div>
  );
}

export default App;
