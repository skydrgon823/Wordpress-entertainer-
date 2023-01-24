import React from 'react';
import { registerPlugin } from '@wordpress/plugins';
import { PluginSidebar } from '@wordpress/editPost';
import { PanelBody, Icon } from '@wordpress/components';
import { withSelect } from '@wordpress/data';
import { UISidebarSelectControl } from '../UIComponents/UISidebarSelectControl';
import { i18n } from '../../constants/leadinConfig';
import SidebarSprocketIcon from '../Common/SidebarSprocketIcon';
import styled from 'styled-components';

export function registerHubspotSidebar() {
  const ContentTypeLabelStyle = styled.div`
    white-space: normal;
  `;

  const ContentTypeLabel = (
    <ContentTypeLabelStyle>{i18n.contentTypeSelectLabel}</ContentTypeLabelStyle>
  );

  const LeadinPluginSidebar = ({ postType }) =>
    postType && (
      <PluginSidebar
        name="leadin"
        title="HubSpot"
        icon={
          <Icon
            className="hs-plugin-sidebar-sprocket"
            icon={SidebarSprocketIcon()}
          />
        }
      >
        <PanelBody title={i18n.hubspotAnalytics} initialOpen={true}>
          <UISidebarSelectControl
            metaKey="content-type"
            className="select-content-type"
            label={ContentTypeLabel}
            options={[
              { label: i18n.detectAutomatically, value: '' },
              { label: i18n.blogPost, value: 'blog-post' },
              { label: i18n.knowledgeArticle, value: 'knowledge-article' },
              { label: i18n.landingPage, value: 'landing-page' },
              { label: i18n.listingPage, value: 'listing-page' },
              { label: i18n.standardPage, value: 'standard-page' },
            ]}
          />
        </PanelBody>
      </PluginSidebar>
    );
  const LeadinPluginSidebarWrapper = withSelect(select => {
    const data = select('core/editor');
    return {
      postType: data && data.getCurrentPostType(),
    };
  })(LeadinPluginSidebar);

  registerPlugin('leadin', {
    render: LeadinPluginSidebarWrapper,
  });
}
