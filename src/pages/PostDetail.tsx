import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Post, Comment } from '../domain/entities';
import { PostService, CommentService, LikeService, AuditLogService } from '../infrastructure/firebaseServices';
import { useAuth } from '../presentation/AuthContext';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { motion, AnimatePresence } from 'motion/react';
import { Heart, MessageCircle, Share2, Check, ArrowLeft, Send, Trash2 } from 'lucide-react';
import { cn } from '../lib/utils';

export const PostDetail: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const { user } = useAuth();
  const [post, setPost] = useState<Post | null>(null);
  const [loading, setLoading] = useState(true);
  const [commentText, setCommentText] = useState('');
  const [comments, setComments] = useState<Comment[]>([]);
  const [isLiked, setIsLiked] = useState(false);
  const [copied, setCopied] = useState(false);

  useEffect(() => {
    if (!id) return;

    const loadPost = async () => {
      try {
        const foundPost = await PostService.getById(id);
        
        if (foundPost) {
          setPost(foundPost);
          AuditLogService.logAction('VIEW_POST', user?.id || 'anonymous', id, foundPost.title);
        }
      } catch (error) {
        console.error('Error loading post:', error);
      } finally {
        setLoading(false);
      }
    };

    loadPost();
  }, [id, user?.id]);

  const handleLike = async () => {
    if (!user || !post) return;
    const liked = await LikeService.toggle(post.id, user.id);
    setIsLiked(liked || false);
  };

  const handleShare = async () => {
    if (!post) return;
    const url = window.location.href;
    try {
      await navigator.clipboard.writeText(url);
      setCopied(true);
      setTimeout(() => setCopied(false), 2000);
      await AuditLogService.logAction('SHARE_POST', user?.id || 'anonymous', post.id, post.title);
    } catch (err) {
      console.error('Erro ao copiar link:', err);
    }
  };

  const handleAddComment = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!user || !post || !commentText.trim()) return;

    try {
      await CommentService.create({
        postId: post.id,
        userId: user.id,
        text: commentText,
        createdAt: new Date()
      });
      setCommentText('');
      // In a real app, we'd have a subscription for comments.
    } catch (error) {
      console.error('Error adding comment:', error);
    }
  };

  if (loading) {
    return (
      <div className="flex h-[60vh] items-center justify-center">
        <div className="h-12 w-12 animate-spin rounded-full border-4 border-blue-600 border-t-transparent" />
      </div>
    );
  }

  if (!post) {
    return (
      <div className="flex h-[60vh] flex-col items-center justify-center text-center">
        <h2 className="text-2xl font-bold text-slate-900">Post não encontrado</h2>
        <button onClick={() => navigate('/')} className="mt-4 text-blue-600 hover:underline">Voltar para Home</button>
      </div>
    );
  }

  return (
    <motion.div 
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      className="mx-auto max-w-4xl px-4 py-12"
    >
      <button 
        onClick={() => navigate(-1)}
        className="mb-8 flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-slate-400 transition-colors hover:text-blue-600"
      >
        <ArrowLeft className="h-4 w-4" />
        Voltar
      </button>

      <header className="mb-12">
        <div className="mb-6 flex items-center gap-3 text-xs font-bold tracking-widest uppercase text-blue-600">
          <span>Notícia</span>
          <span className="h-1.5 w-1.5 rounded-full bg-slate-200" />
          <span className="text-slate-400">{format(post.createdAt, "dd 'de' MMMM, yyyy", { locale: ptBR })}</span>
        </div>
        <h1 className="text-4xl font-black leading-tight text-slate-900 sm:text-5xl lg:text-6xl">
          {post.title}
        </h1>
      </header>

      <div className="relative mb-12 aspect-[21/9] overflow-hidden rounded-[2.5rem] bg-slate-100 shadow-2xl">
        <img 
          src={post.coverImageURL} 
          alt={post.title}
          className="h-full w-full object-cover"
          referrerPolicy="no-referrer"
        />
      </div>

      <div className="grid gap-12 lg:grid-cols-[1fr_300px]">
        <div className="space-y-8">
          <div className="prose prose-slate max-w-none">
            <p className="text-lg leading-relaxed text-slate-600 whitespace-pre-wrap">
              {post.content}
            </p>
          </div>

          <div className="flex items-center gap-6 border-y border-slate-100 py-6">
            <button 
              onClick={handleLike}
              className={cn(
                "flex items-center gap-2 rounded-full px-6 py-3 text-sm font-bold transition-all active:scale-95",
                isLiked ? "bg-red-50 text-red-500" : "bg-slate-50 text-slate-600 hover:bg-slate-100"
              )}
            >
              <Heart className={cn("h-5 w-5", isLiked && "fill-current")} />
              {isLiked ? 'Curtido' : 'Curtir'}
            </button>

            <button 
              onClick={handleShare}
              className="flex items-center gap-2 rounded-full bg-slate-50 px-6 py-3 text-sm font-bold text-slate-600 transition-all hover:bg-slate-100 active:scale-95"
            >
              <AnimatePresence mode="wait">
                {copied ? (
                  <motion.div key="check" initial={{ scale: 0.5 }} animate={{ scale: 1 }} exit={{ scale: 0.5 }}>
                    <Check className="h-5 w-5 text-emerald-500" />
                  </motion.div>
                ) : (
                  <motion.div key="share" initial={{ scale: 0.5 }} animate={{ scale: 1 }} exit={{ scale: 0.5 }}>
                    <Share2 className="h-5 w-5" />
                  </motion.div>
                )}
              </AnimatePresence>
              {copied ? 'Copiado!' : 'Compartilhar'}
            </button>
          </div>

          <section className="space-y-8">
            <h3 className="text-2xl font-black tracking-tight text-slate-900">Comentários</h3>
            
            {user ? (
              <form onSubmit={handleAddComment} className="relative">
                <textarea 
                  value={commentText}
                  onChange={(e) => setCommentText(e.target.value)}
                  placeholder="O que você achou dessa notícia?"
                  className="w-full rounded-3xl border border-slate-100 bg-slate-50 p-6 text-sm font-medium outline-none focus:border-blue-500 transition-all"
                  rows={3}
                />
                <button 
                  type="submit"
                  disabled={!commentText.trim()}
                  className="absolute bottom-4 right-4 flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg transition-all hover:bg-blue-700 disabled:opacity-50"
                >
                  <Send className="h-4 w-4" />
                </button>
              </form>
            ) : (
              <div className="rounded-3xl bg-slate-50 p-8 text-center">
                <p className="text-sm font-bold text-slate-900">Faça login para comentar</p>
                <button onClick={() => navigate('/login')} className="mt-4 text-xs font-bold tracking-widest uppercase text-blue-600 hover:underline">Entrar agora</button>
              </div>
            )}

            <div className="space-y-6">
              {/* Comments would be mapped here from a state/subscription */}
              <div className="flex h-32 flex-col items-center justify-center rounded-3xl border border-dashed border-slate-200 text-slate-400">
                <MessageCircle className="mb-2 h-8 w-8 opacity-20" />
                <p className="text-xs font-bold uppercase tracking-widest">Nenhum comentário ainda</p>
              </div>
            </div>
          </section>
        </div>

        <aside className="space-y-8">
          <div className="rounded-3xl bg-slate-900 p-8 text-white">
            <h4 className="mb-4 text-lg font-black tracking-tight">Sobre o Autor</h4>
            <div className="flex items-center gap-4">
              <div className="h-12 w-12 rounded-full bg-white/10" />
              <div>
                <p className="text-sm font-bold">Redação Guia</p>
                <p className="text-xs text-slate-400">Jornalismo Local</p>
              </div>
            </div>
          </div>

          <div className="rounded-3xl border border-slate-100 p-8">
            <h4 className="mb-6 text-xs font-bold tracking-widest uppercase text-slate-400">Relacionados</h4>
            <div className="space-y-6">
              {[1, 2].map(i => (
                <div key={i} className="group cursor-pointer">
                  <div className="mb-3 aspect-video overflow-hidden rounded-xl bg-slate-100">
                    <img src={`https://picsum.photos/seed/rel-${i}/400/200`} alt="" className="h-full w-full object-cover transition-transform group-hover:scale-105" />
                  </div>
                  <h5 className="text-sm font-bold leading-tight text-slate-900 group-hover:text-blue-600 transition-colors">
                    Outra notícia relevante da semana
                  </h5>
                </div>
              ))}
            </div>
          </div>
        </aside>
      </div>
    </motion.div>
  );
};
