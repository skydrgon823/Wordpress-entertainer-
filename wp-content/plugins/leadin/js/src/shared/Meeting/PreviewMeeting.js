import React, { Fragment, useEffect, useRef } from 'react';
import UIOverlay from '../UIComponents/UIOverlay';
import useMeetingsScript from './useMeetingsScript';

export default function PreviewForm({ url }) {
  const ready = useMeetingsScript();
  const inputEl = useRef();

  useEffect(() => {
    if (!ready) {
      return;
    }

    inputEl.current.innerHTML = '';
    const container = document.createElement('div');
    container.dataset.src = `${url}?embed=true`;
    container.classList.add('meetings-iframe-container');
    inputEl.current.appendChild(container);
    const embedScript = document.createElement('script');
    embedScript.innerHTML =
      'hbspt.meetings.create(".meetings-iframe-container");';
    inputEl.current.appendChild(embedScript);
  }, [url, ready]);

  return <Fragment>{url && <UIOverlay ref={inputEl}></UIOverlay>}</Fragment>;
}
