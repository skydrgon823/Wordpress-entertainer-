import React from 'react';

import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { TextControl } from '@wordpress/components';

export const UISidebarTextControl = compose(
  withDispatch((dispatch, props) => {
    return {
      setMetaValue(value) {
        dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } });
      },
    };
  }),
  withSelect((select, props) => {
    return {
      metaValue: select('core/editor').getEditedPostAttribute('meta')[
        props.metaKey
      ],
    };
  })
)(props => {
  return (
    <TextControl
      type="text"
      label={props.label}
      value={props.metaValue}
      onChange={content => {
        props.setMetaValue(content);
      }}
    />
  );
});
