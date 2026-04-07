export type UserRole = 'admin' | 'standard' | 'company' | 'publisher';

export interface User {
  id: string;
  username: string;
  email: string;
  role: UserRole;
  isBanned?: boolean;
  createdAt: Date;
}

export interface Post {
  id: string;
  title: string;
  slug: string;
  content: string;
  coverImageURL?: string;
  status: 'draft' | 'published';
  categoryId: string;
  authorId: string;
  createdAt: Date;
}

export interface Category {
  id: string;
  name: string;
  slug: string;
  description?: string;
}

export interface Company {
  id: string;
  name: string;
  taxId: string;
  category: string;
  size: 'small' | 'medium' | 'large';
  description?: string;
  address?: string;
  phone?: string;
  website?: string;
  logoURL?: string;
  createdAt: Date;
}

export interface Comment {
  id: string;
  text: string;
  userId: string;
  postId: string;
  createdAt: Date;
}

export interface Like {
  id: string;
  userId: string;
  postId: string;
  createdAt: Date;
}

export interface AuditLog {
  id: string;
  action: string;
  userId: string;
  targetId?: string;
  details?: string;
  createdAt: Date;
}

export interface SystemSettings {
  maintenanceMode: boolean;
  siteName: string;
  allowNewRegistrations: boolean;
}
