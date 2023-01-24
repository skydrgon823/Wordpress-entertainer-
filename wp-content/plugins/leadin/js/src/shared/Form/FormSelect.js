import React, { useState } from 'react';
import debounce from 'lodash/debounce';
import {
  monitorFormCreatedFromTemplate,
  monitorFormCreationFailed,
} from '../../api/hubspotPluginApi';
import {
  createForm,
  fetchForms as searchFormsOAuth,
} from '../../api/hubspotApiClient';
import useForm from './useForm';
import FormSelector from './FormSelector';
import LoadingBlock from '../Common/LoadingBlock';
import { i18n } from '../../constants/leadinConfig';
import {
  DEFAULT_OPTIONS,
  getFormDef,
  isDefaultForm,
} from '../../constants/defaultFormOptions';
import ErrorHandler from '../Common/ErrorHandler';

const mapForm = form => ({
  label: form.name,
  value: form.guid,
});

export default function FormSelect({
  formId,
  formName,
  handleChange,
  origin = 'gutenberg',
}) {
  const { form, loading, setLoading } = useForm(formId, formName);
  const [searchformError, setSearchFormError] = useState(null);

  const loadOptions = debounce(
    (search, callback) => {
      searchFormsOAuth(search)
        .then(forms => callback([...forms.map(mapForm), DEFAULT_OPTIONS]))
        .catch(error => setSearchFormError(error));
    },
    300,
    { trailing: true }
  );

  const defaultOptions = true;
  const value = form ? mapForm(form) : null;

  const handleLocalChange = option => {
    if (isDefaultForm(option.value)) {
      setLoading(true);
      monitorFormCreatedFromTemplate(option.value, origin);
      createForm(getFormDef(option.value))
        .then(({ guid, name }) => handleChange({ value: guid, label: name }))
        .catch(error => {
          setSearchFormError(error);
          monitorFormCreationFailed({ ...error, type: option.value }, origin);
        })
        .finally(() => setLoading(false));
    } else {
      handleChange(option);
    }
  };

  const formApiError = searchformError;

  return loading ? (
    <LoadingBlock />
  ) : !formApiError ? (
    <FormSelector
      defaultOptions={defaultOptions}
      loadOptions={loadOptions}
      onChange={option => handleLocalChange(option)}
      value={value}
    />
  ) : (
    <ErrorHandler
      status={formApiError.status}
      resetErrorState={() => setSearchFormError(null)}
      errorInfo={{
        header: i18n.formApiErrorHeader,
        message: i18n.formApiError,
        action: i18n.refreshForms,
      }}
    />
  );
}
