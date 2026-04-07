import { 
  collection, 
  query, 
  where, 
  getDocs, 
  addDoc, 
  updateDoc, 
  deleteDoc, 
  doc, 
  onSnapshot, 
  orderBy, 
  Timestamp,
  getDoc,
  setDoc
} from 'firebase/firestore';
import { db, auth } from '../lib/firebase';
import { Post, Category, Company, Comment, Like, User, AuditLog, SystemSettings } from '../domain/entities';

console.log('Firestore DB initialized:', db);

enum OperationType {
  CREATE = 'create',
  UPDATE = 'update',
  DELETE = 'delete',
  LIST = 'list',
  GET = 'get',
  WRITE = 'write',
}

interface FirestoreErrorInfo {
  error: string;
  operationType: OperationType;
  path: string | null;
  authInfo: any;
}

function handleFirestoreError(error: unknown, operationType: OperationType, path: string | null) {
  const errInfo: FirestoreErrorInfo = {
    error: error instanceof Error ? error.message : String(error),
    authInfo: {
      userId: auth.currentUser?.uid,
      email: auth.currentUser?.email,
      emailVerified: auth.currentUser?.emailVerified,
    },
    operationType,
    path
  };
  console.error('Firestore Error: ', JSON.stringify(errInfo));
  throw new Error(JSON.stringify(errInfo));
}

export const AuditLogService = {
  async logAction(action: string, userId: string, targetId?: string, details?: string) {
    const path = 'audit_logs';
    try {
      const logData: any = {
        action,
        userId,
        createdAt: Timestamp.now()
      };
      
      if (targetId !== undefined) logData.targetId = targetId;
      if (details !== undefined) logData.details = details;

      await addDoc(collection(db, path), logData);
    } catch (error) {
      console.error('Failed to log action:', error);
    }
  },

  async getAll() {
    const path = 'audit_logs';
    try {
      const q = query(collection(db, path), orderBy('createdAt', 'desc'));
      const snapshot = await getDocs(q);
      return snapshot.docs.map(doc => ({ 
        id: doc.id, 
        ...doc.data(), 
        createdAt: (doc.data().createdAt as Timestamp).toDate() 
      } as AuditLog));
    } catch (error) {
      handleFirestoreError(error, OperationType.LIST, path);
    }
  }
};

export const SettingsService = {
  async getSettings() {
    const path = 'settings/global';
    try {
      const docRef = doc(db, 'settings', 'global');
      const snapshot = await getDoc(docRef);
      if (snapshot.exists()) {
        return snapshot.data() as SystemSettings;
      }
      return { maintenanceMode: false, siteName: 'Guia da Cidade', allowNewRegistrations: true };
    } catch (error) {
      handleFirestoreError(error, OperationType.GET, path);
    }
  },

  async updateSettings(settings: SystemSettings) {
    const path = 'settings/global';
    try {
      const docRef = doc(db, 'settings', 'global');
      await setDoc(docRef, settings);
      if (auth.currentUser) {
        await AuditLogService.logAction('UPDATE_SETTINGS', auth.currentUser.uid, 'global', JSON.stringify(settings));
      }
    } catch (error) {
      handleFirestoreError(error, OperationType.UPDATE, path);
    }
  }
};

export const PostService = {
  async create(post: Omit<Post, 'id'>) {
    const path = 'posts';
    try {
      const docRef = await addDoc(collection(db, path), {
        ...post,
        createdAt: Timestamp.fromDate(post.createdAt)
      });
      if (auth.currentUser) {
        await AuditLogService.logAction('CREATE_POST', auth.currentUser.uid, docRef.id, post.title);
      }
      return docRef.id;
    } catch (error) {
      handleFirestoreError(error, OperationType.CREATE, path);
    }
  },

  async update(id: string, post: Partial<Post>) {
    const path = `posts/${id}`;
    try {
      const docRef = doc(db, 'posts', id);
      const updateData = { ...post };
      if (post.createdAt) updateData.createdAt = Timestamp.fromDate(post.createdAt) as any;
      await updateDoc(docRef, updateData);
      if (auth.currentUser) {
        await AuditLogService.logAction('UPDATE_POST', auth.currentUser.uid, id, post.title);
      }
    } catch (error) {
      handleFirestoreError(error, OperationType.UPDATE, path);
    }
  },

  async delete(id: string) {
    const path = `posts/${id}`;
    try {
      const docRef = doc(db, 'posts', id);
      await deleteDoc(docRef);
      if (auth.currentUser) {
        await AuditLogService.logAction('DELETE_POST', auth.currentUser.uid, id);
      }
    } catch (error) {
      handleFirestoreError(error, OperationType.DELETE, path);
    }
  },
  async getById(id: string) {
    const path = `posts/${id}`;
    try {
      const docRef = doc(db, 'posts', id);
      const snapshot = await getDoc(docRef);
      if (snapshot.exists()) {
        return { id: snapshot.id, ...snapshot.data(), createdAt: (snapshot.data().createdAt as Timestamp).toDate() } as Post;
      }
      return null;
    } catch (error) {
      handleFirestoreError(error, OperationType.GET, path);
    }
  },

  async getAllPublished() {
    const path = 'posts';
    try {
      // Query only with status filter to avoid composite index requirement for orderBy
      const q = query(collection(db, path), where('status', '==', 'published'));
      const snapshot = await getDocs(q);
      const posts = snapshot.docs.map(doc => ({ 
        id: doc.id, 
        ...doc.data(), 
        createdAt: (doc.data().createdAt as Timestamp).toDate() 
      } as Post));
      
      // Sort in memory: newest first
      return posts.sort((a, b) => b.createdAt.getTime() - a.createdAt.getTime());
    } catch (error) {
      handleFirestoreError(error, OperationType.LIST, path);
    }
  },

  subscribeToPublished(callback: (posts: Post[]) => void) {
    const path = 'posts';
    const q = query(collection(db, path));
    return onSnapshot(q, (snapshot) => {
      callback(snapshot.docs.map(doc => ({ id: doc.id, ...doc.data(), createdAt: (doc.data().createdAt ? (doc.data().createdAt as Timestamp).toDate() : new Date()) } as Post)));
    }, (error) => handleFirestoreError(error, OperationType.LIST, path));
  }
};

