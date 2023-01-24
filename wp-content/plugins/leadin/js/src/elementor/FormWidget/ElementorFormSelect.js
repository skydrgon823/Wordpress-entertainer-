import React, { useState, useEffect, Fragment } from 'react';
import { i18n, portalId } from '../../constants/leadinConfig';
import ElementorBanner from '../Common/ElementorBanner';
import { fetchForms } from '../../api/hubspotApiClient';
import UISpinner from '../../shared/UIComponents/UISpinner';

const mapForm = form => ({
  label: form.name,
  value: form.guid,
});

export default function ElementorFormSelect({ formId, setAttributes }) {
  const [loading, setLoading] = useState(false);
  const [hasError, setError] = useState(null);
  const [forms, setForms] = useState([]);

  useEffect(() => {
    setLoading(true);
    fetchForms('', 0, 100)
      .then(data => {
        setForms(data.map(mapForm));
      })
      .catch(error => setError(error))
      .finally(() => setLoading(false));
  }, []);

  return (
    <Fragment>
      {loading ? (
        <div>
          <UISpinner />
        </div>
      ) : hasError ? (
        <ElementorBanner type="danger">{i18n.formApiError}</ElementorBanner>
      ) : (
        <select
          value={formId}
          onChange={event => {
            const selectedForm = forms.find(
              form => form.value === event.target.value
            );
            if (selectedForm) {
              setAttributes({
                portalId,
                formId: selectedForm.value,
                formName: selectedForm.label,
              });
            }
          }}
        >
          <option value="" disabled={true} selected={true}>
            {i18n.searchForForm}
          </option>
          {forms.map(form => (
            <option key={form.value} value={form.value}>
              {form.label}
            </option>
          ))}
        </select>
      )}
    </Fragment>
  );
}
