import { useEffect, useState } from 'react';
import { getForm } from '../../api/hubspotPluginApi';
import { oauth } from '../../constants/leadinConfig';

// TODO: This hook will dissapear when OAuth rolls out.
export default function useForm(id, name) {
  const [loading, setLoading] = useState(true);
  const [form, setForm] = useState(null);

  useEffect(() => {
    if (!id || oauth) {
      if (name) {
        setForm({ guid: id, name });
      } else {
        setForm(null);
      }
      setLoading(false);
    } else {
      getForm(id)
        .then(response => {
          setForm(response);
          setLoading(false);
        })
        .catch(() => setLoading(false));
    }
  }, [id, name]);

  return { loading, form, setLoading };
}
