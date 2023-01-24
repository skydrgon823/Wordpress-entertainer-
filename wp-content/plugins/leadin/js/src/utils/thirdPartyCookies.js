const startHtmlUrl =
  'https://static.hsappstatic.net/integrations-third-party-cookies/static-1.1/html/start.html';

/**
 * Checks if third-party cookies are enabled or not
 * on the current browser session.
 *
 * @returns {Promise<Boolean>}
 *  A promise that will always resolve with a boolean indicating if third-party cookies are enabled or not.
 */
export const checkThirdPartyCookies = () => {
  return new Promise(resolve => {
    try {
      const iframe = document.createElement('iframe');
      iframe.id = 'integrations-third-party-cookies-iframe';
      iframe.src = startHtmlUrl;
      iframe.style.display = 'none';

      const receiveMessage = function(evt) {
        if (evt.data.source === 'integrations-third-party-cookies-message') {
          resolve(evt.data.enabled);

          iframe.parentNode.removeChild(iframe);
          window.removeEventListener('message', receiveMessage, false);
        }
      };

      window.addEventListener('message', receiveMessage, false);

      document.body.appendChild(iframe);
    } catch (err) {
      resolve(false);
    }
  });
};
