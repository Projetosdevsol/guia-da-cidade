import React, { createContext, useContext, useEffect, useState } from 'react';
import { 
  User as FirebaseUser, 
  onAuthStateChanged, 
  signOut, 
  signInWithEmailAndPassword, 
  createUserWithEmailAndPassword,
  updateProfile
} from 'firebase/auth';
import { auth } from '../lib/firebase';
import { User } from '../domain/entities';
import { UserService, AuditLogService } from '../infrastructure/firebaseServices';

interface AuthContextType {
  user: User | null;
  firebaseUser: FirebaseUser | null;
  loading: boolean;
  login: (email: string, password: string) => Promise<void>;
  register: (email: string, password: string, username: string) => Promise<void>;
  logout: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [firebaseUser, setFirebaseUser] = useState<FirebaseUser | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const unsubscribe = onAuthStateChanged(auth, async (fUser) => {
      setFirebaseUser(fUser);
      if (fUser) {
        try {
          let profile = await UserService.getProfile(fUser.uid);
          if (!profile) {
            // This case might happen if registration fails halfway or for existing users without profile
            profile = {
              id: fUser.uid,
              username: fUser.displayName || fUser.email?.split('@')[0] || 'User',
              email: fUser.email || '',
              role: 'standard',
              createdAt: new Date()
            };
            await UserService.createProfile(profile);
          }
          setUser(profile);
        } catch (error) {
          console.error("Error fetching profile:", error);
          setUser(null);
        }
      } else {
        setUser(null);
      }
      setLoading(false);
    });
    return unsubscribe;
  }, []);

  const login = async (email: string, password: string) => {
    const userCredential = await signInWithEmailAndPassword(auth, email, password);
    await AuditLogService.logAction('LOGIN', userCredential.user.uid, userCredential.user.uid, email);
  };

  const register = async (email: string, password: string, username: string) => {
    const userCredential = await createUserWithEmailAndPassword(auth, email, password);
    const fUser = userCredential.user;
    
    await updateProfile(fUser, { displayName: username });
    
    const profile: User = {
      id: fUser.uid,
      username: username,
      email: email,
      role: 'standard',
      createdAt: new Date()
    };
    
    await UserService.createProfile(profile);
    await AuditLogService.logAction('REGISTER', fUser.uid, fUser.uid, email);
    setUser(profile);
  };

  const logout = async () => {
    const uid = auth.currentUser?.uid;
    await signOut(auth);
    if (uid) {
      await AuditLogService.logAction('LOGOUT', uid, uid);
    }
  };

  return (
    <AuthContext.Provider value={{ user, firebaseUser, loading, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
