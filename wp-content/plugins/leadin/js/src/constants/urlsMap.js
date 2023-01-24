import { routes } from './leadinConfig';

const urlsMap = {};
Object.keys(routes).forEach(key => {
  if (typeof routes[key] === 'object') {
    Object.keys(routes[key]).forEach(subkey => {
      urlsMap[routes[key][subkey]] = [key, subkey];
    });
  } else {
    urlsMap[routes[key]] = [key];
  }
});

export default urlsMap;
