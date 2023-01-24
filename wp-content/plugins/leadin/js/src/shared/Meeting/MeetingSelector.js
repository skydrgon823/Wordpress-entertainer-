import React, { Fragment } from 'react';
import AsyncSelect from '../Common/AsyncSelect';
import { i18n } from '../../constants/leadinConfig';
import UISpacer from '../UIComponents/UISpacer';

export default function MeetingSelector({ options, onChange, value }) {
  const optionsWrapper = [
    {
      label: i18n.meetingName,
      options,
    },
  ];

  return (
    <Fragment>
      <UISpacer />
      <p data-test-id="leadin-meeting-select">
        <b>{i18n.selectExistingMeeting}</b>
      </p>
      <AsyncSelect
        defaultOptions={optionsWrapper}
        onChange={onChange}
        placeholder={i18n.selectMeeting}
        value={value}
      />
    </Fragment>
  );
}
