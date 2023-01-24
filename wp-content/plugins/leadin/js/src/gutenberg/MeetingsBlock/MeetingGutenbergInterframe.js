import Raven from 'raven-js';

export const meetingsGutenbergInterframe = (function() {
  let callback;

  return {
    executeCallback(args) {
      if (callback) {
        Raven.context(callback, args);
      }
    },
    setCallback(callbackFunc) {
      callback = callbackFunc;
    },
  };
})();

export function gutenbergTriggerConnectCalendarRefresh(args) {
  meetingsGutenbergInterframe.executeCallback(args);
}
