import React, { Fragment } from 'react';
import { connectionStatus } from '../../constants/leadinConfig';
import ConnectPluginBanner from '../Common/ConnectPluginBanner';
import ElementorFormSelect from './ElementorFormSelect';

const ConnectionStatus = {
  Connected: 'Connected',
  NotConnected: 'NotConnected',
};

export default function FormControlController(attributes, setValue) {
  return () => {
    const render = () => {
      if (connectionStatus === ConnectionStatus.Connected) {
        return (
          <ElementorFormSelect
            formId={attributes.formId}
            setAttributes={setValue}
          />
        );
      } else {
        return <ConnectPluginBanner />;
      }
    };
    return <Fragment>{render()}</Fragment>;
  };
}
