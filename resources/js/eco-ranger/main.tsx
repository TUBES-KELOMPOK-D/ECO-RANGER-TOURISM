import React from 'react';
import { createRoot } from 'react-dom/client';
import './index.css';
import App from './App';
import MarkerDetail from './sections/MarkerDetail';
import LoginPage from './sections/LoginPage';

const ecoRangerContainer = document.getElementById('eco-ranger-root');
const markerDetailContainer = document.getElementById('marker-detail-root');
const loginContainer = document.getElementById('login-root');

if (ecoRangerContainer) {
  const root = createRoot(ecoRangerContainer);
  root.render(
    <React.StrictMode>
      <App />
    </React.StrictMode>
  );
} else if (markerDetailContainer) {
  const root = createRoot(markerDetailContainer);
  root.render(
    <React.StrictMode>
      <MarkerDetail />
    </React.StrictMode>
  );
} else if (loginContainer) {
  const root = createRoot(loginContainer);
  root.render(
    <React.StrictMode>
      <LoginPage />
    </React.StrictMode>
  );
}