export const CompanyService = {
  async create(company: Omit<Company, 'id'>) {
    const path = 'companies';
    try {
      const docRef = await addDoc(collection(db, path), {
        ...company,
        createdAt: Timestamp.fromDate(company.createdAt)
      });
      if (auth.currentUser) {
        await AuditLogService.logAction('CREATE_COMPANY', auth.currentUser.uid, docRef.id, company.name);
      }
      return docRef.id;
    } catch (error) {
      handleFirestoreError(error, OperationType.CREATE, path);
    }
  },

  async update(id: string, company: Partial<Company>) {
    const path = `companies/${id}`;
    try {
      const docRef = doc(db, 'companies', id);
      const updateData = { ...company };
      if (company.createdAt) updateData.createdAt = Timestamp.fromDate(company.createdAt) as any;
      await updateDoc(docRef, updateData);
      if (auth.currentUser) {
        await AuditLogService.logAction('UPDATE_COMPANY', auth.currentUser.uid, id, company.name);
      }
    } catch (error) {
      handleFirestoreError(error, OperationType.UPDATE, path);
    }
  },

  async delete(id: string) {
    const path = `companies/${id}`;
    try {
      const docRef = doc(db, 'companies', id);
      await deleteDoc(docRef);
      if (auth.currentUser) {
        await AuditLogService.logAction('DELETE_COMPANY', auth.currentUser.uid, id);
      }
    } catch (error) {
      handleFirestoreError(error, OperationType.DELETE, path);
    }
  },
  async getAll() {
    const path = 'companies';
    try {
      const q = query(collection(db, path), orderBy('name', 'asc'));
      const snapshot = await getDocs(q);
      return snapshot.docs.map(doc => ({ id: doc.id, ...doc.data(), createdAt: (doc.data().createdAt as Timestamp).toDate() } as Company));
    } catch (error) {
      handleFirestoreError(error, OperationType.LIST, path);
    }
  },

  subscribe(callback: (companies: Company[]) => void) {
    const path = 'companies';
    const q = query(collection(db, path), orderBy('name', 'asc'));
    return onSnapshot(q, (snapshot) => {
      callback(snapshot.docs.map(doc => ({ id: doc.id, ...doc.data(), createdAt: (doc.data().createdAt as Timestamp).toDate() } as Company)));
    }, (error) => handleFirestoreError(error, OperationType.LIST, path));
  }
};

export const CategoryService = {
  async create(category: Omit<Category, 'id'>) {
    const path = 'categories';
    try {
      const docRef = await addDoc(collection(db, path), category);
      if (auth.currentUser) {
        await AuditLogService.logAction('CREATE_CATEGORY', auth.currentUser.uid, docRef.id, category.name);
      }
      return docRef.id;
    } catch (error) {
      handleFirestoreError(error, OperationType.CREATE, path);
    }
  },

  async update(id: string, category: Partial<Category>) {
    const path = `categories/${id}`;
    try {
      const docRef = doc(db, 'categories', id);
      await updateDoc(docRef, category);
      if (auth.currentUser) {
        await AuditLogService.logAction('UPDATE_CATEGORY', auth.currentUser.uid, id, category.name);
      }
    } catch (error) {
      handleFirestoreError(error, OperationType.UPDATE, path);
    }
  },

  async delete(id: string) {
    const path = `categories/${id}`;
    try {
      const docRef = doc(db, 'categories', id);
      await deleteDoc(docRef);
      if (auth.currentUser) {
        await AuditLogService.logAction('DELETE_CATEGORY', auth.currentUser.uid, id);
      }
    } catch (error) {
      handleFirestoreError(error, OperationType.DELETE, path);
    }
  },
  async getAll() {
    const path = 'categories';
    try {
      const snapshot = await getDocs(collection(db, path));
      return snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() } as Category));
    } catch (error) {
      handleFirestoreError(error, OperationType.LIST, path);
    }
  }
};

