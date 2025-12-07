import { initializeApp } from 'firebase/app';
import { getMessaging, getToken, onMessage } from 'firebase/messaging';

const firebaseConfig = {
    apiKey: window.Firebase?.apiKey || '',
    authDomain: window.Firebase?.authDomain || '',
    projectId: window.Firebase?.projectId || '',
    storageBucket: window.Firebase?.storageBucket || '',
    messagingSenderId: window.Firebase?.messagingSenderId || '',
    appId: window.Firebase?.appId || '',
};

let messaging = null;

export function initFirebase() {
    if (!firebaseConfig.apiKey) {
        console.log('Firebase not configured');
        return null;
    }
    
    try {
        const app = initializeApp(firebaseConfig);
        messaging = getMessaging(app);
        return messaging;
    } catch (error) {
        console.error('Firebase initialization error:', error);
        return null;
    }
}

export async function requestNotificationPermission() {
    if (!messaging) {
        messaging = initFirebase();
        if (!messaging) return null;
    }
    
    try {
        const permission = await Notification.requestPermission();
        if (permission === 'granted') {
            const token = await getToken(messaging, {
                vapidKey: window.Firebase?.vapidKey || ''
            });
            return token;
        }
        return null;
    } catch (error) {
        console.error('Error getting notification permission:', error);
        return null;
    }
}

export function onFirebaseMessage(callback) {
    if (!messaging) {
        messaging = initFirebase();
        if (!messaging) return;
    }
    
    onMessage(messaging, (payload) => {
        console.log('Firebase message received:', payload);
        callback(payload);
    });
}

if (window.Firebase && window.Firebase.enabled) {
    document.addEventListener('DOMContentLoaded', () => {
        initFirebase();
        
        onFirebaseMessage((payload) => {
            const { title, body } = payload.notification || {};
            if (window.erpShowNotification) {
                window.erpShowNotification(body || title, 'info', true);
            }
            
            if (window.Livewire) {
                window.Livewire.dispatch('notification-received', {
                    type: 'firebase',
                    message: body || title,
                    data: payload.data || {}
                });
            }
        });
    });
}
