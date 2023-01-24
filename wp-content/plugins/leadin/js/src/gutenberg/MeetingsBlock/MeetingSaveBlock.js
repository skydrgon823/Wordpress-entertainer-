import React from 'react';
import { RawHTML } from '@wordpress/element';

export default function MeetingSaveBlock({ attributes }) {
  const { url } = attributes;

  if (url) {
    return <RawHTML>{`[hubspot url="${url}" type="meeting"]`}</RawHTML>;
  }
  return null;
}
