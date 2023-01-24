const CHROMELESS_WINDOW_FEATURES =
  'menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,titlebar=no,toolbar=no';

const DEFAULT_WINDOW_WIDTH = 550;
const DEFAULT_WINDOW_HEIGHT = 750;

const getWindowOptions = (
  width = DEFAULT_WINDOW_WIDTH,
  height = DEFAULT_WINDOW_HEIGHT,
  formatForChrome = false
) => {
  const { screenY, screenX, outerHeight, outerWidth } = window;

  // calculate top and left in order to center the window on the users' screen
  const top = Math.round(
    screenY + Math.max(0, Math.floor(outerHeight - height) / 2)
  );
  const left = Math.round(
    screenX + Math.max(0, Math.floor(outerWidth - width) / 2)
  );

  if (formatForChrome) {
    return { top, left, width, height, type: 'popup' };
  }
  return `${CHROMELESS_WINDOW_FEATURES},width=${width},height=${height},top=${top},left=${left}`;
};

export const openPopup = (url, windowName, options = getWindowOptions()) => {
  return window.open(url, '_blank', options);
};
