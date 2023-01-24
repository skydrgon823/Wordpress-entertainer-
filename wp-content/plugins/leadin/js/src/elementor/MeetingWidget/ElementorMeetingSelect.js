import React, { useContext, Fragment, useState } from 'react';
import { i18n } from '../../constants/leadinConfig';
import ElementorBanner from '../Common/ElementorBanner';
import UISpinner from '../../shared/UIComponents/UISpinner';
import ElementorMeetingWarning from './ElementorMeetingWarning';
import { MeetingsContext } from '../../shared/Meeting/MeetingsContext';
import useMeetings, {
  useSelectedMeetingCalendar,
} from '../../shared/Meeting/useMeetings';

export default function ElementorMeetingSelect({ url, setAttributes }) {
  const { loading, error, reload } = useContext(MeetingsContext);
  const meetings = useMeetings();
  const selectedMeetingCalendar = useSelectedMeetingCalendar();
  const [localUrl, setLocalUrl] = useState(url);
  return (
    <Fragment>
      {loading ? (
        <div>
          <UISpinner />
        </div>
      ) : error ? (
        <ElementorBanner type="danger">{i18n.meetingsApiError}</ElementorBanner>
      ) : (
        <Fragment>
          {selectedMeetingCalendar && (
            <ElementorMeetingWarning
              status={selectedMeetingCalendar}
              triggerReload={() => reload()}
            />
          )}
          {meetings.length > 1 && (
            <select
              value={localUrl}
              onChange={event => {
                const newUrl = event.target.value;
                setLocalUrl(newUrl);
                setAttributes({
                  url: newUrl,
                });
              }}
            >
              <option value="" disabled={true} selected={true}>
                {i18n.selectMeeting}
              </option>
              {meetings.map(item => (
                <option key={item.value} value={item.value}>
                  {item.label}
                </option>
              ))}
            </select>
          )}
        </Fragment>
      )}
    </Fragment>
  );
}
