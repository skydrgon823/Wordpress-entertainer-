import React from 'react';
import { i18n } from '../../constants/leadinConfig';
import ElementorBanner from './ElementorBanner';

export default function ConnectPluginBanner() {
  return (
    <ElementorBanner>
      <b dangerouslySetInnerHTML={{ __html: i18n.connectHubLink }}></b>
    </ElementorBanner>
  );
}
