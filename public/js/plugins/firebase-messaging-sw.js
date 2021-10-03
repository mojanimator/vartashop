// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.

importScripts('https://www.gstatic.com/firebasejs/9.1.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/9.1.0/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
firebase.initializeApp({


    databaseURL: "https://xxxxxxxxxxxxxxxxxx-project-default-rtdb.firebaseio.com",
    apiKey: "AIzaSyCo0x-JZBYx7Aec4eKd-VV_cot5M5_bZyI",
    authDomain: "vartashop-chat.firebaseapp.com",
    projectId: "vartashop-chat",
    storageBucket: "vartashop-chat.appspot.com",
    messagingSenderId: "564500431273",
    appId: "1:564500431273:web:0c3b73db12ef7306d7c4b8"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

console.log('FIREBASE MESSAGING SW ....');
console.log(messaging);

messaging.setBackgroundMessageHandler(function (payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});