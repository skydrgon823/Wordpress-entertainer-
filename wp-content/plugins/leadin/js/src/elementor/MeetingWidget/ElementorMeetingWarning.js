import React, { Fragment } from 'react';
import { i18n, portalId, hubspotBaseUrl } from '../../constants/leadinConfig';
import { leadinConnectCalendar } from '../../api/hubspotPluginApi';
import { CURRENT_USER_CALENDAR_MISSING } from '../../shared/Meeting/constants';
import ElementorButton from '../Common/ElementorButton';
import ElementorBanner from '../Common/ElementorBanner';
import { styled } from '@linaria/react';

const Container = styled.div`
  padding-bottom: 8px;
`;

export default function MeetingWarning({ triggerReload, status }) {
  const isMeetingOwner = status === CURRENT_USER_CALENDAR_MISSING;
  const titleText = isMeetingOwner
    ? i18n.ownCalendarNotConnected
    : i18n.calendarNotConnected;
  const titleMessage = isMeetingOwner
    ? i18n.ownCalendarNotConnectedDesc
    : i18n.calendarNotConnectedDesc;
  return (
    <Fragment>
      <Container>
        <ElementorBanner type="warning">
          <b>{titleText}</b>
          <br />
          {titleMessage}
        </ElementorBanner>
      </Container>
      {isMeetingOwner && (
        <ElementorButton
          id="meetings-connect-calendar"
          onClick={() =>
            leadinConnectCalendar({ hubspotBaseUrl, portalId, triggerReload })
          }
        >
          {i18n.connectCalendar}
        </ElementorButton>
      )}
    </Fragment>
  );
}