export const UserService = {
  async getProfile(uid: string) {
    const path = `users/${uid}`;
    try {
      const docRef = doc(db, 'users', uid);
      const snapshot = await getDoc(docRef);
      if (snapshot.exists()) {
        return { id: snapshot.id, ...snapshot.data(), createdAt: (snapshot.data().createdAt as Timestamp).toDate() } as User;
      }
      return null;
    } catch (error) {
      handleFirestoreError(error, OperationType.GET, path);
    }
  },

  async createProfile(user: User) {
    const path = `users/${user.id}`;
    try {
      await setDoc(doc(db, 'users', user.id), {
        ...user,
        createdAt: Timestamp.fromDate(user.createdAt)
      });
      await AuditLogService.logAction('CREATE_PROFILE', user.id, user.id, user.username);
    } catch (error) {
      handleFirestoreError(error, OperationType.CREATE, path);
    }
  },

  async getAllUsers() {
    const path = 'users';
    try {
      const snapshot = await getDocs(collection(db, path));
      return snapshot.docs.map(doc => ({ 
        id: doc.id, 
        ...doc.data(), 
        createdAt: (doc.data().createdAt as Timestamp).toDate() 
      } as User));
    } catch (error) {
      handleFirestoreError(error, OperationType.LIST, path);
    }
  },

  async updateUserRole(uid: string, role: User['role']) {
    const path = `users/${uid}`;
    try {
      const docRef = doc(db, 'users', uid);
      await updateDoc(docRef, { role });
      if (auth.currentUser) {
        await AuditLogService.logAction('UPDATE_USER_ROLE', auth.currentUser.uid, uid, role);
      }
    } catch (error) {
      handleFirestoreError(error, OperationType.UPDATE, path);
    }
  },

  async toggleBan(uid: string, isBanned: boolean) {
    const path = `users/${uid}`;
    try {
      const docRef = doc(db, 'users', uid);
      await updateDoc(docRef, { isBanned });
      if (auth.currentUser) {
        await AuditLogService.logAction(isBanned ? 'BAN_USER' : 'UNBAN_USER', auth.currentUser.uid, uid);
      }
    } catch (error) {
      handleFirestoreError(error, OperationType.UPDATE, path);
    }
  },

  async deleteUser(uid: string) {
    const path = `users/${uid}`;
    try {
      const docRef = doc(db, 'users', uid);
      await deleteDoc(docRef);
      if (auth.currentUser) {
        await AuditLogService.logAction('DELETE_USER', auth.currentUser.uid, uid);
      }
    } catch (error) {
      handleFirestoreError(error, OperationType.DELETE, path);
    }
  }
};

export const CommentService = {
  async create(comment: Omit<Comment, 'id'>) {
    const path = 'comments';
    try {
      const docRef = await addDoc(collection(db, path), {
        ...comment,
        createdAt: Timestamp.fromDate(comment.createdAt)
      });
      if (auth.currentUser) {
        await AuditLogService.logAction('CREATE_COMMENT', auth.currentUser.uid, docRef.id, comment.postId);
      }
      return docRef.id;
    } catch (error) {
      handleFirestoreError(error, OperationType.CREATE, path);
    }
  },

  async delete(id: string) {
    const path = `comments/${id}`;
    try {
      const docRef = doc(db, 'comments', id);
      await deleteDoc(docRef);
      if (auth.currentUser) {
        await AuditLogService.logAction('DELETE_COMMENT', auth.currentUser.uid, id);
      }
    } catch (error) {
      handleFirestoreError(error, OperationType.DELETE, path);
    }
  }
};

export const LikeService = {
  async toggle(postId: string, userId: string) {
    const path = 'likes';
    try {
      const q = query(collection(db, path), where('postId', '==', postId), where('userId', '==', userId));
      const snapshot = await getDocs(q);
      
      if (snapshot.empty) {
        const docRef = await addDoc(collection(db, path), {
          postId,
          userId,
          createdAt: Timestamp.now()
        });
        await AuditLogService.logAction('LIKE_POST', userId, postId);
        return true;
      } else {
        await deleteDoc(doc(db, path, snapshot.docs[0].id));
        await AuditLogService.logAction('UNLIKE_POST', userId, postId);
        return false;
      }
    } catch (error) {
      handleFirestoreError(error, OperationType.WRITE, path);
    }
  }
};
