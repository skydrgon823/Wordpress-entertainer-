import React, { Fragment } from 'react';
import { connectionStatus } from '../../constants/leadinConfig';
import ConnectPluginBanner from '../Common/ConnectPluginBanner';
import ElementorMeetingSelect from './ElementorMeetingSelect';
import MeetingsContextWrapper from '../../shared/Meeting/MeetingsContext';

const ConnectionStatus = {
  Connected: 'Connected',
  NotConnected: 'NotConnected',
};

export default function MeetingControlController(attributes, setValue) {
  return () => {
    const render = () => {
      if (connectionStatus === ConnectionStatus.Connected) {
        return (
          <MeetingsContextWrapper url={attributes.url}>
            <ElementorMeetingSelect
              url={attributes.url}
              setAttributes={setValue}
            />
          </MeetingsContextWrapper>
        );
      } else {
        return <ConnectPluginBanner />;
      }
    };
    return <Fragment>{render()}</Fragment>;
  };
}
