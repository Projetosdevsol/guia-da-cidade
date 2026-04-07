import React from 'react';
import { BrowserRouter as Router, Routes, Route, Link, useLocation } from 'react-router-dom';
import { AnimatePresence } from 'motion/react';
import { AlertTriangle } from 'lucide-react';
import { AuthProvider, useAuth } from './presentation/AuthContext';
import { Navbar } from './components/Navbar';
import { Home } from './pages/Home';
import { Directory } from './pages/Directory';
import { Admin } from './pages/Admin';
import { LoginPage } from './pages/LoginPage';
import { Dashboard } from './pages/Dashboard';
import { ProtectedRoute } from './components/ProtectedRoute';
import { PageTransition } from './components/PageTransition';

import { UserService, SettingsService } from './infrastructure/firebaseServices';
import { User, SystemSettings } from './domain/entities';

const AnimatedRoutes = () => {
  const location = useLocation();
  const { user } = useAuth();
  const [settings, setSettings] = React.useState<SystemSettings | null>(null);

  React.useEffect(() => {
    SettingsService.getSettings().then(setSettings);
  }, []);

  if (settings?.maintenanceMode && user?.role !== 'admin') {
    return (
      <div className="flex min-h-[60vh] flex-col items-center justify-center text-center p-10">
        <div className="mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-amber-50 text-amber-600">
          <AlertTriangle className="h-10 w-10" />
        </div>
        <h1 className="text-3xl font-black tracking-tight text-slate-900">Modo Manutenção</h1>
        <p className="mt-4 max-w-md text-slate-500">
          O {settings.siteName} está passando por uma manutenção programada. Voltaremos em breve!
        </p>
      </div>
    );
  }
  
  return (
    <AnimatePresence mode="wait">
      <Routes location={location} key={location.pathname}>
        <Route 
          path="/" 
          element={
            <PageTransition>
              <Home />
            </PageTransition>
          } 
        />
        <Route 
          path="/empresas" 
          element={
            <PageTransition>
              <div className="section-container py-12">
                <Directory />
              </div>
            </PageTransition>
          } 
        />
        <Route 
          path="/login" 
          element={
            <PageTransition>
              <div className="section-container py-20">
                <LoginPage />
              </div>
            </PageTransition>
          } 
        />
        <Route 
          path="/dashboard" 
          element={
            <ProtectedRoute>
              <PageTransition>
                <div className="section-container py-12">
                  <Dashboard />
                </div>
              </PageTransition>
            </ProtectedRoute>
          } 
        />
        <Route 
          path="/admin" 
          element={
            <ProtectedRoute adminOnly>
              <PageTransition>
                <div className="section-container py-12">
                  <Admin />
                </div>
              </PageTransition>
            </ProtectedRoute>
          } 
        />
      </Routes>
    </AnimatePresence>
  );
};

export default function App() {
  return (
    <AuthProvider>
      <Router>
        <div className="min-h-screen bg-white selection:bg-blue-100 selection:text-blue-900">
          <Navbar />
          <main className="min-h-[calc(100vh-64px)] overflow-hidden">
            <AnimatedRoutes />
          </main>
          
          <footer className="border-t border-slate-100 bg-slate-50 py-20">
            <div className="section-container">
              <div className="grid gap-12 lg:grid-cols-4">
                <div className="lg:col-span-2">
                  <Link to="/" className="inline-block font-serif text-2xl font-bold tracking-tight text-slate-900">
                    Guia da<span className="text-blue-600">Cidade</span>
                  </Link>
                  <p className="mt-6 max-w-sm text-sm leading-relaxed text-slate-500">
                    A plataforma definitiva para informações locais, notícias e conexões comerciais. Nosso objetivo é fortalecer a comunidade através da informação de qualidade.
                  </p>
                </div>
                <div>
                  <h4 className="mb-6 text-xs font-bold tracking-widest uppercase text-slate-900">Navegação</h4>
                  <ul className="space-y-4 text-sm text-slate-500">
                    <li><Link to="/" className="hover:text-blue-600 transition-colors">Notícias</Link></li>
                    <li><Link to="/empresas" className="hover:text-blue-600 transition-colors">Empresas</Link></li>
                    <li><Link to="/login" className="hover:text-blue-600 transition-colors">Minha Conta</Link></li>
                  </ul>
                </div>
                <div>
                  <h4 className="mb-6 text-xs font-bold tracking-widest uppercase text-slate-900">Legal</h4>
                  <ul className="space-y-4 text-sm text-slate-500">
                    <li><a href="#" className="hover:text-blue-600 transition-colors">Termos de Uso</a></li>
                    <li><a href="#" className="hover:text-blue-600 transition-colors">Privacidade</a></li>
                    <li><a href="#" className="hover:text-blue-600 transition-colors">Contato</a></li>
                  </ul>
                </div>
              </div>
              <div className="mt-20 flex flex-col items-center justify-between gap-6 border-t border-slate-200 pt-8 sm:flex-row">
                <p className="text-xs font-medium text-slate-400">
                  © 2026 Guia da Cidade. Uma plataforma white-label para municípios.
                </p>
                <div className="flex gap-6">
                  <div className="h-8 w-8 rounded-full bg-slate-200" />
                  <div className="h-8 w-8 rounded-full bg-slate-200" />
                  <div className="h-8 w-8 rounded-full bg-slate-200" />
                </div>
              </div>
            </div>
          </footer>
        </div>
      </Router>
    </AuthProvider>
  );
}
