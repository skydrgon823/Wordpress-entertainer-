import { openPopup } from './popup';
import { hubspotBaseUrl } from '../constants/leadinConfig';

const LOGIN_WINDOW_NAME = 'HubSpotLoginWindow';
const POPUP_LOGIN_COMPLETED = 'POPUP_LOGIN_COMPLETED';
const locationOrigin = hubspotBaseUrl.replace('local', 'app');

export default function loginThroughPopup(handleLogin) {
  const onLogin =
    typeof handleLogin === 'function'
      ? handleLogin
      : () => window.location.reload();

  const handler = event => {
    if (event.data === POPUP_LOGIN_COMPLETED) {
      window.removeEventListener('message', handler);
      onLogin();
    }
  };

  window.addEventListener('message', handler);
  const next = `${locationOrigin}/popup-login-completed`;

  openPopup(
    `${locationOrigin}/login?loginRedirectUrl=${encodeURIComponent(next)}`,
    LOGIN_WINDOW_NAME
  );
}
