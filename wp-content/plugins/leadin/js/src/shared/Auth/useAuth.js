import { useEffect, useState } from 'react';
import { getAuth } from '../../api/hubspotPluginApi';

export default function useAuth() {
  const [auth, setAuth] = useState(false);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    getAuth()
      .then(response => {
        setAuth(!!response);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, []);

  return { auth, loading };
}
