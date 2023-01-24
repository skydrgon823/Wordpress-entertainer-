import React from 'react';
import { registerBlockType } from '@wordpress/blocks';
import SprocketIcon from '../Common/SprocketIcon';
import FormBlockSave from './FormBlockSave';
import { connectionStatus, i18n } from '../../constants/leadinConfig';
import FormGutenbergPreview from './FormGutenbergPreview';
import ErrorHandler from '../../shared/Common/ErrorHandler';
import FormEdit from '../../shared/Form/FormEdit';

const ConnectionStatus = {
  Connected: 'Connected',
  NotConnected: 'NotConnected',
};

export default function registerFormBlock() {
  const editComponent = props => {
    if (props.attributes.preview) {
      return <FormGutenbergPreview />;
    } else if (connectionStatus === ConnectionStatus.Connected) {
      return <FormEdit {...props} />;
    } else {
      return <ErrorHandler status={401} />;
    }
  };

  registerBlockType('leadin/hubspot-form-block', {
    title: i18n.formBlockTitle,
    description: i18n.formBlockDescription,
    icon: SprocketIcon,
    category: 'leadin-blocks',
    attributes: {
      portalId: {
        type: 'string',
      },
      formId: {
        type: 'string',
      },
      formName: {
        type: 'string',
      },
      preview: {
        type: 'boolean',
        default: false,
      },
    },
    example: {
      attributes: {
        preview: true,
      },
    },
    edit: editComponent,
    save: props => <FormBlockSave {...props} />,
  });
}
