import { initInterframe } from '../lib/Interframe';
import {
  backgroundIframeUrl,
  impactLink,
  iframeUrl,
} from '../constants/leadinConfig';

function getIframeHeight() {
  const adminMenuWrap = document.getElementById('adminmenuwrap');
  const sideMenuHeight = adminMenuWrap ? adminMenuWrap.offsetHeight : 0;
  const adminBar = document.getElementById('wpadminbar');
  const adminBarHeight = (adminBar && adminBar.offsetHeight) || 0;

  if (window.innerHeight < sideMenuHeight) {
    return sideMenuHeight;
  } else {
    return window.innerHeight - adminBarHeight;
  }
}

function addIframeResizeEvent(iframe) {
  let animationFrame;
  window.addEventListener(
    'resize',
    () => {
      if (animationFrame) {
        cancelAnimationFrame(animationFrame);
      }
      animationFrame = requestAnimationFrame(() => {
        iframe.style.minHeight = `${getIframeHeight()}px`;
      });
    },
    true
  );
}

function createIframeElement(iframeSrc) {
  const iframe = document.createElement('iframe');
  iframe.id = 'leadin-iframe';
  iframe.src = iframeSrc;
  iframe.setAttribute('referrerpolicy', 'no-referrer-when-downgrade');
  iframe.setAttribute(
    'sandbox',
    'allow-scripts allow-forms allow-popups allow-top-navigation allow-same-origin'
  );
  iframe.style.minHeight = `${getIframeHeight()}px`;
  addIframeResizeEvent(iframe);
  return iframe;
}

export function createIframe() {
  // FIXME: We need to investigate why the Iframe connection is not succeding with an impactLink
  const link = impactLink
    ? `${impactLink}?u=${encodeURIComponent(`${iframeUrl}&trackConsent=0`)}`
    : iframeUrl;
  const iframe = createIframeElement(link);
  initInterframe(iframe);
  document.getElementById('leadin-iframe-container').appendChild(iframe);
}

export function createBackgroundIframe() {
  const iframe = createIframeElement(backgroundIframeUrl);
  iframe.style.display = 'none';
  initInterframe(iframe);
  document.body.appendChild(iframe);
}
