import React, { Fragment, useEffect } from 'react';
import { monitorMeetingPreviewRender } from '../../api/hubspotPluginApi';
import MeetingController from './MeetingController';
import MeetingsContextWrapper from './MeetingsContext';
import PreviewMeeting from './PreviewMeeting';

export default function MeetingEdit({
  attributes: { url },
  isSelected,
  setAttributes,
  preview = true,
  origin = 'gutenberg',
}) {
  const handleChange = newUrl => {
    setAttributes({
      url: newUrl,
    });
  };

  useEffect(() => {
    monitorMeetingPreviewRender(origin);
  }, [origin]);

  return (
    <Fragment>
      {(isSelected || !url) && (
        <MeetingsContextWrapper url={url}>
          <MeetingController handleChange={handleChange} />
        </MeetingsContextWrapper>
      )}
      {preview && url && <PreviewMeeting url={url} />}
    </Fragment>
  );
}
