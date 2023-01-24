import ReactDOM from 'react-dom';
import MeetingControlController from './MeetingControlController';
import MeetingWidgetController from './MeetingWidgetController';

export default function registerMeetingsWidget(
  controlContainer,
  widgetContainer,
  setValue
) {
  const attributes = widgetContainer.dataset.attributes
    ? JSON.parse(widgetContainer.dataset.attributes)
    : {};

  this.widgetContainer = widgetContainer;
  this.controlContainer = controlContainer;
  this.setValue = setValue;
  this.attributes = attributes;
}

registerMeetingsWidget.prototype.render = function() {
  ReactDOM.render(
    MeetingWidgetController(this.attributes, this.setValue)(),
    this.widgetContainer
  );

  ReactDOM.render(
    MeetingControlController(this.attributes, this.setValue)(),
    this.controlContainer
  );
};

registerMeetingsWidget.prototype.done = function() {
  ReactDOM.unmountComponentAtNode(this.widgetContainer);
  ReactDOM.unmountComponentAtNode(this.controlContainer);
};
