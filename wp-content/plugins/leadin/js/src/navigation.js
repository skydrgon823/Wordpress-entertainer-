import $ from 'jquery';

import { domElements } from './constants/selectors';
import urlsMap from './constants/urlsMap';

function setSelectedMenuItem(url) {
  $(domElements.subMenuButtons).removeClass('current');
  const pageParam = url.match(/\?page=leadin_?\w*/)[0];
  const selectedElement = $(`a[href="admin.php${pageParam}"]`);
  selectedElement.parent().addClass('current');
}

// Given a route like "/settings/forms", parse it into "?page=leadin_settings&leadin_route[]=forms"
export function syncRoute(path = '', searchQuery = '') {
  const baseUrls = Object.keys(urlsMap).sort((a, b) =>
    a.length < b.length ? 1 : -1
  );
  let wpPage;
  let route;

  baseUrls.some(basePath => {
    if (path.indexOf(basePath) === 0) {
      wpPage = urlsMap[basePath][0];
      const routePrefix = urlsMap[basePath][1] || '';
      const cleanedPath = path.replace(basePath, '');
      route = `${routePrefix}${cleanedPath}`.replace(/^\/+/, '');
      return true;
    }
    return false;
  });

  if (!wpPage) {
    return;
  }

  const leadinRouteParam = route
    ? `&${route
        .split('/')
        .map(subRoute => `${encodeURIComponent(`leadin_route[]`)}=${subRoute}`)
        .join('&')}`
    : '';

  const leadinSearchParam = searchQuery.length
    ? `&leadin_search=${encodeURIComponent(searchQuery)}`
    : '';

  const nonce = window.leadinConfig
    ? `&_wpnonce=${window.leadinConfig.routeNonce}`
    : '';

  const newUrl = `?page=${wpPage}${leadinRouteParam}${leadinSearchParam}${nonce}`;

  setSelectedMenuItem(newUrl);
  window.history.replaceState(null, null, newUrl);
}

export const leadinPageReload = () => window.location.reload(true);

export const leadinPageRedirect = path => {
  syncRoute(path);
  leadinPageReload();
};
