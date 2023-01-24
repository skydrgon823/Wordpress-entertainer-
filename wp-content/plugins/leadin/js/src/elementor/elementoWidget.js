export default function elementorWidget(
  elementor,
  options,
  callback,
  done = () => {}
) {
  return elementor.modules.controls.BaseData.extend({
    onReady() {
      const self = this;
      const controlContainer = this.ui.contentEditable.prevObject[0].querySelector(
        options.controlSelector
      );
      let widgetContainer = this.options.element.$el[0].querySelector(
        options.containerSelector
      );
      if (widgetContainer) {
        callback(controlContainer, widgetContainer, args =>
          self.setValue(args)
        );
      } else {
        // eslint-disable-next-line no-undef
        elementorFrontend.hooks.addAction(
          `frontend/element_ready/${options.widgetName}.default`,
          element => {
            widgetContainer = element[0].querySelector(
              options.containerSelector
            );
            callback(controlContainer, widgetContainer, args =>
              self.setValue(args)
            );
          }
        );
      }
    },
    saveValue(props) {
      this.setValue(props);
    },
    onBeforeDestroy() {
      // eslint-disable-next-line no-undef
      elementorFrontend.hooks.removeAction(
        `frontend/element_ready/${options.widgetName}.default`
      );
      done();
    },
  });
}
