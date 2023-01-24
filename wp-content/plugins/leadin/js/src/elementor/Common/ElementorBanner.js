import React from 'react';

export default function ElementorBanner({ type = 'warning', children }) {
  return (
    <div className="elementor-control-content">
      <div
        className={`elementor-control-raw-html elementor-panel-alert elementor-panel-alert-${type}`}
      >
        {children}
      </div>
    </div>
  );
}
