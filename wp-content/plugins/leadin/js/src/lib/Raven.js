import Raven from 'raven-js';
import {
  hubspotBaseUrl,
  phpVersion,
  wpVersion,
  leadinPluginVersion,
  portalId,
  plugins,
} from '../constants/leadinConfig';

export function configureRaven() {
  if (hubspotBaseUrl.indexOf('app.hubspot.com') === -1) {
    return;
  }

  Raven.config(
    'https://e9b8f382cdd130c0d415cd977d2be56f@exceptions.hubspot.com/1',
    {
      instrument: {
        tryCatch: false,
      },
      collectWindowErrors: false,
      release: leadinPluginVersion,
    }
  ).install();

  Raven.setTagsContext({
    v: leadinPluginVersion,
    php: phpVersion,
    wordpress: wpVersion,
  });

  Raven.setUserContext({
    hub: portalId,
    plugins: Object.keys(plugins)
      .map(name => `${name}#${plugins[name].Version}`)
      .join(','),
  });
}

export default Raven;
