import productsCarousel from './components/ProductsCarousel.vue'
import imageUploader from './components/image-uploader.vue'

import searchPage from './components/searchPage.vue'
// import firebaseChat from './components/firebase-chat.vue'
import pusherChat from './components/pusher-chat.vue'
import productsPanel from './components/ProductsPanel.vue'
import productCreate from './components/ProductsCreate.vue'
import orderAdmin from './components/OrderAdmin.vue'
import OrderUser from './components/OrderUser.vue'
import FactorMaker from './components/FactorMaker.vue'


Vue.config.devtools = false;
Vue.config.debug = false;
Vue.config.silent = true;
const app = new Vue({
    el: '#app',
    mode: 'production',

    components: {
        productsCarousel,
        searchPage,
        pusherChat,
        imageUploader,
        productsPanel,
        productCreate,
        orderAdmin,
        OrderUser,
        FactorMaker,

    }
});
