import registerFormBlock from '../gutenberg/FormBlock/registerFormBlock';
import { registerHubspotSidebar } from '../gutenberg/Sidebar/contentType';
import registerMeetingBlock from '../gutenberg/MeetingsBlock/registerMeetingBlock';
import {
  initBackgroundApp,
  initMonitorGutenberBlockPreview,
} from '../utils/backgroundAppUtils';

initBackgroundApp([
  registerFormBlock,
  registerMeetingBlock,
  registerHubspotSidebar,
]);
initMonitorGutenberBlockPreview();
