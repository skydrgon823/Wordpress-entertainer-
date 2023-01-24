import React from 'react';
import HubspotWrapper from '../Common/HubspotWrapper';
import { i18n, pluginPath } from '../../constants/leadinConfig';
import AsyncSelect from '../Common/AsyncSelect';

export default function FormSelector({ loadOptions, onChange, value }) {
  return (
    <HubspotWrapper pluginPath={pluginPath}>
      <p data-test-id="leadin-form-select">
        <b>{i18n.selectExistingForm}</b>
      </p>
      <AsyncSelect
        placeholder={i18n.searchForForm}
        value={value}
        loadOptions={loadOptions}
        onChange={onChange}
      />
    </HubspotWrapper>
  );
}
