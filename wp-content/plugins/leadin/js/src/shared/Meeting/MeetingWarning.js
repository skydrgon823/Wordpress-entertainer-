import React from 'react';
import UIAlert from '../UIComponents/UIAlert';
import UIButton from '../UIComponents/UIButton';
import { leadinConnectCalendar } from '../../api/hubspotPluginApi';
import { portalId, hubspotBaseUrl, i18n } from '../../constants/leadinConfig';
import { CURRENT_USER_CALENDAR_MISSING } from './constants';

export default function MeetingWarning({ triggerReload, status }) {
  const isMeetingOwner = status === CURRENT_USER_CALENDAR_MISSING;
  const titleText = isMeetingOwner
    ? i18n.ownCalendarNotConnected
    : i18n.calendarNotConnected;
  const titleMessage = isMeetingOwner
    ? i18n.ownCalendarNotConnectedDesc
    : i18n.calendarNotConnectedDesc;
  return (
    <UIAlert titleText={titleText} titleMessage={titleMessage}>
      {isMeetingOwner && (
        <UIButton
          use="tertiary"
          id="meetings-connect-calendar"
          onClick={() =>
            leadinConnectCalendar({ hubspotBaseUrl, portalId, triggerReload })
          }
        >
          {i18n.connectCalendar}
        </UIButton>
      )}
    </UIAlert>
  );
}
