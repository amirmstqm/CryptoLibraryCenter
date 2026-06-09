/**
 * data-fetching.js
 *
 * Data source: Firebase Firestore
 */

import { setLibraries } from './filter.js';

export async function fetchAndDisplayLibraries() {
    try {
        await fetchFromFirebase();
    } catch (err) {
        console.error('Error loading libraries:', err);
    }
}

async function fetchFromFirebase() {
    const { getFirestore, collection, getDocs, enableIndexedDbPersistence } =
        await import('https://www.gstatic.com/firebasejs/9.0.0/firebase-firestore.js');
    const { initializeApp } =
        await import('https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js');
    const { default: firebaseConfig } = await import('./firebase-config.js');

    const app = initializeApp(firebaseConfig, 'main-app');
    const db  = getFirestore(app);

    enableIndexedDbPersistence(db).catch(err => console.warn(err));

    const snapshot = await getDocs(collection(db, 'libraries'));
    const librariesData = [];

    snapshot.forEach(doc => {
        const data = doc.data();
        if (data.show !== false) {
            data.id             = doc.id;
            data.normalizedName = data.name.toLowerCase();
            data.pqcAlgorithms  = normalizePqcAlgorithms(data['pqc-algorithm']);
            librariesData.push(data);
        }
    });

    setLibraries(librariesData);
    console.info(`Loaded ${librariesData.length} libraries from Firebase.`);
}

function normalizePqcAlgorithms(raw) {
    if (!raw) return [];
    if (Array.isArray(raw)) return raw.map(a => a.trim());
    if (typeof raw === 'string') return raw.split(',').map(a => a.trim());
    return [];
}
