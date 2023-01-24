import $ from 'jquery';
import { domElements } from '../constants/selectors';

export function setLeadinUnAuthedNavigation() {
  $(domElements.subMenu).remove();
}

export function setLeadinCookiesEnabledNavigation() {
  $(domElements.subMenuLinks).addClass('visible');
}

export function setLeadinCookiesDisabledNavigation() {
  $(domElements.subMenuLinks).removeClass('visible');
}
