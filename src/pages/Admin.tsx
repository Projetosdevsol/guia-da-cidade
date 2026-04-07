import React, { useEffect, useState } from 'react';
import { useAuth } from '../presentation/AuthContext';
import { Navigate, useNavigate } from 'react-router-dom';
import { LayoutDashboard, Newspaper, Building2, Users, Plus, X, Database, Shield, User as UserIcon, History, Settings as SettingsIcon, Ban, Trash2, Save, AlertTriangle, ExternalLink } from 'lucide-react';
import { db } from '../lib/firebase';
import { collection, addDoc, Timestamp } from 'firebase/firestore';
import { cn } from '../lib/utils';
import { UserService, AuditLogService, SettingsService, PostService, CategoryService } from '../infrastructure/firebaseServices';
import { User, AuditLog, SystemSettings, Post, Category } from '../domain/entities';
import { getAuth, sendPasswordResetEmail } from 'firebase/auth';

export const Admin: React.FC = () => {
  const { user, loading } = useAuth();
  const navigate = useNavigate();
  const [status, setStatus] = useState<{ type: 'success' | 'error', message: string } | null>(null);
  const [users, setUsers] = useState<User[]>([]);
  const [logs, setLogs] = useState<AuditLog[]>([]);
  const [posts, setPosts] = useState<Post[]>([]);
  const [categories, setCategories] = useState<Category[]>([]);
  const [settings, setSettings] = useState<SystemSettings | null>(null);
  const [statsData, setStatsData] = useState({ totalUsers: 0, totalPosts: 0, totalCompanies: 0 });
  const [activeTab, setActiveTab] = useState<'overview' | 'users' | 'content' | 'logs' | 'settings'>('overview');
  
  // Modal State
  const [isPostModalOpen, setIsPostModalOpen] = useState(false);
  const [newPost, setNewPost] = useState({
    title: '',
    content: '',
    coverImageURL: '',
    categoryId: '',
    status: 'published' as Post['status']
  });

  useEffect(() => {
    if (user?.role === 'admin') {
      if (activeTab === 'overview') loadOverview();
      if (activeTab === 'users') loadUsers();
      if (activeTab === 'content') loadContent();
      if (activeTab === 'logs') loadLogs();
      if (activeTab === 'settings') loadSettings();
    }
  }, [user, activeTab]);

  const loadOverview = async () => {
    const allUsers = await UserService.getAllUsers();
    const allLogs = await AuditLogService.getAll();
    // For now, we'll just count from the lists we have
    // In a real app, we'd have a dedicated stats service
    if (allUsers && allLogs) {
      setUsers(allUsers);
      setLogs(allLogs);
      setStatsData({
        totalUsers: allUsers.length,
        totalPosts: allLogs.filter(l => l.action === 'CREATE_POST').length,
        totalCompanies: allUsers.filter(u => u.role === 'company').length
      });
    }
  };

  const loadUsers = async () => {
    const allUsers = await UserService.getAllUsers();
    if (allUsers) setUsers(allUsers);
  };

  const loadContent = async () => {
    const allPosts = await PostService.getAllPublished();
    const allCats = await CategoryService.getAll();
    if (allPosts) setPosts(allPosts);
    if (allCats) {
      setCategories(allCats);
      if (allCats.length > 0 && !newPost.categoryId) {
        setNewPost(prev => ({ ...prev, categoryId: allCats[0].id }));
      }
    }
  };

  const loadLogs = async () => {
    const allLogs = await AuditLogService.getAll();
    if (allLogs) setLogs(allLogs);
  };

  const loadSettings = async () => {
    const currentSettings = await SettingsService.getSettings();
    if (currentSettings) setSettings(currentSettings);
  };

  const handleRoleChange = async (uid: string, newRole: User['role']) => {
    try {
      await UserService.updateUserRole(uid, newRole);
      setUsers(prev => prev.map(u => u.id === uid ? { ...u, role: newRole } : u));
      setStatus({ type: 'success', message: 'Role atualizada com sucesso!' });
    } catch (error) {
      setStatus({ type: 'error', message: 'Erro ao atualizar role.' });
    }
  };

  const handleToggleBan = async (uid: string, isBanned: boolean) => {
    try {
      await UserService.toggleBan(uid, isBanned);
      setUsers(prev => prev.map(u => u.id === uid ? { ...u, isBanned } : u));
      setStatus({ type: 'success', message: isBanned ? 'Usuário banido!' : 'Usuário desbanido!' });
    } catch (error) {
      setStatus({ type: 'error', message: 'Erro ao alterar status de banimento.' });
    }
  };

  const handleDeleteUser = async (uid: string) => {
    if (!window.confirm('Tem certeza que deseja excluir este usuário? Esta ação é irreversível.')) return;
    try {
      await UserService.deleteUser(uid);
      setUsers(prev => prev.filter(u => u.id !== uid));
      setStatus({ type: 'success', message: 'Usuário excluído com sucesso!' });
    } catch (error) {
      setStatus({ type: 'error', message: 'Erro ao excluir usuário.' });
    }
  };

  const handleResetPassword = async (email: string) => {
    try {
      const auth = getAuth();
      await sendPasswordResetEmail(auth, email);
      await AuditLogService.logAction('RESET_PASSWORD', user!.id, email);
      setStatus({ type: 'success', message: `E-mail de redefinição enviado para ${email}` });
    } catch (error) {
      setStatus({ type: 'error', message: 'Erro ao enviar e-mail de redefinição.' });
    }
  };

  const handleSaveSettings = async () => {
    if (!settings) return;
    try {
      await SettingsService.updateSettings(settings);
      setStatus({ type: 'success', message: 'Configurações salvas com sucesso!' });
    } catch (error) {
      setStatus({ type: 'error', message: 'Erro ao salvar configurações.' });
    }
  };

  const handleCreatePost = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!user) return;
    
    try {
      const slug = newPost.title.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "").replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');
      
      await PostService.create({
        ...newPost,
        slug,
        authorId: user.id,
        createdAt: new Date()
      });
      
      setIsPostModalOpen(false);
      setNewPost({ title: '', content: '', coverImageURL: '', categoryId: categories[0]?.id || '', status: 'published' });
      setStatus({ type: 'success', message: 'Notícia publicada com sucesso!' });
      loadContent();
    } catch (error) {
      setStatus({ type: 'error', message: 'Erro ao publicar notícia.' });
    }
  };

  if (loading) return <div className="flex h-64 items-center justify-center text-xs font-bold uppercase tracking-widest text-slate-400">Carregando...</div>;
  if (!user || user.role !== 'admin') return <Navigate to="/" />;

  const stats = [
    { label: 'Total de Usuários', value: statsData.totalUsers.toString(), icon: Users, color: 'text-blue-600', bg: 'bg-blue-50' },
    { label: 'Publicações Ativas', value: statsData.totalPosts.toString(), icon: Newspaper, color: 'text-emerald-600', bg: 'bg-emerald-50' },
    { label: 'Empresas Parceiras', value: statsData.totalCompanies.toString(), icon: Building2, color: 'text-indigo-600', bg: 'bg-indigo-50' },
  ];

  const seedData = async () => {
    try {
      setStatus(null);
      // Seed Categories
      const catRef = await addDoc(collection(db, 'categories'), { name: 'Geral', slug: 'geral' });
      
      // Seed Posts
      await addDoc(collection(db, 'posts'), {
        title: 'Nova ciclovia inaugurada no centro',
        slug: 'nova-ciclovia',
        content: 'A prefeitura inaugurou hoje a nova ciclovia que liga o centro ao bairro Jardim das Flores. Com 5km de extensão, a obra promete melhorar a mobilidade urbana.',
        coverImageURL: 'https://picsum.photos/seed/city/800/400',
        status: 'published',
        categoryId: catRef.id,
        authorId: user.id,
        createdAt: Timestamp.now()
      });

      await addDoc(collection(db, 'posts'), {
        title: 'Festival de Gastronomia começa este final de semana',
        slug: 'festival-gastronomia',
        content: 'O tradicional festival de gastronomia da cidade começa neste sábado na Praça da Matriz. Serão mais de 20 expositores com pratos típicos da região.',
        coverImageURL: 'https://picsum.photos/seed/food/800/400',
        status: 'published',
        categoryId: catRef.id,
        authorId: user.id,
        createdAt: Timestamp.now()
      });

      // Seed Companies
      await addDoc(collection(db, 'companies'), {
        name: 'Padaria Central',
        taxId: '12.345.678/0001-90',
        category: 'Alimentação',
        size: 'small',
        description: 'A melhor padaria da cidade com pães quentinhos a toda hora.',
        address: 'Rua Principal, 123',
        phone: '(11) 9999-9999',
        website: 'https://padariacentral.com',
        createdAt: Timestamp.now()
      });

      await addDoc(collection(db, 'companies'), {
        name: 'Tech Solutions',
        taxId: '98.765.432/0001-10',
        category: 'Tecnologia',
        size: 'medium',
        description: 'Soluções em software e hardware para sua empresa.',
        address: 'Av. Paulista, 1000',
        phone: '(11) 8888-8888',
        website: 'https://techsolutions.com',
        createdAt: Timestamp.now()
      });

      setStatus({ type: 'success', message: 'Dados semeados com sucesso!' });
    } catch (error) {
      console.error(error);
      setStatus({ type: 'error', message: 'Erro ao semear dados.' });
    }
  };

  return (
    <div className="space-y-12 pb-20">
      <header className="flex flex-col gap-6 border-b border-slate-100 pb-10 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <span className="mb-2 inline-block text-[10px] font-bold tracking-widest uppercase text-blue-600">
            Painel de Controle
          </span>
          <h1 className="text-4xl font-black tracking-tight text-slate-900">Administração</h1>
          <p className="mt-2 text-slate-500">Gerencie o ecossistema digital da sua cidade.</p>
        </div>
        <div className="flex flex-wrap gap-3">
          <button 
            onClick={seedData}
            className="flex items-center gap-2 rounded-2xl bg-amber-50 px-4 py-2.5 text-xs font-bold tracking-widest uppercase text-amber-700 transition-all hover:bg-amber-100 active:scale-95"
          >
            <Database className="h-4 w-4" />
            Semear Dados
          </button>
          <button 
            onClick={() => setIsPostModalOpen(true)}
            className="flex items-center gap-2 rounded-2xl bg-slate-900 px-4 py-2.5 text-xs font-bold tracking-widest uppercase text-white transition-all hover:bg-blue-600 hover:shadow-lg active:scale-95"
          >
            <Plus className="h-4 w-4" />
            Nova Notícia
          </button>
        </div>
      </header>

      <div className="flex gap-8 border-b border-slate-100 pb-4">
        {[
          { id: 'overview', label: 'Visão Geral', icon: LayoutDashboard },
          { id: 'users', label: 'Usuários', icon: Users },
          { id: 'content', label: 'Conteúdo', icon: Newspaper },
          { id: 'logs', label: 'Logs', icon: History },
          { id: 'settings', label: 'Configurações', icon: SettingsIcon },
        ].map((tab) => (
          <button
            key={tab.id}
            onClick={() => setActiveTab(tab.id as any)}
            className={cn(
              "flex items-center gap-2 border-b-2 px-1 pb-4 text-xs font-bold tracking-widest uppercase transition-all",
              activeTab === tab.id 
                ? "border-blue-600 text-blue-600" 
                : "border-transparent text-slate-400 hover:text-slate-600"
            )}
          >
            <tab.icon className="h-4 w-4" />
            {tab.label}
          </button>
        ))}
      </div>

      {status && (
        <div className={cn(
          "rounded-2xl p-4 text-sm font-bold tracking-wide",
          status.type === 'success' ? "bg-emerald-50 text-emerald-700 border border-emerald-100" : "bg-red-50 text-red-700 border border-red-100"
        )}>
          {status.message}
        </div>
      )}

      {activeTab === 'overview' && (
        <>
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
                <h2 className="text-xl font-black tracking-tight text-slate-900">Atividade Recente</h2>
                <button className="text-[10px] font-bold tracking-widest uppercase text-blue-600 hover:underline">Ver tudo</button>
              </div>
              <div className="space-y-6">
                {logs.slice(0, 5).map((log) => (
                  <div key={log.id} className="flex items-center justify-between border-b border-slate-50 pb-6 last:border-0 last:pb-0">
                    <div className="flex items-start gap-4">
                      <div className="mt-1 h-2 w-2 rounded-full bg-blue-500" />
                      <div>
                        <p className="text-sm font-bold text-slate-900">{log.action}</p>
                        <p className="mt-1 text-xs text-slate-400">
                          {log.createdAt.toLocaleString()} - {log.details || 'Sem detalhes'}
                        </p>
                      </div>
                    </div>
                  </div>
                ))}
                {logs.length === 0 && (
                  <p className="text-center text-sm text-slate-400 py-10">Nenhuma atividade recente.</p>
                )}
              </div>
            </section>

            <section className="rounded-[2rem] border border-slate-100 bg-white p-10 shadow-sm">
              <div className="mb-8 flex items-center justify-between">
                <h2 className="text-xl font-black tracking-tight text-slate-900">Empresas Pendentes</h2>
                <span className="badge-label bg-slate-100 text-slate-500">0 Pendentes</span>
              </div>
              <div className="flex h-48 flex-col items-center justify-center text-center">
                <div className="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-50 text-slate-300">
                  <Building2 className="h-6 w-6" />
                </div>
                <p className="text-sm font-bold text-slate-900">Tudo em dia!</p>
                <p className="mt-1 text-xs text-slate-400">Nenhuma empresa aguardando aprovação.</p>
              </div>
            </section>
          </div>
        </>
      )}

      {activeTab === 'users' && (
        <section className="rounded-[2rem] border border-slate-100 bg-white p-10 shadow-sm">
          <div className="mb-8 flex items-center justify-between">
            <h2 className="text-xl font-black tracking-tight text-slate-900">Gerenciamento de Usuários</h2>
            <span className="badge-label bg-blue-50 text-blue-600">{users.length} Usuários</span>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-left">
              <thead>
                <tr className="border-b border-slate-100">
                  <th className="pb-4 text-[10px] font-bold tracking-widest uppercase text-slate-400">Usuário</th>
                  <th className="pb-4 text-[10px] font-bold tracking-widest uppercase text-slate-400">E-mail</th>
                  <th className="pb-4 text-[10px] font-bold tracking-widest uppercase text-slate-400">Role</th>
                  <th className="pb-4 text-[10px] font-bold tracking-widest uppercase text-slate-400 text-right">Ações</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-50">
                {users.map((u) => (
                  <tr key={u.id} className={cn("group transition-colors", u.isBanned && "bg-red-50/50")}>
                    <td className="py-6">
                      <div className="flex items-center gap-3">
                        <div className={cn(
                          "flex h-10 w-10 items-center justify-center rounded-xl text-slate-400",
                          u.isBanned ? "bg-red-100 text-red-500" : "bg-slate-50"
                        )}>
                          <UserIcon className="h-5 w-5" />
                        </div>
                        <div>
                          <span className="text-sm font-bold text-slate-900">{u.username}</span>
                          {u.isBanned && <span className="ml-2 text-[10px] font-bold uppercase text-red-500">Banido</span>}
                        </div>
                      </div>
                    </td>
                    <td className="py-6 text-sm text-slate-500">{u.email}</td>
                    <td className="py-6">
                      <select
                        value={u.role}
                        onChange={(e) => handleRoleChange(u.id, e.target.value as any)}
                        disabled={u.isBanned}
                        className="rounded-lg border border-slate-100 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-700 outline-none focus:border-blue-500 disabled:opacity-50"
                      >
                        <option value="standard">Usuário Comum</option>
                        <option value="company">Empresa</option>
                        <option value="publisher">Jornal/Influencer</option>
                        <option value="admin">Administrador</option>
                      </select>
                    </td>
                    <td className="py-6 text-right">
                      <div className="flex items-center justify-end gap-2">
                        {(u.role === 'company' || u.role === 'publisher') && (
                          <button 
                            onClick={() => navigate(`/dashboard?uid=${u.id}`)}
                            title="Ver Dashboard"
                            className="h-8 w-8 rounded-full bg-blue-50 text-blue-600 transition-colors hover:bg-blue-100"
                          >
                            <ExternalLink className="h-4 w-4" />
                          </button>
                        )}
                        <button 
                          onClick={() => handleResetPassword(u.email)}
                          title="Redefinir Senha"
                          className="h-8 w-8 rounded-full bg-slate-50 text-slate-400 transition-colors hover:bg-amber-50 hover:text-amber-600"
                        >
                          <Shield className="h-4 w-4" />
                        </button>
                        <button 
                          onClick={() => handleToggleBan(u.id, !u.isBanned)}
                          title={u.isBanned ? "Desbanir" : "Banir"}
                          className={cn(
                            "h-8 w-8 rounded-full transition-colors",
                            u.isBanned ? "bg-emerald-50 text-emerald-600 hover:bg-emerald-100" : "bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-500"
                          )}
                        >
                          <Ban className="h-4 w-4" />
                        </button>
                        <button 
                          onClick={() => handleDeleteUser(u.id)}
                          title="Excluir Usuário"
                          className="h-8 w-8 rounded-full bg-slate-50 text-slate-400 transition-colors hover:bg-red-500 hover:text-white"
                        >
                          <Trash2 className="h-4 w-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </section>
      )}

      {activeTab === 'logs' && (
        <section className="rounded-[2rem] border border-slate-100 bg-white p-10 shadow-sm">
          <div className="mb-8 flex items-center justify-between">
            <h2 className="text-xl font-black tracking-tight text-slate-900">Logs de Auditoria</h2>
            <button onClick={loadLogs} className="text-[10px] font-bold tracking-widest uppercase text-blue-600 hover:underline">Atualizar</button>
          </div>
          <div className="space-y-4">
            {logs.map((log) => (
              <div key={log.id} className="flex items-start gap-4 rounded-2xl border border-slate-50 bg-slate-50/30 p-4">
                <div className="mt-1 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-slate-400 shadow-sm">
                  <History className="h-4 w-4" />
                </div>
                <div className="flex-1">
                  <div className="flex items-center justify-between">
                    <span className="text-xs font-bold uppercase tracking-widest text-blue-600">{log.action}</span>
                    <span className="text-[10px] font-medium text-slate-400">{log.createdAt.toLocaleString()}</span>
                  </div>
                  <p className="mt-1 text-sm text-slate-900">
                    <span className="font-bold">Admin ID:</span> {log.userId}
                  </p>
                  {log.targetId && (
                    <p className="text-sm text-slate-600">
                      <span className="font-bold">Alvo:</span> {log.targetId}
                    </p>
                  )}
                  {log.details && (
                    <p className="mt-2 rounded-lg bg-white p-2 text-xs font-mono text-slate-500">
                      {log.details}
                    </p>
                  )}
                </div>
              </div>
            ))}
            {logs.length === 0 && (
              <div className="py-12 text-center text-slate-400">Nenhum log encontrado.</div>
            )}
          </div>
        </section>
      )}

      {activeTab === 'settings' && (
        <section className="rounded-[2rem] border border-slate-100 bg-white p-10 shadow-sm">
          <div className="mb-8 flex items-center justify-between">
            <h2 className="text-xl font-black tracking-tight text-slate-900">Configurações do Sistema</h2>
            <button 
              onClick={handleSaveSettings}
              className="flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold tracking-widest uppercase text-white transition-all hover:bg-blue-700 active:scale-95"
            >
              <Save className="h-4 w-4" />
              Salvar
            </button>
          </div>
          
          {settings && (
            <div className="grid gap-10 lg:grid-cols-2">
              <div className="space-y-6">
                <div>
                  <label className="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">Nome do Site</label>
                  <input 
                    type="text" 
                    value={settings.siteName}
                    onChange={(e) => setSettings({ ...settings, siteName: e.target.value })}
                    className="w-full rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-blue-500"
                  />
                </div>
                
                <div className="flex items-center justify-between rounded-2xl border border-slate-50 bg-slate-50/50 p-4">
                  <div>
                    <p className="text-sm font-bold text-slate-900">Novos Registros</p>
                    <p className="text-xs text-slate-400">Permitir que novos usuários se cadastrem.</p>
                  </div>
                  <button 
                    onClick={() => setSettings({ ...settings, allowNewRegistrations: !settings.allowNewRegistrations })}
                    className={cn(
                      "relative h-6 w-11 rounded-full transition-colors",
                      settings.allowNewRegistrations ? "bg-blue-600" : "bg-slate-200"
                    )}
                  >
                    <div className={cn(
                      "absolute top-1 h-4 w-4 rounded-full bg-white transition-all",
                      settings.allowNewRegistrations ? "left-6" : "left-1"
                    )} />
                  </button>
                </div>
              </div>

              <div className="space-y-6">
                <div className={cn(
                  "rounded-[2rem] p-8 transition-colors",
                  settings.maintenanceMode ? "bg-amber-50 border border-amber-100" : "bg-slate-50 border border-slate-100"
                )}>
                  <div className="mb-4 flex items-center gap-3">
                    <AlertTriangle className={cn("h-6 w-6", settings.maintenanceMode ? "text-amber-600" : "text-slate-400")} />
                    <h3 className="text-lg font-black tracking-tight text-slate-900">Modo Manutenção</h3>
                  </div>
                  <p className="mb-6 text-sm text-slate-500">
                    Quando ativado, apenas administradores poderão acessar a plataforma. Útil para atualizações críticas.
                  </p>
                  <button 
                    onClick={() => setSettings({ ...settings, maintenanceMode: !settings.maintenanceMode })}
                    className={cn(
                      "w-full rounded-xl py-3 text-xs font-bold tracking-widest uppercase transition-all",
                      settings.maintenanceMode 
                        ? "bg-amber-600 text-white hover:bg-amber-700" 
                        : "bg-slate-900 text-white hover:bg-blue-600"
                    )}
                  >
                    {settings.maintenanceMode ? 'Desativar Manutenção' : 'Ativar Manutenção'}
                  </button>
                </div>
              </div>
            </div>
          )}
        </section>
      )}

      {activeTab === 'content' && (
        <section className="rounded-[2rem] border border-slate-100 bg-white p-10 shadow-sm">
          <div className="mb-8 flex items-center justify-between">
            <h2 className="text-xl font-black tracking-tight text-slate-900">Gerenciamento de Conteúdo</h2>
            <button 
              onClick={() => setIsPostModalOpen(true)}
              className="flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold tracking-widest uppercase text-white transition-all hover:bg-blue-700 active:scale-95"
            >
              <Plus className="h-4 w-4" />
              Nova Notícia
            </button>
          </div>
          
          <div className="grid gap-6">
            {posts.map((post) => (
              <div key={post.id} className="flex items-center justify-between rounded-2xl border border-slate-50 p-4 transition-colors hover:bg-slate-50/50">
                <div className="flex items-center gap-4">
                  <div className="h-12 w-12 overflow-hidden rounded-lg bg-slate-100">
                    <img src={post.coverImageURL} alt="" className="h-full w-full object-cover" />
                  </div>
                  <div>
                    <h3 className="text-sm font-bold text-slate-900">{post.title}</h3>
                    <p className="text-[10px] font-bold uppercase tracking-widest text-slate-400">
                      {post.status === 'published' ? 'Publicado' : 'Rascunho'} • {post.createdAt.toLocaleDateString()}
                    </p>
                  </div>
                </div>
                <div className="flex items-center gap-2">
                  <button className="h-8 w-8 rounded-full bg-slate-50 text-slate-400 transition-colors hover:bg-blue-50 hover:text-blue-600">
                    <Save className="h-4 w-4" />
                  </button>
                  <button 
                    onClick={() => PostService.delete(post.id).then(loadContent)}
                    className="h-8 w-8 rounded-full bg-slate-50 text-slate-400 transition-colors hover:bg-red-50 hover:text-red-500"
                  >
                    <Trash2 className="h-4 w-4" />
                  </button>
                </div>
              </div>
            ))}
            {posts.length === 0 && (
              <div className="flex h-48 flex-col items-center justify-center text-center text-slate-400">
                <Newspaper className="mb-4 h-12 w-12 opacity-20" />
                <p className="text-sm font-bold">Nenhuma notícia encontrada.</p>
              </div>
            )}
          </div>
        </section>
      )}

      {/* New Post Modal */}
      {isPostModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm">
          <div className="w-full max-w-2xl rounded-[2.5rem] bg-white p-10 shadow-2xl">
            <div className="mb-8 flex items-center justify-between">
              <h2 className="text-2xl font-black tracking-tight text-slate-900">Criar Nova Notícia</h2>
              <button 
                onClick={() => setIsPostModalOpen(false)}
                className="flex h-10 w-10 items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100"
              >
                <X className="h-5 w-5" />
              </button>
            </div>

            <form onSubmit={handleCreatePost} className="space-y-6">
              <div className="grid gap-6 sm:grid-cols-2">
                <div className="sm:col-span-2">
                  <label className="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">Título</label>
                  <input 
                    required
                    type="text" 
                    value={newPost.title}
                    onChange={(e) => setNewPost({ ...newPost, title: e.target.value })}
                    placeholder="Ex: Nova ciclovia inaugurada..."
                    className="w-full rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-blue-500"
                  />
                </div>

                <div>
                  <label className="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">Categoria</label>
                  <select 
                    required
                    value={newPost.categoryId}
                    onChange={(e) => setNewPost({ ...newPost, categoryId: e.target.value })}
                    className="w-full rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-blue-500"
                  >
                    {categories.map(cat => (
                      <option key={cat.id} value={cat.id}>{cat.name}</option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">Status</label>
                  <select 
                    required
                    value={newPost.status}
                    onChange={(e) => setNewPost({ ...newPost, status: e.target.value as any })}
                    className="w-full rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-blue-500"
                  >
                    <option value="published">Publicado</option>
                    <option value="draft">Rascunho</option>
                  </select>
                </div>

                <div className="sm:col-span-2">
                  <label className="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">URL da Imagem de Capa</label>
                  <input 
                    required
                    type="url" 
                    value={newPost.coverImageURL}
                    onChange={(e) => setNewPost({ ...newPost, coverImageURL: e.target.value })}
                    placeholder="https://exemplo.com/imagem.jpg"
                    className="w-full rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-blue-500"
                  />
                </div>

                <div className="sm:col-span-2">
                  <label className="mb-2 block text-xs font-bold uppercase tracking-widest text-slate-400">Conteúdo</label>
                  <textarea 
                    required
                    rows={6}
                    value={newPost.content}
                    onChange={(e) => setNewPost({ ...newPost, content: e.target.value })}
                    placeholder="Escreva o corpo da notícia aqui..."
                    className="w-full rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:border-blue-500"
                  />
                </div>
              </div>

              <div className="flex justify-end gap-4 pt-4">
                <button 
                  type="button"
                  onClick={() => setIsPostModalOpen(false)}
                  className="rounded-xl px-6 py-3 text-xs font-bold tracking-widest uppercase text-slate-400 hover:text-slate-600"
                >
                  Cancelar
                </button>
                <button 
                  type="submit"
                  className="rounded-xl bg-blue-600 px-8 py-3 text-xs font-bold tracking-widest uppercase text-white shadow-lg shadow-blue-500/20 transition-all hover:bg-blue-700 active:scale-95"
                >
                  Publicar Notícia
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
