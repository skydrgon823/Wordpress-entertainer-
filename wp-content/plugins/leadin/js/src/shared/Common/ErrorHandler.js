import React from 'react';
import UIButton from '../UIComponents/UIButton';
import UIContainer from '../UIComponents/UIContainer';
import HubspotWrapper from './HubspotWrapper';
import { i18n, adminUrl, redirectNonce } from '../../constants/leadinConfig';
import { pluginPath } from '../../constants/leadinConfig';

function redirectToPlugin() {
  window.location.href = `${adminUrl}admin.php?page=leadin&leadin_expired=${redirectNonce}`;
}

export default function ErrorHandler({ status, resetErrorState, errorInfo }) {
  const isUnauthorized = status === 401 || status === 403;
  const errorHeader = isUnauthorized
    ? i18n.unauthorizedHeader
    : errorInfo.header;
  const errorMessage = isUnauthorized
    ? i18n.unauthorizedMessage
    : errorInfo.message;

  return (
    <HubspotWrapper pluginPath={pluginPath}>
      <UIContainer textAlign="center">
        <h4>{errorHeader}</h4>
        <p>
          <b>{errorMessage}</b>
        </p>
        {isUnauthorized ? (
          <UIButton data-test-id="authorize-button" onClick={redirectToPlugin}>
            {i18n.goToPlugin}
          </UIButton>
        ) : (
          <UIButton data-test-id="retry-button" onClick={resetErrorState}>
            {errorInfo.action}
          </UIButton>
        )}
      </UIContainer>
    </HubspotWrapper>
  );
}
