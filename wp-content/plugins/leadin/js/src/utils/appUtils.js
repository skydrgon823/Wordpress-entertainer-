import $ from 'jquery';
import Raven, { configureRaven } from '../lib/Raven';

export function initApp(initFn) {
  configureRaven();
  Raven.context(initFn);
}

export function initAppOnReady(initFn) {
  function main() {
    $(document).ready(initFn);
  }
  initApp(main);
}
