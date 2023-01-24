import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';

const applyWithSelect = withSelect((select, props) => {
  return {
    metaValue: select('core/editor').getEditedPostAttribute('meta')[
      props.metaKey
    ],
  };
});

const applyWithDispatch = withDispatch((dispatch, props) => {
  return {
    setMetaValue(value) {
      dispatch('core/editor').editPost({ meta: { [props.metaKey]: value } });
    },
  };
});

export default compose(
  applyWithDispatch,
  applyWithSelect
);
