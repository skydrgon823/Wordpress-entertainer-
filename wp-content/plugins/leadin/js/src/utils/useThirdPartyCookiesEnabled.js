import { useEffect, useState } from 'react';
import { checkThirdPartyCookies } from './thirdPartyCookies';

export function useThirdPartyCookiesEnabled() {
  const [cookiesEnabled, setCookiesEnabled] = useState(false);

  useEffect(() => {
    checkThirdPartyCookies().then(result =>
      setCookiesEnabled(result && !/firefox/i.test(navigator.userAgent))
    );
  }, []);

  return cookiesEnabled;
}
