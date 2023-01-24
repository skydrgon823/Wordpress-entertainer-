import React, { useEffect, useRef } from 'react';
import UIOverlay from '../UIComponents/UIOverlay';
import { formsScriptPayload, hublet } from '../../constants/leadinConfig';
import useFormScript from './useFormsScript';

export default function PreviewForm({ portalId, formId }) {
  const inputEl = useRef();
  const ready = useFormScript();

  useEffect(() => {
    if (!ready) {
      return;
    }

    inputEl.current.innerHTML = '';
    const embedScript = document.createElement('script');
    embedScript.innerHTML = `hbspt.forms.create({ portalId: '${portalId}', formId: '${formId}', region: '${hublet}', ${formsScriptPayload} });`;
    inputEl.current.appendChild(embedScript);
  }, [formId, portalId, ready]);

  return <UIOverlay ref={inputEl} />;
}
