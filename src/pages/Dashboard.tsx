import React from 'react';
import { useAuth } from '../presentation/AuthContext';
import { Navigate, useSearchParams } from 'react-router-dom';
import { BarChart3, Newspaper, MessageSquare, Heart, Share2, TrendingUp, Users, Building2 } from 'lucide-react';
import { cn } from '../lib/utils';
import { UserService } from '../infrastructure/firebaseServices';
import { User as UserEntity } from '../domain/entities';

export const Dashboard: React.FC = () => {
  const { user: currentUser, loading: authLoading } = useAuth();
  const [searchParams] = useSearchParams();
  const [targetUser, setTargetUser] = React.useState<UserEntity | null>(null);
  const [loading, setLoading] = React.useState(true);

  const targetUid = searchParams.get('uid');

  React.useEffect(() => {
    const loadTargetUser = async () => {
      if (targetUid && currentUser?.role === 'admin') {
        const profile = await UserService.getProfile(targetUid);
        setTargetUser(profile);
      } else {
        setTargetUser(currentUser);
      }
      setLoading(false);
    };

    if (!authLoading) {
      loadTargetUser();
    }
  }, [targetUid, currentUser, authLoading]);

  if (authLoading || loading) return <div className="flex h-64 items-center justify-center text-xs font-bold uppercase tracking-widest text-slate-400">Carregando...</div>;
  if (!currentUser || (currentUser.role !== 'company' && currentUser.role !== 'publisher' && currentUser.role !== 'admin')) return <Navigate to="/" />;

  const displayUser = targetUser || currentUser;
  const isCompany = displayUser.role === 'company';
  const isImpersonating = targetUid && currentUser.role === 'admin' && targetUid !== currentUser.id;

  const stats = isCompany ? [
    { label: 'Visualizações de Perfil', value: '1.2k', icon: TrendingUp, color: 'text-blue-600', bg: 'bg-blue-50' },
    { label: 'Pesquisas Relacionadas', value: '456', icon: BarChart3, color: 'text-emerald-600', bg: 'bg-emerald-50' },
    { label: 'Cliques no Website', value: '89', icon: Share2, color: 'text-indigo-600', bg: 'bg-indigo-50' },
  ] : [
    { label: 'Total de Leituras', value: '15.4k', icon: Newspaper, color: 'text-blue-600', bg: 'bg-blue-50' },
    { label: 'Engajamento', value: '2.3k', icon: Heart, color: 'text-rose-600', bg: 'bg-rose-50' },
    { label: 'Comentários', value: '142', icon: MessageSquare, color: 'text-amber-600', bg: 'bg-amber-50' },
  ];

  return (
    <div className="space-y-12 pb-20">
      <header className="flex flex-col gap-6 border-b border-slate-100 pb-10 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <span className="mb-2 inline-block text-[10px] font-bold tracking-widest uppercase text-blue-600">
            {isCompany ? 'Painel da Empresa' : 'Painel do Publicador'}
            {isImpersonating && <span className="ml-2 text-amber-600">(Visualizando como Administrador)</span>}
          </span>
          <h1 className="text-4xl font-black tracking-tight text-slate-900">Olá, {displayUser.username}</h1>
          <p className="mt-2 text-slate-500">
            {isCompany 
              ? 'Acompanhe o desempenho da sua empresa na plataforma.' 
              : 'Gerencie suas publicações e interações com a comunidade.'}
          </p>
        </div>
        <button className="flex items-center gap-2 rounded-2xl bg-slate-900 px-6 py-3 text-xs font-bold tracking-widest uppercase text-white transition-all hover:bg-blue-600 hover:shadow-lg active:scale-95">
          <Newspaper className="h-4 w-4" />
          {isCompany ? 'Postar Promoção' : 'Nova Publicação'}
        </button>
      </header>

      <div className="grid gap-8 sm:grid-cols-3">
        {stats.map((stat) => (
          <div key={stat.label} className="group rounded-3xl border border-slate-100 bg-white p-8 transition-all hover:border-blue-100 hover:shadow-xl hover:shadow-blue-500/5">
            <div className="mb-6 flex items-center justify-between">
              <div className={`flex h-14 w-14 items-center justify-center rounded-2xl ${stat.bg} ${stat.color} ring-1 ring-slate-50 transition-transform group-hover:scale-110`}>
                <stat.icon className="h-7 w-7" />
              </div>
              <span className="font-serif text-3xl font-black text-slate-900">{stat.value}</span>
            </div>
            <p className="text-xs font-bold tracking-widest uppercase text-slate-400">{stat.label}</p>
          </div>
        ))}
      </div>

      <div className="grid gap-12 lg:grid-cols-2">
        <section className="rounded-[2rem] border border-slate-100 bg-white p-10 shadow-sm">
          <div className="mb-8 flex items-center justify-between">
            <h2 className="text-xl font-black tracking-tight text-slate-900">
              {isCompany ? 'Pesquisas Recentes' : 'Publicações Populares'}
            </h2>
            <button className="text-[10px] font-bold tracking-widest uppercase text-blue-600 hover:underline">Ver Detalhes</button>
          </div>
          <div className="space-y-6">
            {isCompany ? (
              ['Padarias em Centro', 'Pão francês preço', 'Café da manhã'].map((term, i) => (
                <div key={i} className="flex items-center justify-between border-b border-slate-50 pb-6 last:border-0 last:pb-0">
                  <div className="flex items-center gap-4">
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400">
                      <TrendingUp className="h-5 w-5" />
                    </div>
                    <span className="text-sm font-bold text-slate-900">{term}</span>
                  </div>
                  <span className="text-xs font-bold text-blue-600">+{Math.floor(Math.random() * 50)}%</span>
                </div>
              ))
            ) : (
              ['Obras no Centro', 'Festival de Inverno', 'Nova Ciclovia'].map((post, i) => (
                <div key={i} className="flex items-center justify-between border-b border-slate-50 pb-6 last:border-0 last:pb-0">
                  <div className="flex items-center gap-4">
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400">
                      <Newspaper className="h-5 w-5" />
                    </div>
                    <span className="text-sm font-bold text-slate-900">{post}</span>
                  </div>
                  <div className="flex gap-4">
                    <div className="flex items-center gap-1 text-[10px] font-bold text-slate-400">
                      <Heart className="h-3 w-3" /> {Math.floor(Math.random() * 100)}
                    </div>
                    <div className="flex items-center gap-1 text-[10px] font-bold text-slate-400">
                      <MessageSquare className="h-3 w-3" /> {Math.floor(Math.random() * 20)}
                    </div>
                  </div>
                </div>
              ))
            )}
          </div>
        </section>

        <section className="rounded-[2rem] border border-slate-100 bg-white p-10 shadow-sm">
          <div className="mb-8 flex items-center justify-between">
            <h2 className="text-xl font-black tracking-tight text-slate-900">Insights da Comunidade</h2>
            <span className="badge-label bg-emerald-50 text-emerald-600">Positivo</span>
          </div>
          <div className="flex h-48 flex-col items-center justify-center text-center">
            <div className="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-50 text-slate-300">
              <Users className="h-6 w-6" />
            </div>
            <p className="text-sm font-bold text-slate-900">Crescimento de 12%</p>
            <p className="mt-1 text-xs text-slate-400">Seu alcance aumentou em relação à semana passada.</p>
          </div>
        </section>
      </div>
    </div>
  );
};
