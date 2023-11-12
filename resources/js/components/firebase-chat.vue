<template>
    <div class="container">
        <div class="position-absolute bottom-0 right-0">
            <div class="rounded-circle bg-red">
                <i class="fa fa-support text-white" aria-hidden="true"></i>
            </div>
        </div>
    </div>
</template>

<script>
    import {initializeApp} from "firebase";
    import "firebase/firebase-messaging";

    export default {
        mounted() {
            this.initFireBase()
        },
        props: [],

//        components: {paginator, refEditor},
        data() {
            return {}
        },
        methods: {
            log(str) {
                console.log(str);
            },
            initFireBase() {


// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
                const firebaseConfig = {
                    apiKey: "AIzaSyCo0x-JZBYx7Aec4eKd-VV_cot5M5_bZyI",
                    authDomain: "vartashop-chat.firebaseapp.com",
                    projectId: "vartashop-chat",
                    storageBucket: "vartashop-chat.appspot.com",
                    messagingSenderId: "564500431273",
                    appId: "1:564500431273:web:0c3b73db12ef7306d7c4b8"
                };

// Initialize Firebase
                const app = initializeApp(firebaseConfig);

                // Retrieve Firebase Messaging object.
//                const messaging = firebase.messaging();
                console.log(app);
//                messaging.setBackgroundMessageHandler(function (payload) {
//                    console.log('[firebase-messaging-sw.js] Received background message ', payload);
//                    // Customize notification here
//                    const {title, body} = payload.notification;
//                    const notificationOptions = {
//                        body,
//                    };
//                    // Add the public key generated from the console here.
//                    messaging.usePublicVapidKey("BIeUTUm9zJY5l12vIo_c8nQnPgUDtxrYldqFi4St8i-fH3X2lCOAoqsi7FGh0HhPMLr11rRhZDwD2GKXJCF2ycA");
//
//                    this.retreiveToken();
//                    messaging.onTokenRefresh(() => {
//                        this.retreiveToken();
//
//
//                    });
//
//                    messaging.onMessage((payload) => {
//                        console.log('Message received');
//                        console.log(payload);
//
////                    location.reload();
//                    });
//                })
            },
            sendTokenToServer(fcm_token) {
                const user_id = '{{auth()->user()->id}}';
                //console.log($user_id);
                axios.post('/api/save-token', {
                    fcm_token, user_id
                })
                    .then(res => {
                        console.log(res);
                    })

            }
            ,
            retreiveToken() {
                messaging.getToken().then((currentToken) => {
                    if (currentToken) {
                        sendTokenToServer(currentToken);
                        // updateUIForPushEnabled(currentToken);
                    } else {
                        // Show permission request.
                        //console.log('No Instance ID token available. Request permission to generate one.');
                        // Show permission UI.
                        //updateUIForPushPermissionRequired();
                        //etTokenSentToServer(false);
                        alert('You should allow notification!');
                    }
                }).catch((err) => {
                    console.log(err.message);
                    // showToken('Error retrieving Instance ID token. ', err);
                    // setTokenSentToServer(false);
                });
            }
        },
    }
</script>
