import $ from 'jquery';
import { initBackgroundApp } from '../utils/backgroundAppUtils';
import {
  monitorReviewBannerRendered,
  monitorReviewBannerLinkClicked,
  monitorReviewBannerDismissed,
} from '../api/hubspotPluginApi';
import { domElements } from '../constants/selectors';

/**
 * Adds some methods to window when review banner is
 * displayed to monitor events
 */
export function initMonitorReviewBanner() {
  if (!window.reviewBannerTracking) {
    window.reviewBannerTracking = {
      monitorReviewBannerRendered,
      monitorReviewBannerLinkClicked,
      monitorReviewBannerDismissed,
    };
  }

  function reviewLinkClickHandler() {
    const reviewBanner = document.getElementById('leadin-review-banner');

    if (reviewBanner) {
      reviewBanner.classList.add('leadin-review-banner--hide');
      window.reviewBannerTracking.monitorReviewBannerLinkClicked();
    }
  }

  function dismissBtnClickHandler() {
    window.reviewBannerTracking.monitorReviewBannerDismissed();
  }

  $(domElements.reviewBannerLeaveReviewLink)
    .off('click')
    .click(reviewLinkClickHandler);

  $(domElements.reviewBannerDismissButton)
    .off('click')
    .click(dismissBtnClickHandler);

  $('#leadin-iframe').ready(() => {
    monitorReviewBannerRendered();
  });
}

initBackgroundApp(initMonitorReviewBanner);
