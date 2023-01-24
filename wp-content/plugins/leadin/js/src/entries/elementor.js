import elementorWidget from '../elementor/elementoWidget';
import registerFormWidget from '../elementor/FormWidget/registerFormWidget';
import { initBackgroundApp } from '../utils/backgroundAppUtils';
import registerMeetingsWidget from '../elementor/MeetingWidget/registerMeetingWidget';
/* eslint-disable no-undef */

window.addEventListener('elementor/init', () => {
  initBackgroundApp(() => {
    let FormWidget;
    let MeetingsWidget;

    const leadinSelectFormItemView = elementorWidget(
      elementor,
      {
        widgetName: 'hubspot-form',
        controlSelector: '.elementor-hbspt-form-selector',
        containerSelector: '.hubspot-form-edit-mode',
      },
      (controlContainer, widgetContainer, setValue) => {
        FormWidget = new registerFormWidget(
          controlContainer,
          widgetContainer,
          setValue
        );
        FormWidget.render();
      },
      () => {
        FormWidget.done();
      }
    );

    const leadinSelectMeetingItemView = elementorWidget(
      elementor,
      {
        widgetName: 'hubspot-meeting',
        controlSelector: '.elementor-hbspt-meeting-selector',
        containerSelector: '.hubspot-meeting-edit-mode',
      },
      (controlContainer, widgetContainer, setValue) => {
        MeetingsWidget = new registerMeetingsWidget(
          controlContainer,
          widgetContainer,
          setValue
        );
        MeetingsWidget.render();
      },
      () => {
        MeetingsWidget.done();
      }
    );

    elementor.addControlView('leadinformselect', leadinSelectFormItemView);
    elementor.addControlView(
      'leadinmeetingselect',
      leadinSelectMeetingItemView
    );
  });
});
