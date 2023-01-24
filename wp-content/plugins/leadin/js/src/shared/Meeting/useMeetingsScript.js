import $ from 'jquery';

import { useState, useEffect } from 'react';
import { meetingsScript } from '../../constants/leadinConfig';
import Raven from '../../lib/Raven';

let promise;

function loadMeetingsScript() {
  if (!promise) {
    promise = new Promise((resolve, reject) =>
      $.getScript(meetingsScript)
        .done(resolve)
        .fail(reject)
    );
  }
  return promise;
}

export default function useMeetingsScript() {
  const [ready, setReady] = useState(false);

  useEffect(() => {
    loadMeetingsScript()
      .then(() => setReady(true))
      .catch(error => Raven.captureException(error));
  }, []);

  return ready;
}
