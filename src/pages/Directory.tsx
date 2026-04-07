import React, { useEffect, useState } from 'react';
import { Company } from '../domain/entities';
import { CompanyService, AuditLogService } from '../infrastructure/firebaseServices';
import { useAuth } from '../presentation/AuthContext';
import { CompanyCard } from '../components/CompanyCard';
import { Search, Filter, Building2 } from 'lucide-react';

export const Directory: React.FC = () => {
  const { user } = useAuth();
  const [companies, setCompanies] = useState<Company[]>([]);
  const [search, setSearch] = useState('');
  const [categoryFilter, setCategoryFilter] = useState('all');
  const [sizeFilter, setSizeFilter] = useState('all');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const unsubscribe = CompanyService.subscribe((data) => {
      setCompanies(data);
      setLoading(false);
    });
    return unsubscribe;
  }, []);

  useEffect(() => {
    if (!search && categoryFilter === 'all' && sizeFilter === 'all') return;
    const timeout = setTimeout(() => {
      const details = JSON.stringify({ search, categoryFilter, sizeFilter });
      AuditLogService.logAction('SEARCH_COMPANIES', user?.id || 'anonymous', undefined, details);
    }, 2000);
    return () => clearTimeout(timeout);
  }, [search, categoryFilter, sizeFilter, user?.id]);

  const categories = Array.from(new Set(companies.map(c => c.category)));

  const filteredCompanies = companies.filter(company => {
    const matchesSearch = company.name.toLowerCase().includes(search.toLowerCase()) ||
                         company.description?.toLowerCase().includes(search.toLowerCase());
    const matchesCategory = categoryFilter === 'all' || company.category === categoryFilter;
    const matchesSize = sizeFilter === 'all' || company.size === sizeFilter;
    return matchesSearch && matchesCategory && matchesSize;
  });

  return (
    <div className="space-y-12 pb-20">
      <header className="border-b border-slate-100 pb-12 pt-8">
        <div className="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <span className="mb-2 inline-block text-[10px] font-bold tracking-widest uppercase text-blue-600">
              Guia Comercial
            </span>
            <h1 className="text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">Empresas & Serviços</h1>
            <p className="mt-4 max-w-xl text-lg text-slate-500">Conecte-se com os melhores profissionais e estabelecimentos da nossa cidade.</p>
          </div>
          <div className="flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-xs font-bold text-slate-600">
            <Building2 className="h-4 w-4" />
            {filteredCompanies.length} Empresas ativas
          </div>
        </div>
      </header>

      <div className="sticky top-20 z-40 -mx-4 flex flex-col gap-4 bg-white/80 px-4 py-4 backdrop-blur-md sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div className="section-container flex flex-col gap-4 lg:flex-row lg:items-center">
          <div className="relative flex-1">
            <Search className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
            <input 
              type="text" 
              placeholder="Buscar por nome, categoria ou descrição..." 
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-12 pr-4 text-sm font-medium outline-none ring-blue-500/20 transition-all focus:border-blue-500 focus:ring-4"
            />
          </div>
          
          <div className="flex flex-wrap items-center gap-3">
            <div className="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2">
              <Filter className="h-4 w-4 text-slate-400" />
              <select 
                value={categoryFilter}
                onChange={(e) => setCategoryFilter(e.target.value)}
                className="bg-transparent text-sm font-bold text-slate-600 outline-none"
              >
                <option value="all">Categorias</option>
                {categories.map(cat => (
                  <option key={cat} value={cat}>{cat}</option>
                ))}
              </select>
            </div>
            
            <div className="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2">
              <Building2 className="h-4 w-4 text-slate-400" />
              <select 
                value={sizeFilter}
                onChange={(e) => setSizeFilter(e.target.value)}
                className="bg-transparent text-sm font-bold text-slate-600 outline-none"
              >
                <option value="all">Porte</option>
                <option value="small">Pequena</option>
                <option value="medium">Média</option>
                <option value="large">Grande</option>
              </select>
            </div>

            {(search || categoryFilter !== 'all' || sizeFilter !== 'all') && (
              <button 
                onClick={() => { setSearch(''); setCategoryFilter('all'); setSizeFilter('all'); }}
                className="text-xs font-bold uppercase tracking-widest text-red-500 hover:underline"
              >
                Limpar
              </button>
            )}
          </div>
        </div>
      </div>

      {loading ? (
        <div className="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          {[1, 2, 3, 4, 5, 6, 7, 8].map(i => (
            <div key={i} className="h-72 animate-pulse rounded-3xl bg-slate-50" />
          ))}
        </div>
      ) : filteredCompanies.length > 0 ? (
        <div className="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          {filteredCompanies.map(company => (
            <CompanyCard key={company.id} company={company} />
          ))}
        </div>
      ) : (
        <div className="flex h-96 flex-col items-center justify-center rounded-3xl bg-slate-50 text-slate-500">
          <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-sm">
            <Building2 className="h-8 w-8 text-slate-300" />
          </div>
          <p className="text-xl font-bold text-slate-900">Nenhuma empresa encontrada</p>
          <p className="mt-2 text-center text-sm">Não encontramos resultados para os filtros selecionados.</p>
        </div>
      )}
    </div>
  );
};
