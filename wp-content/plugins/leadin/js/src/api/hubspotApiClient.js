import { makeProxyRequest } from './wordpressApiClient';

const FORMS_PATH = `/forms/v2/forms`;

export function fetchForms(searchQuery = '', offset = 0, limit = 10) {
  const queryParams = {
    offset,
    limit,
    formTypes: ['HUBSPOT'],
  };

  if (searchQuery) {
    queryParams.name__contains = searchQuery;
  }

  return makeProxyRequest('get', FORMS_PATH, {}, queryParams).then(forms => {
    const filteredForms = [];

    forms.forEach(currentForm => {
      const { guid, name } = currentForm;
      filteredForms.push({ name, guid });
    });

    return filteredForms;
  });
}

export function createForm(payload) {
  return makeProxyRequest('post', FORMS_PATH, payload);
}
