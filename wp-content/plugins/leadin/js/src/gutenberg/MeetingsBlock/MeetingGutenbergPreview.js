import React, { Fragment } from 'react';
import { pluginPath } from '../../constants/leadinConfig';
import UIImage from '../UIComponents/UIImage';

export default function MeetingGutenbergPreview() {
  return (
    <Fragment>
      <UIImage
        alt="Create a new Hubspot Meeting"
        width="100%"
        src={`${pluginPath}/assets/images/hubspot-meetings.png`}
      />
    </Fragment>
  );
}
