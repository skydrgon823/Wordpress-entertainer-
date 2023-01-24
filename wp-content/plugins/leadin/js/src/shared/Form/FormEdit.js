import React, { Fragment, useEffect } from 'react';
import { portalId, oauth } from '../../constants/leadinConfig';
import UISpacer from '../UIComponents/UISpacer';
import AuthWrapper from '../Auth/AuthWrapper';
import PreviewForm from './PreviewForm';
import FormSelect from './FormSelect';
import { monitorFormPreviewRender } from '../../api/hubspotPluginApi';

export default function FormEdit({
  attributes,
  isSelected,
  setAttributes,
  preview = true,
  origin = 'gutenberg',
}) {
  const { formId, formName } = attributes;

  const formSelected = portalId && formId;

  const handleChange = selectedForm => {
    setAttributes({
      portalId,
      formId: selectedForm.value,
      formName: selectedForm.label,
    });
  };

  useEffect(() => {
    monitorFormPreviewRender(origin);
  }, [origin]);

  return (
    <Fragment>
      {(isSelected || !formSelected) &&
        (!oauth ? (
          <AuthWrapper>
            <FormSelect
              formId={formId}
              formName={formName}
              handleChange={handleChange}
              origin={origin}
            />
          </AuthWrapper>
        ) : (
          <FormSelect
            formId={formId}
            formName={formName}
            handleChange={handleChange}
            origin={origin}
          />
        ))}
      {formSelected && (
        <Fragment>
          {isSelected && <UISpacer />}
          {preview && <PreviewForm portalId={portalId} formId={formId} />}
        </Fragment>
      )}
    </Fragment>
  );
}
