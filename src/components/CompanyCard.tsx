import React from 'react';
import { Company } from '../domain/entities';
import { Building2, MapPin, Phone, Globe } from 'lucide-react';
import { motion } from 'motion/react';
import { cn } from '../lib/utils';

interface CompanyCardProps {
  company: Company;
}

export const CompanyCard: React.FC<CompanyCardProps> = ({ company }) => {
  const sizeColors = {
    small: "bg-emerald-50 text-emerald-700 border-emerald-100",
    medium: "bg-amber-50 text-amber-700 border-amber-100",
    large: "bg-indigo-50 text-indigo-700 border-indigo-100"
  };

  return (
    <motion.div 
      initial={{ opacity: 0, y: 10 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      className="group flex flex-col rounded-2xl border border-slate-200 bg-white p-6 transition-all hover:border-blue-200 hover:shadow-xl hover:shadow-blue-500/5"
    >
      <div className="mb-6 flex items-start justify-between">
        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-400 ring-1 ring-slate-100 transition-colors group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:ring-blue-100">
          {company.logoURL ? (
            <img src={company.logoURL} alt={company.name} className="h-full w-full rounded-2xl object-cover" referrerPolicy="no-referrer" />
          ) : (
            <Building2 className="h-7 w-7" />
          )}
        </div>
        <span className={cn(
          "badge-label border",
          sizeColors[company.size]
        )}>
          {company.size === 'small' ? 'Pequena' : company.size === 'medium' ? 'Média' : 'Grande'}
        </span>
      </div>
      
      <div className="mb-4">
        <h3 className="font-serif text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{company.name}</h3>
        <span className="text-[10px] font-bold uppercase tracking-widest text-blue-600">{company.category}</span>
      </div>
      
      <p className="mb-6 line-clamp-2 text-sm leading-relaxed text-slate-500">
        {company.description || "Nenhuma descrição detalhada disponível para esta empresa no momento."}
      </p>
      
      <div className="mt-auto space-y-3 border-t border-slate-50 pt-6">
        {company.address && (
          <div className="flex items-center gap-3 text-xs text-slate-500">
            <div className="flex h-6 w-6 items-center justify-center rounded-full bg-slate-50 text-slate-400">
              <MapPin className="h-3.5 w-3.5" />
            </div>
            <span className="truncate font-medium">{company.address}</span>
          </div>
        )}
        <div className="flex items-center gap-4">
          {company.phone && (
            <div className="flex items-center gap-3 text-xs text-slate-500">
              <div className="flex h-6 w-6 items-center justify-center rounded-full bg-slate-50 text-slate-400">
                <Phone className="h-3.5 w-3.5" />
              </div>
              <span className="font-medium">{company.phone}</span>
            </div>
          )}
          {company.website && (
            <a 
              href={company.website} 
              target="_blank" 
              rel="noopener noreferrer"
              className="flex items-center gap-3 text-xs text-blue-600 transition-colors hover:text-blue-700"
            >
              <div className="flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                <Globe className="h-3.5 w-3.5" />
              </div>
              <span className="truncate font-bold uppercase tracking-widest">Site</span>
            </a>
          )}
        </div>
      </div>
    </motion.div>
  );
};
