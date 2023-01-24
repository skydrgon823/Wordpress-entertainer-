import $ from 'jquery';

import Raven from '../lib/Raven';
import { ajaxUrl, ajaxNonce } from '../constants/leadinConfig';

function makeAjaxRequest(action, method, payload) {
  const url = `${ajaxUrl}?action=${action}&_ajax_nonce=${ajaxNonce}`;
  return new Promise((resolve, reject) => {
    const ajaxPayload = {
      url,
      method,
      contentType: 'application/json',
      success: data => resolve(data),
      error: response => {
        Raven.captureMessage(
          `HTTP request ${action} failed with code ${response.status}: ${response.message}`
        );
        reject(response);
      },
    };

    if (payload) {
      ajaxPayload.data = JSON.stringify(payload);
    }

    $.ajax(ajaxPayload);
  });
}

export function postAjax(action, payload) {
  return makeAjaxRequest(action, 'post', payload);
}

export function getAjax(action) {
  return makeAjaxRequest(action, 'get');
}

export function leadinConnectPortal(portalInfo) {
  return postAjax('leadin_registration_ajax', portalInfo);
}

export function leadinDisconnectPortal() {
  return postAjax('leadin_disconnect_ajax', {});
}

export function skipSignup(defaultApp) {
  return postAjax('leadin_skip_signup', { defaultApp });
}

export function skipReview() {
  return postAjax('leadin_skip_review');
}

export function updateHublet(hublet) {
  return postAjax('leadin_update_hublet', { hublet });
}

export function getPortalHublet(hublet) {
  return postAjax('leadin_get_portal_hublet', { hublet });
}

export function trackConsent(canTrack) {
  return postAjax('leadin_track_consent', { canTrack });
}

export function disableInternalTracking(value) {
  return postAjax('leadin_disable_internal_tracking', value ? '1' : '0');
}

export function fetchDisableInternalTracking() {
  return getAjax('leadin_disable_internal_tracking');
}
