import ReactDOM from 'react-dom';
import FormControlController from './FormControlController';
import FormWidgetController from './FormWidgetController';

export default function registerFormWidget(
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

registerFormWidget.prototype.render = function() {
  ReactDOM.render(
    FormWidgetController(this.attributes, this.setValue)(),
    this.widgetContainer
  );

  ReactDOM.render(
    FormControlController(this.attributes, this.setValue)(),
    this.controlContainer
  );
};

registerFormWidget.prototype.done = function() {
  ReactDOM.unmountComponentAtNode(this.widgetContainer);
  ReactDOM.unmountComponentAtNode(this.controlContainer);
};
