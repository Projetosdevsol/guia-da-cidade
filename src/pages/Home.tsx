import React, { useEffect, useState } from 'react';
import { Post } from '../domain/entities';
import { PostService, AuditLogService } from '../infrastructure/firebaseServices';
import { useAuth } from '../presentation/AuthContext';
import { PostCard } from '../components/PostCard';
import { Search } from 'lucide-react';
import { cn } from '../lib/utils';

export const Home: React.FC = () => {
  const { user } = useAuth();
  const [posts, setPosts] = useState<Post[]>([]);
  const [search, setSearch] = useState('');
  const [loading, setLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState(1);
  const postsPerPage = 6;

  useEffect(() => {
    const unsubscribe = PostService.subscribeToPublished((data) => {
      setPosts(data);
      setLoading(false);
    });
    return unsubscribe;
  }, []);

  useEffect(() => {
    setCurrentPage(1);
  }, [search]);

  useEffect(() => {
    if (!search) return;
    const timeout = setTimeout(() => {
      AuditLogService.logAction('SEARCH_POSTS', user?.id || 'anonymous', undefined, search);
    }, 2000);
    return () => clearTimeout(timeout);
  }, [search, user?.id]);

  const filteredPosts = posts.filter(post => 
    post.title.toLowerCase().includes(search.toLowerCase()) ||
    post.content.toLowerCase().includes(search.toLowerCase())
  );

  const totalPages = Math.ceil(filteredPosts.length / postsPerPage);
  const indexOfLastPost = currentPage * postsPerPage;
  const indexOfFirstPost = indexOfLastPost - postsPerPage;
  const currentPosts = filteredPosts.slice(indexOfFirstPost, indexOfLastPost);

  const paginate = (pageNumber: number) => {
    setCurrentPage(pageNumber);
    window.scrollTo({ top: 500, behavior: 'smooth' });
  };

  return (
    <div className="space-y-16 pb-20">
      {/* Hero Section - Editorial Style */}
      <section className="relative -mx-4 overflow-hidden bg-slate-900 px-4 py-24 text-white sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div className="section-container relative z-10">
          <div className="max-w-3xl">
            <span className="mb-6 inline-block text-xs font-bold tracking-[0.3em] uppercase text-blue-500">
              O seu guia definitivo
            </span>
            <h1 className="mb-8 text-5xl font-black leading-[0.9] tracking-tighter sm:text-7xl lg:text-8xl">
              CONECTANDO <br />
              <span className="italic text-blue-500">PESSOAS</span> & <br />
              CIDADES.
            </h1>
            <p className="mb-10 max-w-xl text-lg leading-relaxed text-slate-400">
              Explore as últimas notícias, eventos culturais e o guia comercial mais completo da região. Informação de qualidade para quem vive a cidade.
            </p>
            <div className="relative max-w-md">
              <Search className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500" />
              <input 
                type="text" 
                placeholder="O que você está procurando hoje?" 
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="w-full rounded-full bg-white/10 py-4 pl-12 pr-6 text-sm font-medium text-white placeholder:text-slate-500 outline-none ring-1 ring-white/20 focus:bg-white/20 focus:ring-blue-500/50 transition-all"
              />
            </div>
          </div>
        </div>
        
        {/* Decorative Elements */}
        <div className="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-blue-600/20 to-transparent opacity-50" />
        <div className="absolute -right-20 top-1/4 h-96 w-96 rounded-full bg-blue-600/30 blur-[120px]" />
        <div className="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-blue-600 via-transparent to-transparent" />
      </section>

      {/* Content Section */}
      <div className="section-container">
        <div className="mb-12 flex flex-col items-start justify-between gap-4 border-b border-slate-100 pb-8 sm:flex-row sm:items-end">
          <div>
            <h2 className="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Destaques da Semana</h2>
            <p className="mt-2 text-slate-500">As notícias mais relevantes para o seu dia a dia.</p>
          </div>
          <div className="flex items-center gap-4">
            <span className="text-xs font-bold tracking-widest uppercase text-slate-400">
              {filteredPosts.length} Artigos encontrados
            </span>
          </div>
        </div>

        {loading ? (
          <div className="grid gap-12 sm:grid-cols-2 lg:grid-cols-3">
            {[1, 2, 3, 4, 5, 6].map(i => (
              <div key={i} className="space-y-4">
                <div className="aspect-[16/10] animate-pulse rounded-2xl bg-slate-100" />
                <div className="h-4 w-1/4 animate-pulse rounded bg-slate-100" />
                <div className="h-8 w-full animate-pulse rounded bg-slate-100" />
                <div className="h-4 w-3/4 animate-pulse rounded bg-slate-100" />
              </div>
            ))}
          </div>
        ) : filteredPosts.length > 0 ? (
          <>
            <div className="grid gap-x-8 gap-y-16 sm:grid-cols-2 lg:grid-cols-3">
              {currentPosts.map(post => (
                <PostCard key={post.id} post={post} />
              ))}
            </div>

            {totalPages > 1 && (
              <div className="mt-20 flex items-center justify-center gap-2">
                <button
                  onClick={() => paginate(currentPage - 1)}
                  disabled={currentPage === 1}
                  className="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-400 transition-all hover:border-blue-600 hover:text-blue-600 disabled:opacity-30 disabled:hover:border-slate-200 disabled:hover:text-slate-400"
                >
                  <span className="sr-only">Anterior</span>
                  <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                  </svg>
                </button>

                {Array.from({ length: totalPages }, (_, i) => i + 1).map(number => (
                  <button
                    key={number}
                    onClick={() => paginate(number)}
                    className={cn(
                      "flex h-10 w-10 items-center justify-center rounded-xl text-sm font-bold transition-all",
                      currentPage === number 
                        ? "bg-blue-600 text-white shadow-lg shadow-blue-500/30" 
                        : "border border-slate-200 text-slate-600 hover:border-blue-600 hover:text-blue-600"
                    )}
                  >
                    {number}
                  </button>
                ))}

                <button
                  onClick={() => paginate(currentPage + 1)}
                  disabled={currentPage === totalPages}
                  className="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-400 transition-all hover:border-blue-600 hover:text-blue-600 disabled:opacity-30 disabled:hover:border-slate-200 disabled:hover:text-slate-400"
                >
                  <span className="sr-only">Próximo</span>
                  <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            )}
          </>
        ) : (
          <div className="flex h-96 flex-col items-center justify-center rounded-3xl bg-slate-50 text-slate-500">
            <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-sm">
              <Search className="h-8 w-8 text-slate-300" />
            </div>
            <p className="text-xl font-bold text-slate-900">Nenhum resultado encontrado</p>
            <p className="mt-2 max-w-xs text-center text-sm">Não encontramos notícias com os termos buscados. Tente usar palavras-chave mais genéricas.</p>
            <button 
              onClick={() => setSearch('')}
              className="mt-6 text-xs font-bold tracking-widest uppercase text-blue-600 hover:underline"
            >
              Limpar busca
            </button>
          </div>
        )}
      </div>
    </div>
  );
};
