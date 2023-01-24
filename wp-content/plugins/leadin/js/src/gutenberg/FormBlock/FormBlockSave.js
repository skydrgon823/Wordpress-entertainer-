import React from 'react';
import { RawHTML } from '@wordpress/element';

export default function FormSaveBlock({ attributes }) {
  const { portalId, formId } = attributes;

  if (portalId && formId) {
    return (
      <RawHTML>
        {`[hubspot portal="${portalId}" id="${formId}" type="form"]`}
      </RawHTML>
    );
  }
  return null;
}
