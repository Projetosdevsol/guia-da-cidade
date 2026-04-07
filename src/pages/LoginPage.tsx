import React, { useEffect, useState } from 'react';
import { useAuth } from '../presentation/AuthContext';
import { useNavigate, useLocation } from 'react-router-dom';
import { LogIn, UserPlus, Mail, Lock, User as UserIcon, AlertCircle, Loader2 } from 'lucide-react';
import { motion, AnimatePresence } from 'motion/react';
import { cn } from '../lib/utils';
import { SettingsService } from '../infrastructure/firebaseServices';
import { SystemSettings } from '../domain/entities';

export const LoginPage: React.FC = () => {
  const { user, login, register, loading } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const from = location.state?.from?.pathname || "/";

  const [isRegistering, setIsRegistering] = useState(false);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [username, setUsername] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [settings, setSettings] = useState<SystemSettings | null>(null);

  useEffect(() => {
    SettingsService.getSettings().then(setSettings);
  }, []);

  useEffect(() => {
    if (user && !loading) {
      navigate(from, { replace: true });
    }
  }, [user, loading, navigate, from]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);

    if (isRegistering && settings && !settings.allowNewRegistrations) {
      setError('Novos registros estão desativados no momento.');
      return;
    }

    setIsSubmitting(true);

    try {
      if (isRegistering) {
        if (!username.trim()) {
          throw new Error('Nome de usuário é obrigatório');
        }
        await register(email, password, username);
      } else {
        await login(email, password);
      }
    } catch (err: any) {
      console.error(err);
      let message = 'Ocorreu um erro ao processar sua solicitação.';
      if (err.code === 'auth/user-not-found' || err.code === 'auth/wrong-password' || err.code === 'auth/invalid-credential') {
        message = 'E-mail ou senha incorretos.';
      } else if (err.code === 'auth/email-already-in-use') {
        message = 'Este e-mail já está em uso.';
      } else if (err.code === 'auth/weak-password') {
        message = 'A senha deve ter pelo menos 6 caracteres.';
      } else if (err.code === 'auth/invalid-email') {
        message = 'E-mail inválido.';
      } else if (err.message) {
        message = err.message;
      }
      setError(message);
    } finally {
      setIsSubmitting(false);
    }
  };

  if (loading) return null;

  return (
    <div className="flex flex-col items-center justify-center px-4">
      <motion.div 
        initial={{ opacity: 0, y: 30 }}
        animate={{ opacity: 1, y: 0 }}
        className="w-full max-w-md overflow-hidden rounded-[2.5rem] border border-slate-100 bg-white p-10 shadow-2xl shadow-slate-200/50 sm:p-14"
      >
        <div className="mb-10 text-center">
          <div className="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-900 text-white shadow-xl shadow-slate-900/20">
            <span className="font-serif text-3xl font-bold italic">G</span>
          </div>
          <h1 className="mb-2 text-3xl font-black tracking-tight text-slate-900">
            {isRegistering ? 'Criar Conta' : 'Bem-vindo.'}
          </h1>
          <p className="text-sm font-medium leading-relaxed text-slate-500">
            {isRegistering 
              ? 'Junte-se à nossa comunidade e participe da sua cidade.' 
              : 'Acesse sua conta para interagir e participar da vida da sua cidade.'}
          </p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-5">
          <AnimatePresence mode="wait">
            {error && (
              <motion.div 
                initial={{ opacity: 0, height: 0 }}
                animate={{ opacity: 1, height: 'auto' }}
                exit={{ opacity: 0, height: 0 }}
                className="flex items-center gap-3 rounded-xl bg-red-50 p-4 text-xs font-bold text-red-600 ring-1 ring-red-100"
              >
                <AlertCircle className="h-4 w-4 shrink-0" />
                {error}
              </motion.div>
            )}
          </AnimatePresence>

          {isRegistering && (
            <div className="space-y-2">
              <label className="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Nome de Usuário</label>
              <div className="relative">
                <UserIcon className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                <input 
                  type="text"
                  required
                  value={username}
                  onChange={(e) => setUsername(e.target.value)}
                  placeholder="Como quer ser chamado?"
                  className="w-full rounded-2xl border border-slate-100 bg-slate-50 py-4 pl-12 pr-4 text-sm font-medium outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                />
              </div>
            </div>
          )}

          <div className="space-y-2">
            <label className="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">E-mail</label>
            <div className="relative">
              <Mail className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
              <input 
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="seu@email.com"
                className="w-full rounded-2xl border border-slate-100 bg-slate-50 py-4 pl-12 pr-4 text-sm font-medium outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
              />
            </div>
          </div>

          <div className="space-y-2">
            <label className="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Senha</label>
            <div className="relative">
              <Lock className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
              <input 
                type="password"
                required
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="••••••••"
                className="w-full rounded-2xl border border-slate-100 bg-slate-50 py-4 pl-12 pr-4 text-sm font-medium outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
              />
            </div>
          </div>

          <button 
            type="submit"
            disabled={isSubmitting}
            className="group relative flex w-full items-center justify-center gap-4 overflow-hidden rounded-2xl bg-slate-900 py-5 text-sm font-bold tracking-widest uppercase text-white transition-all hover:bg-blue-600 hover:shadow-xl active:scale-95 disabled:opacity-70 disabled:pointer-events-none"
          >
            {isSubmitting ? (
              <Loader2 className="h-5 w-5 animate-spin" />
            ) : (
              <>
                {isRegistering ? <UserPlus className="h-5 w-5" /> : <LogIn className="h-5 w-5" />}
                {isRegistering ? 'Criar Conta' : 'Entrar'}
              </>
            )}
          </button>
        </form>

        <div className="mt-10 text-center">
          {(!isRegistering || (settings && settings.allowNewRegistrations)) && (
            <button 
              onClick={() => {
                setIsRegistering(!isRegistering);
                setError(null);
              }}
              className="text-xs font-bold tracking-widest uppercase text-slate-500 transition-colors hover:text-blue-600"
            >
              {isRegistering ? 'Já tem uma conta? Entre aqui' : 'Não tem conta? Cadastre-se'}
            </button>
          )}
        </div>

        <div className="mt-10 flex flex-col items-center gap-6">
          <div className="h-px w-full bg-slate-100" />
          <p className="max-w-[240px] text-center text-[10px] font-bold leading-relaxed tracking-widest uppercase text-slate-400">
            Ao entrar, você concorda com nossos <a href="#" className="text-slate-900 underline underline-offset-4">Termos de Uso</a> e <a href="#" className="text-slate-900 underline underline-offset-4">Privacidade</a>.
          </p>
        </div>
      </motion.div>
    </div>
  );
};
