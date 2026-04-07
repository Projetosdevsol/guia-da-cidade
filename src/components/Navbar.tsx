import React from 'react';
import { Link, NavLink } from 'react-router-dom';
import { useAuth } from '../presentation/AuthContext';
import { Menu, X } from 'lucide-react';
import { cn } from '../lib/utils';

export const Navbar: React.FC = () => {
  const { user, logout } = useAuth();
  const [isMenuOpen, setIsMenuOpen] = React.useState(false);

  return (
    <nav className="glass-nav">
      <div className="section-container flex h-16 items-center justify-between">
        <div className="flex items-center gap-10">
          <Link to="/" className="group flex items-center gap-2">
            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-white transition-transform group-hover:scale-110">
              <span className="font-serif text-lg font-bold">G</span>
            </div>
            <span className="font-serif text-xl font-bold tracking-tight text-slate-900">
              Guia da<span className="text-blue-600">Cidade</span>
            </span>
          </Link>
          <div className="hidden md:flex md:items-center md:gap-8">
            <NavLink 
              to="/" 
              className={({ isActive }) => cn(
                "text-xs font-bold tracking-widest uppercase transition-colors hover:text-blue-600",
                isActive ? "text-blue-600" : "text-slate-500"
              )}
            >
              Notícias
            </NavLink>
            <NavLink 
              to="/empresas" 
              className={({ isActive }) => cn(
                "text-xs font-bold tracking-widest uppercase transition-colors hover:text-blue-600",
                isActive ? "text-blue-600" : "text-slate-500"
              )}
            >
              Empresas
            </NavLink>
          </div>
        </div>

        <div className="flex items-center gap-6">
          <div className="hidden items-center gap-6 md:flex">
            {user ? (
              <div className="flex items-center gap-6">
                {(user.role === 'company' || user.role === 'publisher') && (
                  <Link 
                    to="/dashboard" 
                    className="text-xs font-bold tracking-widest uppercase text-slate-900 hover:text-blue-600"
                  >
                    Dashboard
                  </Link>
                )}
                {user.role === 'admin' && (
                  <Link 
                    to="/admin" 
                    className="text-xs font-bold tracking-widest uppercase text-slate-900 hover:text-blue-600"
                  >
                    Admin
                  </Link>
                )}
                <div className="flex items-center gap-4">
                  <div className="flex flex-col items-end">
                    <Link 
                      to={user.role === 'admin' ? '/admin' : (user.role === 'company' || user.role === 'publisher' ? '/dashboard' : '/')}
                      className="text-xs font-bold text-slate-900 hover:text-blue-600 transition-colors"
                    >
                      {user.username}
                    </Link>
                    <button 
                      onClick={logout}
                      className="text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-red-500 transition-colors"
                    >
                      Sair
                    </button>
                  </div>
                  <Link 
                    to={user.role === 'admin' ? '/admin' : (user.role === 'company' || user.role === 'publisher' ? '/dashboard' : '/')}
                    className="group relative"
                  >
                    <div className="h-8 w-8 rounded-full bg-slate-100 ring-2 ring-slate-50 transition-all group-hover:ring-blue-100 group-hover:scale-110" />
                    <div className="absolute inset-0 flex items-center justify-center text-[10px] font-bold text-slate-400 opacity-0 transition-opacity group-hover:opacity-100">
                      {user.username[0].toUpperCase()}
                    </div>
                  </Link>
                </div>
              </div>
            ) : (
              <Link 
                to="/login"
                className="rounded-full bg-slate-900 px-6 py-2 text-xs font-bold tracking-widest uppercase text-white transition-all hover:bg-blue-600 hover:shadow-lg active:scale-95"
              >
                Entrar
              </Link>
            )}
          </div>
          
          <button 
            onClick={() => setIsMenuOpen(!isMenuOpen)}
            className="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-900 md:hidden"
          >
            {isMenuOpen ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
          </button>
        </div>
      </div>

      {/* Mobile Menu */}
      {isMenuOpen && (
        <div className="border-t border-slate-100 bg-white p-4 md:hidden">
          <div className="flex flex-col gap-4">
            <NavLink 
              to="/" 
              onClick={() => setIsMenuOpen(false)}
              className="text-sm font-bold tracking-widest uppercase text-slate-900"
            >
              Notícias
            </NavLink>
            <NavLink 
              to="/empresas" 
              onClick={() => setIsMenuOpen(false)}
              className="text-sm font-bold tracking-widest uppercase text-slate-900"
            >
              Empresas
            </NavLink>
            <div className="h-px w-full bg-slate-100" />
            {user ? (
              <>
                {(user.role === 'company' || user.role === 'publisher') && (
                  <Link 
                    to="/dashboard" 
                    onClick={() => setIsMenuOpen(false)}
                    className="text-sm font-bold tracking-widest uppercase text-slate-900"
                  >
                    Dashboard
                  </Link>
                )}
                {user.role === 'admin' && (
                  <Link 
                    to="/admin" 
                    onClick={() => setIsMenuOpen(false)}
                    className="text-sm font-bold tracking-widest uppercase text-slate-900"
                  >
                    Admin
                  </Link>
                )}
                <div className="flex items-center justify-between">
                  <Link 
                    to={user.role === 'admin' ? '/admin' : (user.role === 'company' || user.role === 'publisher' ? '/dashboard' : '/')}
                    onClick={() => setIsMenuOpen(false)}
                    className="text-sm font-bold text-slate-900 hover:text-blue-600 transition-colors"
                  >
                    {user.username}
                  </Link>
                  <button 
                    onClick={() => { logout(); setIsMenuOpen(false); }}
                    className="text-xs font-bold uppercase text-red-500"
                  >
                    Sair
                  </button>
                </div>
              </>
            ) : (
              <Link 
                to="/login"
                onClick={() => setIsMenuOpen(false)}
                className="rounded-xl bg-slate-900 py-3 text-center text-xs font-bold tracking-widest uppercase text-white"
              >
                Entrar
              </Link>
            )}
          </div>
        </div>
      )}
    </nav>
  );
};
