import React, { Fragment } from 'react';
import useAuth from './useAuth';
import LoadingBlock from '../Common/LoadingBlock';
import ErrorHandler from '../Common/ErrorHandler';

export default function AuthWrapper({ children }) {
  const { auth, loading } = useAuth();

  return loading ? (
    <LoadingBlock />
  ) : auth ? (
    <Fragment>{children}</Fragment>
  ) : (
    <ErrorHandler status={401} />
  );
}
