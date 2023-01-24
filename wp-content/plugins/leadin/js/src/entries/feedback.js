import $ from 'jquery';
import Raven from '../lib/Raven';
import { domElements } from '../constants/selectors';
import ThickBoxModal from '../feedback/ThickBoxModal';
import { submitFeedbackForm } from '../feedback/feedbackFormApi';
import { initAppOnReady } from '../utils/appUtils';

function deactivatePlugin() {
  window.location.href = $(domElements.deactivatePluginButton).attr('href');
}

function setLoadingState() {
  $(domElements.deactivateFeedbackSubmit).addClass('loading');
}

function submitAndDeactivate(e) {
  e.preventDefault();
  setLoadingState();

  submitFeedbackForm(domElements.deactivateFeedbackForm)
    .then(deactivatePlugin)
    .catch(err => {
      Raven.captureException(err);
      deactivatePlugin();
    });
}

function init() {
  // eslint-disable-next-line no-new
  new ThickBoxModal(
    domElements.deactivatePluginButton,
    'leadin-feedback-container',
    'leadin-feedback-window',
    'leadin-feedback-content'
  );

  $(domElements.deactivateFeedbackForm)
    .off('submit')
    .submit(submitAndDeactivate);
  $(domElements.deactivateFeedbackSkip)
    .off('click')
    .click(deactivatePlugin);
}

initAppOnReady(init);
