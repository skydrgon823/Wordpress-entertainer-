import { useEffect, useState } from 'react';
import { getMeetings, getMeetingUsers } from '../../api/hubspotPluginApi';
import { fetchMeetingsAndUsers } from '../../api/wordpressMeetingsApiClient';
import { useThirdPartyCookiesEnabled } from '../../utils/useThirdPartyCookiesEnabled';

let meetings = [];
let meetingUsers = [];

function loadMeetings(cookiesEnabled = false) {
  if (!cookiesEnabled) {
    return fetchMeetingsAndUsers().then(data => {
      meetings = data && data.meetingLinks;
      meetingUsers = data && data.meetingUsers;
    });
  }

  return getMeetings()
    .then(data => {
      meetings = data;

      if (data && data.length > 0) {
        const userIds = data.reduce((p, { meetingsUserIds }) => {
          const ids = meetingsUserIds.reduce(
            (previous, current) => ({
              ...previous,
              [current]: current,
            }),
            {}
          );
          return { ...p, ...ids };
        }, {});
        return getMeetingUsers(Object.keys(userIds));
      }
      return [];
    })
    .then(users => (meetingUsers = users));
}

export default function useMeetingsFetch() {
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const cookiesEnabled = useThirdPartyCookiesEnabled();

  const reload = () => {
    meetings = [];
    setError(null);
    setLoading(true);
  };

  useEffect(() => {
    if (loading && meetings.length === 0) {
      loadMeetings(cookiesEnabled)
        .catch(e => setError(e))
        .finally(() => setLoading(false));
    } else {
      setLoading(false);
    }
  }, [loading, cookiesEnabled]);

  return [{ meetings, meetingUsers }, loading, error, reload];
}
