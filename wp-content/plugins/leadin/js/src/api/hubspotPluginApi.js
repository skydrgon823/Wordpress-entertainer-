import Raven from '../lib/Raven';
import { meetingsGutenbergInterframe } from '../gutenberg/MeetingsBlock/MeetingGutenbergInterframe';

function callInterframeMethod(method, ...args) {
  return window.leadinChildFrameConnection.promise.then(child =>
    Raven.context(child[method], args)
  );
}

export function getAuth() {
  return callInterframeMethod('leadinGetAuth');
}

export function getMeetings() {
  return callInterframeMethod('leadinGetMeetings');
}

export function getMeetingUser() {
  return callInterframeMethod('leadinGetMeetingUser');
}

export function getMeetingUsers(ids) {
  return callInterframeMethod('leadinGetMeetingUsers', ids);
}

export function createMeetingUser(data) {
  return callInterframeMethod('leadinPostMeetingUser', data);
}

export function getForm(formId) {
  return callInterframeMethod('leadinGetForm', formId);
}

export function monitorFormPreviewRender(origin = 'gutenberg') {
  return callInterframeMethod('monitorFormPreviewRender', origin);
}

export function monitorFormCreatedFromTemplate(type, origin = 'gutenberg') {
  return callInterframeMethod('monitorFormCreatedFromTemplate', type, origin);
}

export function monitorFormCreationFailed(error, origin = 'gutenberg') {
  return callInterframeMethod('monitorFormCreationFailed', error, origin);
}

export function monitorMeetingPreviewRender(origin = 'gutenberg') {
  return callInterframeMethod('monitorMeetingPreviewRender', origin);
}

export function monitorSidebarMetaChange(metaKey) {
  return callInterframeMethod('monitorSidebarMetaChange', metaKey);
}

export function monitorReviewBannerRendered() {
  return callInterframeMethod('monitorReviewBannerRendered');
}

export function monitorReviewBannerLinkClicked() {
  return callInterframeMethod('monitorReviewBannerLinkClicked');
}

export function monitorReviewBannerDismissed() {
  return callInterframeMethod('monitorReviewBannerDismissed');
}

export function leadinConnectCalendar(calendarArgs) {
  const { hubspotBaseUrl, portalId, triggerReload } = calendarArgs;
  meetingsGutenbergInterframe.setCallback(triggerReload);

  return callInterframeMethod('leadinConnectCalendar', {
    hubspotBaseUrl,
    portalId,
  });
}
