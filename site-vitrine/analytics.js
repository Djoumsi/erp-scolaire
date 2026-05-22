/**
 * Google Analytics 4 Integration
 * Tech Solutions DDTP - MBOA School
 * Conforme RGPD et CNIL
 */

// ============================================
// 1. GOOGLE ANALYTICS 4 - CONFIGURATION INITIALE
// ============================================

// Configuration GA4
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

// ID de mesure GA4 - À remplacer par votre ID
const GA4_ID = 'G-XXXXXXXXXX'; // Remplacer par votre ID

// Initialisation avec consentement RGPD
gtag('config', GA4_ID, {
    'page_path': window.location.pathname,
    'page_title': document.title,
    'anonymize_ip': true,
    'cookie_flags': 'SameSite=None;Secure',
    'allow_google_signals': false, // RGPD compliance
    'allow_ad_personalization_signals': false // RGPD compliance
});

// ============================================
// 2. GESTION DU CONSENTEMENT RGPD
// ============================================

// Vérifier si l'utilisateur a déjà donné son consentement
function checkConsentCookie() {
    const consent = localStorage.getItem('erpscolaire_consent');
    if (consent) {
        const consentData = JSON.parse(consent);
        updateGAConsent(consentData);
    } else {
        showConsentBanner();
    }
}

// Afficher le bandeau de consentement
function showConsentBanner() {
    const banner = document.createElement('div');
    banner.id = 'consent-banner';
    banner.innerHTML = `
        <div style="
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 20px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            font-family: 'Segoe UI', Tahoma, sans-serif;
            z-index: 9999;
            border-top: 3px solid #0066cc;
        ">
            <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <strong style="display: block; margin-bottom: 0.5rem;">🍪 Nous utilisons des cookies</strong>
                    <p style="margin: 0; color: #666; font-size: 0.95rem;">
                        Nous utilisons Google Analytics pour analyser l'utilisation du site (conforme RGPD).
                        <a href="politique-confidentialite.html" style="color: #0066cc; text-decoration: none;">En savoir plus</a>
                    </p>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button id="reject-cookies" style="
                        padding: 10px 20px;
                        background: white;
                        color: #0066cc;
                        border: 2px solid #0066cc;
                        border-radius: 5px;
                        cursor: pointer;
                        font-weight: 600;
                        transition: all 0.3s;
                    ">Refuser</button>
                    <button id="accept-cookies" style="
                        padding: 10px 20px;
                        background: #0066cc;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-weight: 600;
                        transition: all 0.3s;
                    ">Accepter</button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(banner);

    // Event listeners
    document.getElementById('accept-cookies').addEventListener('click', function() {
        saveConsent(true);
        banner.style.display = 'none';
        updateGAConsent({ analytics: true });
    });

    document.getElementById('reject-cookies').addEventListener('click', function() {
        saveConsent(false);
        banner.style.display = 'none';
        updateGAConsent({ analytics: false });
    });
}

// Sauvegarder le consentement
function saveConsent(accepted) {
    const consentData = {
        analytics: accepted,
        timestamp: new Date().toISOString(),
        version: '1.0'
    };
    localStorage.setItem('erpscolaire_consent', JSON.stringify(consentData));
}

// Mettre à jour les paramètres GA4 en fonction du consentement
function updateGAConsent(consentData) {
    if (consentData.analytics === false) {
        // Désactiver GA4
        gtag('consent', 'update', {
            'analytics_storage': 'denied'
        });
    } else {
        // Activer GA4
        gtag('consent', 'update', {
            'analytics_storage': 'granted'
        });
    }
}

// ============================================
// 3. ÉVÉNEMENTS PERSONNALISÉS
// ============================================

// Event: Clic sur "Essayer la démo"
document.addEventListener('click', function(e) {
    if (e.target.textContent.includes('Essayer la démo') || e.target.textContent.includes('Accéder à la démo')) {
        gtag('event', 'click_demo', {
            'event_category': 'engagement',
            'event_label': 'demo_cta',
            'value': 1
        });
    }
});

// Event: Soumission formulaire contact
document.addEventListener('submit', function(e) {
    if (e.target.id === 'contactForm') {
        gtag('event', 'form_submit', {
            'event_category': 'conversion',
            'event_label': 'contact_form',
            'form_name': 'contact',
            'form_id': 'contactForm'
        });
    }

    if (e.target.id === 'newsletterForm') {
        gtag('event', 'newsletter_signup', {
            'event_category': 'conversion',
            'event_label': 'newsletter',
            'form_name': 'newsletter'
        });
    }
});

// Event: Clic sur plan de tarification
document.addEventListener('click', function(e) {
    if (e.target.textContent.includes('Commander') || e.target.textContent.includes('Demander devis')) {
        const planName = e.target.closest('.pricing-card')?.querySelector('.plan-name')?.textContent || 'unknown';
        gtag('event', 'select_plan', {
            'event_category': 'conversion',
            'event_label': planName.toLowerCase(),
            'plan': planName
        });
    }
});

// Event: Vue de page (pageview)
function trackPageView() {
    gtag('event', 'page_view', {
        'page_title': document.title,
        'page_path': window.location.pathname
    });
}

// Event: Défilement (scroll tracking)
let scrollTracked = false;
window.addEventListener('scroll', function() {
    if (!scrollTracked && window.scrollY > window.innerHeight) {
        gtag('event', 'scroll_depth', {
            'event_category': 'engagement',
            'event_label': 'page_scrolled',
            'scroll_percentage': Math.round((window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100)
        });
        scrollTracked = true;
    }
});

// Event: Clic sur lien externe
document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link && link.hostname !== window.location.hostname && link.target !== '_blank') {
        gtag('event', 'click_outbound', {
            'event_category': 'engagement',
            'event_label': link.hostname,
            'link_url': link.href
        });
    }
});

// ============================================
// 4. SUIVI DU TEMPS DE CHARGEMENT
// ============================================

if (window.performance && window.performance.timing) {
    window.addEventListener('load', function() {
        const perfData = window.performance.timing;
        const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;

        gtag('event', 'page_load_time', {
            'event_category': 'performance',
            'event_label': 'page_load',
            'value': pageLoadTime,
            'unit': 'milliseconds'
        });

        // Autres métriques
        const connectTime = perfData.responseEnd - perfData.requestStart;
        const renderTime = perfData.domComplete - perfData.domLoading;

        gtag('event', 'performance_metrics', {
            'event_category': 'performance',
            'connect_time': connectTime,
            'render_time': renderTime,
            'page_load_time': pageLoadTime
        });
    });
}

// ============================================
// 5. INITIALISATION
// ============================================

// Charger le script GA4 de manière asynchrone
function loadGA4Script() {
    const script = document.createElement('script');
    script.async = true;
    script.src = 'https://www.googletagmanager.com/gtag/js?id=' + GA4_ID;
    document.head.appendChild(script);
}

// Initialisation au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    loadGA4Script();
    checkConsentCookie();
    trackPageView();

    console.log('✓ Google Analytics 4 initié (RGPD compliant)');
});

// ============================================
// 6. FONCTION D'EXPORT
// ============================================

// Exporter la fonction gtag pour utilisation externe
window.sendGAEvent = function(eventName, eventData) {
    gtag('event', eventName, eventData);
};
