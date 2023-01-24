import React, { Fragment } from 'react';
import { pluginPath } from '../../constants/leadinConfig';
import UIImage from '../UIComponents/UIImage';

export default function FormGutenbergPreview() {
  return (
    <Fragment>
      <UIImage
        alt="Create a new Hubspot Form"
        src={`${pluginPath}/assets/images/hubspot-form.png`}
      />
    </Fragment>
  );
}
