import React from 'react';

import { SelectControl } from '@wordpress/components';
import withMetaData from '../../utils/withMetaData';
import { monitorSidebarMetaChange } from '../../api/hubspotPluginApi';

export const UISidebarSelectControl = withMetaData(props => {
  return (
    <SelectControl
      value={props.metaValue}
      onChange={content => {
        props.setMetaValue(content);
        monitorSidebarMetaChange(props.metaKey);
      }}
      {...props}
    />
  );
});
