import $ from 'jquery';
import { domElements } from '../constants/selectors';

export default class ThickBoxModal {
  constructor(
    openTriggerSelector,
    inlineContentId,
    windowCssClass,
    contentCssClass
  ) {
    this.openTriggerSelector = openTriggerSelector;
    this.inlineContentId = inlineContentId;
    this.windowCssClass = windowCssClass;
    this.contentCssClass = contentCssClass;

    $(openTriggerSelector).click(this.init.bind(this));
  }

  close() {
    window.tb_remove();
  }

  init(e) {
    window.tb_show(
      '',
      `#TB_inline?inlineId=${this.inlineContentId}&modal=true`
    );
    // thickbox doesn't respect the width and height url parameters https://core.trac.wordpress.org/ticket/17249
    // We override thickboxes css with !important in the css
    $(domElements.thickboxModalWindow).addClass(this.windowCssClass);

    // have to modify the css of the thickbox content container as well
    $(domElements.thickboxModalContent).addClass(this.contentCssClass);

    // we unbind previous handlers because a thickbox modal is a single global object.
    // Everytime it is re-opened, it still has old handlers bound
    $(domElements.thickboxModalClose)
      .off('click')
      .click(this.close);

    e.preventDefault();
  }
}
