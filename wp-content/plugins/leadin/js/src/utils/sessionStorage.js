export function getSessionStorage(item) {
  try {
    return sessionStorage.getItem(item);
  } catch (e) {
    return null;
  }
}

export function setSessionStorage(item, value) {
  try {
    sessionStorage.setItem(item, value);
  } catch (e) {
    // no-op
  }
}

export function deleteSessionStorage(item) {
  try {
    sessionStorage.removeItem(item);
  } catch (e) {
    // no-op
  }
}
