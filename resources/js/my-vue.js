import productsCarousel from './components/ProductsCarousel.vue'
import imageUploader from './components/image-uploader.vue'

import searchPage from './components/searchPage.vue'
// import firebaseChat from './components/firebase-chat.vue'
import pusherChat from './components/pusher-chat.vue'


Vue.config.devtools = false;
Vue.config.debug = false;
Vue.config.silent = true;
const app = new Vue({
    el: '#app',
    mode: 'production',

    components: {productsCarousel, searchPage, pusherChat, imageUploader}
});
