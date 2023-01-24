import React from 'react';
import HubspotWrapper from './HubspotWrapper';
import UISpinner from '../UIComponents/UISpinner';
import { pluginPath } from '../../constants/leadinConfig';

export default function LoadingBlock() {
  return (
    <HubspotWrapper pluginPath={pluginPath}>
      <UISpinner size={50} />
    </HubspotWrapper>
  );
}
