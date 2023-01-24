import $ from 'jquery';

import Raven from '../lib/Raven';
import { restNonce, restUrl } from '../constants/leadinConfig';
import { addQueryObjectToUrl } from '../utils/queryParams';

function makeRequest(method, path, data, queryParams = {}) {
  // eslint-disable-next-line compat/compat
  const restApiUrl = new URL(`${restUrl}leadin/v1${path}`);
  addQueryObjectToUrl(restApiUrl, queryParams);

  return new Promise((resolve, reject) => {
    const payload = {
      url: restApiUrl.toString(),
      method,
      contentType: 'application/json',
      beforeSend: xhr => xhr.setRequestHeader('X-WP-Nonce', restNonce),
      success: resolve,
      error: response => {
        Raven.captureMessage(
          `HTTP Request to ${restApiUrl} failed with error ${response.status}: ${response.responseText}`,
          {
            fingerprint: [
              '{{ default }}',
              path,
              response.status,
              response.responseText,
            ],
          }
        );
        reject(response);
      },
    };

    if (method !== 'get') {
      payload.data = JSON.stringify(data);
    }

    $.ajax(payload);
  });
}

export function makeProxyRequest(
  method,
  hubspotApiPath,
  data,
  queryParamsObject = {}
) {
  const proxyApiPath = `/proxy`;
  // eslint-disable-next-line compat/compat
  const proxyQueryParams = new URLSearchParams(queryParamsObject).toString();
  const proxyUrl = `${hubspotApiPath}?${proxyQueryParams}`;

  return makeRequest(method, proxyApiPath, data, { proxyUrl });
}

export function fetchOAuthToken() {
  return makeRequest('GET', '/oauth-token').catch(err => {
    return { status: err.status, message: err.responseText };
  });
}

/**
 * To surface errors to the interframe, we need to catch the error
 * and return it to through penpal as a normal message, which the iframe
 * can check for and re-throw.
 */
export function makeInterframeProxyRequest(...args) {
  return makeProxyRequest(...args).catch(err => {
    return { status: err.status, message: err.responseText };
  });
}

export function healthcheckRestApi() {
  return makeRequest('get', '/healthcheck');
}
