import $ from 'jquery';
import {
  deleteSessionStorage,
  getSessionStorage,
  setSessionStorage,
} from '../utils/sessionStorage';
import {
  setLeadinCookiesDisabledNavigation,
  setLeadinCookiesEnabledNavigation,
} from '../utils/sideNav';
import { checkThirdPartyCookies } from '../utils/thirdPartyCookies';

const THIRD_PARTY_COOKIES = 'leadin_third_party_cookies';

$(() => {
  if (getSessionStorage(THIRD_PARTY_COOKIES)) {
    setLeadinCookiesEnabledNavigation();
  }

  checkThirdPartyCookies().then(enabled => {
    if (enabled) {
      setSessionStorage(THIRD_PARTY_COOKIES, true);
      setLeadinCookiesEnabledNavigation();
    } else {
      deleteSessionStorage(THIRD_PARTY_COOKIES);
      setLeadinCookiesDisabledNavigation();
    }
  });
});
