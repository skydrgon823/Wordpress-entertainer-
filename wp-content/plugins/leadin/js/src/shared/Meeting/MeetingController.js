import React, { Fragment, useContext, useEffect } from 'react';
import LoadingBlock from '../Common/LoadingBlock';
import MeetingSelector from './MeetingSelector';
import MeetingWarning from './MeetingWarning';
import { MeetingsContext } from './MeetingsContext';
import useMeetings, {
  useSelectedMeeting,
  useSelectedMeetingCalendar,
} from './useMeetings';
import HubspotWrapper from '../Common/HubspotWrapper';
import ErrorHandler from '../Common/ErrorHandler';
import { i18n, pluginPath } from '../../constants/leadinConfig';

export default function MeetingController({ handleChange }) {
  const { loading, selectedMeeting, error, reload } = useContext(
    MeetingsContext
  );
  const meetings = useMeetings();
  const selectedMeetingOption = useSelectedMeeting();
  const selectedMeetingCalendar = useSelectedMeetingCalendar();

  useEffect(() => {
    if (!selectedMeeting && meetings.length > 0) {
      handleChange(meetings[0].value);
    }
  }, [meetings, selectedMeeting, handleChange]);

  const handleLocalChange = option => {
    handleChange(option.value);
  };

  return (
    <Fragment>
      {loading ? (
        <LoadingBlock />
      ) : error ? (
        <ErrorHandler
          status={(error && error.status) || error}
          resetErrorState={() => reload()}
          errorInfo={{
            header: i18n.meetingsApiErrorHeader,
            message: i18n.meetingsApiError,
            action: i18n.refreshMeetings,
          }}
        />
      ) : (
        <HubspotWrapper padding="90px 32px 24px" pluginPath={pluginPath}>
          {selectedMeetingCalendar && (
            <MeetingWarning
              status={selectedMeetingCalendar}
              triggerReload={() => reload()}
            />
          )}
          {meetings.length > 1 && (
            <MeetingSelector
              onChange={handleLocalChange}
              options={meetings}
              value={selectedMeetingOption}
            />
          )}
        </HubspotWrapper>
      )}
    </Fragment>
  );
}
