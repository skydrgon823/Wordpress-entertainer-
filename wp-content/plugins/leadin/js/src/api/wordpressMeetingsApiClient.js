import { makeProxyRequest } from './wordpressApiClient';

const WordpressServiceUrls = {
  GetMeetingsPath: '/wordpress/v1/meetings/links',
  CreateMeetingUserPath: '/wordpress/v1/meetings/user',
};

export function fetchMeetingsAndUsers() {
  return makeProxyRequest('get', WordpressServiceUrls.GetMeetingsPath, {}, {});
}

export function getOrCreateMeetingUser(defaultMeetingUser) {
  return makeProxyRequest(
    'post',
    WordpressServiceUrls.CreateMeetingUserPath,
    defaultMeetingUser,
    {}
  );
}
