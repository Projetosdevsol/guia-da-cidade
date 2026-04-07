import React, { useState } from 'react';
import { Post } from '../domain/entities';
import { AuditLogService } from '../infrastructure/firebaseServices';
import { useAuth } from '../presentation/AuthContext';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { motion, AnimatePresence } from 'motion/react';
import { Heart, MessageCircle, Share2, Check } from 'lucide-react';
import { cn } from '../lib/utils';

interface PostCardProps {
  post: Post;
}

export const PostCard: React.FC<PostCardProps> = ({ post }) => {
  const { user } = useAuth();
  const [copied, setCopied] = useState(false);

  const handleShare = async () => {
    const url = `${window.location.origin}/post/${post.id}`;
    try {
      await navigator.clipboard.writeText(url);
      setCopied(true);
      setTimeout(() => setCopied(false), 2000);
      await AuditLogService.logAction('SHARE_POST', user?.id || 'anonymous', post.id, post.title);
    } catch (err) {
      console.error('Erro ao copiar link:', err);
    }
  };

  return (
    <motion.article 
      initial={{ opacity: 0, y: 20 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      className="group flex flex-col gap-4"
    >
      <div className="relative aspect-[16/10] overflow-hidden rounded-2xl bg-slate-100">
        <img 
          src={post.coverImageURL || `https://picsum.photos/seed/${post.id}/800/500`} 
          alt={post.title}
          referrerPolicy="no-referrer"
          className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
        />
        <div className="absolute top-4 left-4 flex flex-col gap-2">
          <span className="badge-label bg-white/90 text-slate-900 shadow-sm backdrop-blur-sm">
            Notícia
          </span>
          <div className="flex flex-col items-center rounded-xl bg-blue-600/90 px-3 py-2 text-white shadow-lg backdrop-blur-sm">
            <span className="text-lg font-black leading-none">{format(post.createdAt, "dd")}</span>
            <span className="text-[10px] font-bold uppercase tracking-tighter">{format(post.createdAt, "MMM", { locale: ptBR })}</span>
          </div>
        </div>
      </div>
      
      <div className="flex flex-col gap-2">
        <div className="flex items-center gap-3 text-[10px] font-bold tracking-widest uppercase text-slate-400">
          <span className="text-blue-600">{format(post.createdAt, "yyyy")}</span>
          <span className="h-1 w-1 rounded-full bg-slate-300" />
          <span>5 min de leitura</span>
        </div>
        
        <h3 className="text-xl font-bold leading-tight text-slate-900 transition-colors group-hover:text-blue-600 sm:text-2xl">
          {post.title}
        </h3>
        
        <p className="line-clamp-2 text-sm leading-relaxed text-slate-500">
          {post.content}
        </p>
        
        <div className="mt-2 flex items-center gap-4 border-t border-slate-100 pt-4">
          <div className="flex items-center gap-1 text-slate-400 transition-colors hover:text-red-500">
            <Heart className="h-4 w-4" />
            <span className="text-xs font-bold">24</span>
          </div>
          <div className="flex items-center gap-1 text-slate-400 transition-colors hover:text-blue-500">
            <MessageCircle className="h-4 w-4" />
            <span className="text-xs font-bold">12</span>
          </div>
          <div className="relative">
            <button 
              onClick={handleShare}
              className="flex items-center gap-1 text-slate-400 transition-colors hover:text-blue-600"
              title="Compartilhar"
            >
              <AnimatePresence mode="wait">
                {copied ? (
                  <motion.div
                    key="check"
                    initial={{ scale: 0.5, opacity: 0 }}
                    animate={{ scale: 1, opacity: 1 }}
                    exit={{ scale: 0.5, opacity: 0 }}
                  >
                    <Check className="h-4 w-4 text-emerald-500" />
                  </motion.div>
                ) : (
                  <motion.div
                    key="share"
                    initial={{ scale: 0.5, opacity: 0 }}
                    animate={{ scale: 1, opacity: 1 }}
                    exit={{ scale: 0.5, opacity: 0 }}
                  >
                    <Share2 className="h-4 w-4" />
                  </motion.div>
                )}
              </AnimatePresence>
              <span className={cn("text-xs font-bold", copied && "text-emerald-600")}>
                {copied ? 'Copiado!' : 'Compartilhar'}
              </span>
            </button>
          </div>
          <button className="ml-auto text-xs font-bold tracking-widest uppercase text-slate-900 transition-colors hover:text-blue-600">
            Ler mais
          </button>
        </div>
      </div>
    </motion.article>
  );
};
