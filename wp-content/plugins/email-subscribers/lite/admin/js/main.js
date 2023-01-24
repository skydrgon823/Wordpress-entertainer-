import '../css/style.css';
import GalleryItemsPage from './src/views/GalleryItemsPage';

if ( 'undefined' !== typeof wp.i18n ) {
    global.__ = wp.i18n.__;
} else {
    // Create a dummy fallback function incase i18n library isn't available.
    global.__ = ( text, textDomain ) => {
        return text;
    }
}

const campaignGalleryItemsWrapper = document.querySelector('#ig-es-campaign-gallery-items-wrapper');

let campaignType = location.search.split('campaign-type=')[1];
let campaignId   = location.search.split('campaign-id=')[1];

if ( 'undefined' === typeof campaignType ) {
    campaignType = ig_es_main_js_data.post_notification_campaign_type;
}

if ( 'undefined' === typeof campaignId ) {
    campaignId = 0;
}

m.mount(
    campaignGalleryItemsWrapper, 
    {
        view: () => {
            return <GalleryItemsPage campaignId={campaignId} campaignType={campaignType}/>
        }
    }
);




