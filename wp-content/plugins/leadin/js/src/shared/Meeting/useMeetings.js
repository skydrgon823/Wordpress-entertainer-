import { useContext } from 'react';
import { i18n } from '../../constants/leadinConfig';
import {
  CURRENT_USER_CALENDAR_MISSING,
  OTHER_USER_CALENDAR_MISSING,
} from './constants';
import { MeetingsContext } from './MeetingsContext';

function getDefaultMeetingName(meeting, currentUser, meetingUsers) {
  const [meetingOwnerId] = meeting.meetingsUserIds;
  let result = i18n.defaultMeeting;
  if (
    currentUser &&
    meetingOwnerId !== currentUser.id &&
    meetingUsers[meetingOwnerId]
  ) {
    const user = meetingUsers[meetingOwnerId];
    result += ` (${user.userProfile.fullName})`;
  }
  return result;
}

function hasCalendarObject(user) {
  return (
    user &&
    user.meetingsUserBlob &&
    user.meetingsUserBlob.calendarSettings &&
    user.meetingsUserBlob.calendarSettings.email
  );
}

export default function useMeetings() {
  const { meetings, meetingUsers, currentUser } = useContext(MeetingsContext);

  return meetings.map(meet => ({
    label: meet.name || getDefaultMeetingName(meet, currentUser, meetingUsers),
    value: meet.link,
  }));
}

export function useSelectedMeeting() {
  const { selectedMeeting } = useContext(MeetingsContext);
  const meetings = useMeetings();
  const option = meetings.find(({ value }) => value === selectedMeeting);

  return option;
}

export function useSelectedMeetingCalendar() {
  const { selectedMeeting, meetings, meetingUsers, currentUser } = useContext(
    MeetingsContext
  );

  const meeting = meetings.find(meet => meet.link === selectedMeeting);
  if (!meeting) {
    return null;
  } else {
    const { meetingsUserIds } = meeting;
    if (
      currentUser &&
      meetingsUserIds.includes(currentUser.id) &&
      !hasCalendarObject(currentUser)
    ) {
      return CURRENT_USER_CALENDAR_MISSING;
    } else if (
      meetingsUserIds
        .map(id => meetingUsers[id])
        .some(user => !hasCalendarObject(user))
    ) {
      return OTHER_USER_CALENDAR_MISSING;
    } else {
      return null;
    }
  }
}
