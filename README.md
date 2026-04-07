# Guia da Cidade 🏙️

Uma plataforma robusta e elegante de "Guia da Cidade", projetada como uma solução white-label para municípios. Conecta cidadãos a notícias locais, eventos culturais e um diretório comercial completo.

## 🚀 Funcionalidades Principais

### 📰 Portal de Notícias
- **Feed Dinâmico:** Lista de notícias e eventos com paginação otimizada.
- **Busca Inteligente:** Filtre conteúdos por termos de interesse em tempo real.
- **Destaques Visuais:** Cards de notícias com datas proeminentes e tempo de leitura estimado.
- **Compartilhamento:** Botão rápido para copiar o link da notícia para a área de transferência.

### 🏢 Diretório de Empresas
- **Guia Comercial:** Catálogo completo de estabelecimentos e serviços locais.
- **Filtros Avançados:** Pesquisa por nome, categoria e porte da empresa (Pequena, Média, Grande).
- **Conexão Direta:** Informações detalhadas sobre os parceiros da cidade.

### 🔐 Gestão de Acessos (RBAC)
- **Usuário Comum:** Acesso à leitura e interação básica.
- **Empresa:** Painel exclusivo para gerenciar promoções e visualizar métricas de alcance.
- **Publicador:** Dashboard para criação e acompanhamento de artigos e notícias.
- **Administrador:** Controle total sobre a plataforma, usuários e configurações globais.

### 🛠️ Painel Administrativo
- **Gestão de Usuários:** Alteração de cargos (roles), banimento e exclusão de contas.
- **Configurações do Sistema:** Controle de Modo Manutenção, nome do site e identidade visual.
- **Auditoria Completa:** Registro detalhado de todas as ações críticas (buscas, compartilhamentos, logins, alterações de dados).
- **Seed de Dados:** Ferramenta para popular o banco de dados com informações iniciais de teste.

### 📊 Dashboards de Performance
- **Métricas em Tempo Real:** Visualizações de perfil, cliques em links e engajamento da comunidade.
- **Insights:** Tendências de busca e termos mais procurados pelos cidadãos.

## 🛠️ Tecnologias Utilizadas

- **Frontend:** React 19 + TypeScript
- **Estilização:** Tailwind CSS (Design moderno e responsivo)
- **Backend/Database:** Firebase (Firestore & Authentication)
- **Animações:** Motion (Transições de página e micro-interações)
- **Ícones:** Lucide React
- **Data/Hora:** date-fns
- **Bundler:** Vite

## 📁 Estrutura do Projeto

- `/src/components`: Componentes de UI reutilizáveis.
- `/src/domain`: Entidades e definições de tipos do negócio.
- `/src/infrastructure`: Serviços de integração com Firebase e lógica de dados.
- `/src/pages`: Páginas principais da aplicação.
- `/src/presentation`: Contextos de estado global (Autenticação).
- `/src/lib`: Utilitários e configurações de bibliotecas.

## 🛡️ Segurança e Auditoria

O projeto prioriza a transparência e segurança:
- **Regras de Segurança Firestore:** Proteção rigorosa de dados sensíveis e PII.
- **Logs de Auditoria:** Cada ação administrativa ou de interação relevante é registrada na coleção `audit_logs`, permitindo rastreabilidade total.
- **Modo Manutenção:** Bloqueio instantâneo do acesso público para atualizações críticas.

---

Desenvolvido com foco em performance, acessibilidade e design editorial.
