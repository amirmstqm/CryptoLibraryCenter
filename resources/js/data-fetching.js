/**
 * data-fetching.js
 *
 * PRIMARY source: Laravel API (/api/libraries) — data served from MySQL
 * FALLBACK source: Firebase Firestore — used if MySQL API is unavailable
 */

import { setLibraries } from './filter.js';

export async function fetchAndDisplayLibraries() {
    try {
        const success = await fetchFromMySQL();
        if (!success) {
            console.warn('MySQL API unavailable, falling back to Firebase...');
            await fetchFromFirebase();
        }
    } catch (err) {
        console.error('Error loading libraries:', err);
        await fetchFromFirebase();
    }
}

// 1. Fetch from MySQL via Laravel API
async function fetchFromMySQL() {
    try {
        const response = await fetch('/api/libraries', {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) return false;

        const librariesData = await response.json();

        if (!Array.isArray(librariesData) || librariesData.length === 0) return false;

        const normalized = librariesData.map(lib => ({
            ...lib,
            normalizedName: lib.name.toLowerCase(),
            pqcAlgorithms: normalizePqcAlgorithms(lib['pqc-algorithm']),
        }));

        setLibraries(normalized);
        console.info(`Loaded ${normalized.length} libraries from MySQL.`);
        return true;

    } catch (err) {
        console.warn('MySQL fetch error:', err.message);
        return false;
    }
}

// 2. Fallback: Fetch directly from Firebase
async function fetchFromFirebase() {
    const { getFirestore, collection, getDocs, enableIndexedDbPersistence } =
        await import('https://www.gstatic.com/firebasejs/9.0.0/firebase-firestore.js');
    const { initializeApp } =
        await import('https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js');
    const { default: firebaseConfig } = await import('./firebase-config.js');

    const app = initializeApp(firebaseConfig, 'fallback-app');
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
    console.info(`Loaded ${librariesData.length} libraries from Firebase (fallback).`);
}

function normalizePqcAlgorithms(raw) {
    if (!raw) return [];
    if (Array.isArray(raw)) return raw.map(a => a.trim());
    if (typeof raw === 'string') return raw.split(',').map(a => a.trim());
    return [];
}
