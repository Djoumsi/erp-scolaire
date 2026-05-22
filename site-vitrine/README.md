# 🎓 Site Vitrine MBOA School — Tech Solutions DDTP

## 📋 Description
Site vitrine professionnel pour présenter et vendre **MBOA School** au Cameroun.
Solution complète de gestion d'établissements scolaires pour le Cameroun.

**Status :** ✅ **EN LIGNE** sur Render.com
**URL de production :** `https://mboa-school.onrender.com` 
**Domaine :** `.cm` (Cameroun) - À configurer pour production

---

## 🚀 Déploiement Rapide

**Plateforme actuelle :** Render.com (Gratuit + stable)
**Temps de déploiement :** ~15 minutes
**Guide complet :** Voir `DEPLOYMENT-RENDER.md`

---

## 🎯 Contenu du site

### Pages/Sections
1. **Accueil (Hero)** — Présentation accroche
2. **Caractéristiques** — 8 modules clés
3. **Tarification** — 3 plans basés sur le nombre d'élèves
   - Basique : < 150 élèves → 35 000 FCFA/mois
   - Professionnel : 150-500 élèves → 85 000 FCFA/mois ⭐ Recommandé
   - Entreprise : > 500 élèves → 250 000 FCFA/mois
4. **Démo en ligne** — Accès direct au système (login admin)
5. **Témoignages** — 3 cas clients fictifs (Douala, Yaoundé, Buea)
6. **Formulaire de contact** — Recueil des demandes
7. **Footer** — Coordonnées, réseaux sociaux, liens

---

## 🎨 Design

### Couleurs
- **Primary (Bleu)** : `#0066cc` — Professionnel, confiance
- **Secondary (Vert)** : `#00a651` — Succès, croissance
- **Accent (Orange)** : `#ff6b35` — Appel à l'action
- **Dark** : `#1a1a1a` — Textes
- **Light** : `#f8f9fa` — Backgrounds

### Caractéristiques du design
- ✅ Design responsive (mobile-first)
- ✅ Gradient moderne (bleu/vert)
- ✅ Icons Bootstrap (bi bi-*)
- ✅ Animations CSS fluides
- ✅ Formulaire interactif
- ✅ Intégration WhatsApp
- ✅ Liens réseaux sociaux

---

## 📁 Structure des fichiers

```
site-vitrine/
├── index.html              # Page d'accueil principale
├── form-handler.php        # Handler formulaire de contact
├── logs/                   # Dossier logs (auto-créé)
│   ├── contacts_YYYY-MM-DD.json
│   └── contact_log.txt
└── README.md              # Ce fichier
```

---

## 🔧 Installation

### Prérequis
- PHP 7.4+ (pour form-handler.php)
- Serveur web (Apache, Nginx, WAMP)
- Domaine `.cm` enregistré

### Étapes d'installation

1. **Télécharger les fichiers**
```bash
mkdir -p /var/www/html/erpscolaire-vitrine
cp index.html form-handler.php /var/www/html/erpscolaire-vitrine/
```

2. **Configuration serveur web**

**Apache (.htaccess)**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Redirection HTTP → HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # Blocage d'accès aux fichiers sensibles
    RewriteRule ^logs/ - [F]
</IfModule>
```

**Nginx**
```nginx
server {
    listen 443 ssl http2;
    server_name erpscolaire.cm;
    
    root /var/www/html/erpscolaire-vitrine;
    index index.html;
    
    # Redirection HTTP → HTTPS
    if ($scheme != "https") {
        return 301 https://$server_name$request_uri;
    }
    
    # PHP handler
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Bloquer accès logs
    location ^~ /logs/ {
        deny all;
    }
}
```

3. **Permissions des dossiers**
```bash
chmod 755 /var/www/html/erpscolaire-vitrine
chmod 755 /var/www/html/erpscolaire-vitrine/logs
chown www-data:www-data /var/www/html/erpscolaire-vitrine/logs
```

4. **SSL/TLS (Let's Encrypt)**
```bash
certbot certonly --webroot -w /var/www/html/erpscolaire-vitrine -d erpscolaire.cm
```

---

## 📧 Formulaire de contact

### Fonctionnalités
- ✅ Validation côté client (HTML5)
- ✅ Validation côté serveur (PHP)
- ✅ Enregistrement JSON (logs)
- ✅ Réponse JSON pour UX fluide
- ✅ Email de notification (optionnel)

### Données collectées
1. Nom complet
2. Email
3. Numéro téléphone
4. Nom établissement
5. Nombre d'élèves (catégorie)
6. Message libre

### Fichiers de logs
- `logs/contacts_YYYY-MM-DD.json` — JSON structuré (15 champs)
- `logs/contact_log.txt` — Ligne simple (pour monitoring)

---

## 🔐 Sécurité

### Mesures implémentées
1. **Sanitization** : `htmlspecialchars()` + `trim()`
2. **Validation email** : `filter_var(FILTER_VALIDATE_EMAIL)`
3. **HTTPS obligatoire** : Redirection 301 HTTP → HTTPS
4. **Blocage répertoire logs** : `.htaccess` / `nginx.conf`
5. **CORS** : Formulaire POST même origine

### À améliorer
- [ ] CSRF token (ajouter session)
- [ ] Rate limiting (anti-spam)
- [ ] reCAPTCHA v3
- [ ] Email réel via SendGrid/Mailgun
- [ ] Base de données (au lieu de JSON)

---

## 🌐 Déploiement

### Serveurs camerounais recommandés
1. **Camtel** — Fournisseur national
2. **MTN Business** — Hosting + domaine .cm
3. **Ovh Cameroon** — Dedicated server
4. **Kinboo Cameroun** — Cloud hosting

### Registrar domaine .cm
- **Nic.cm** — Registrar officiel Cameroun
- Coût : ~15 000-20 000 FCFA/an
- Transfert DNS possible

### Étapes déploiement
1. Enregistrer domaine `erpscolaire.cm` sur Nic.cm
2. Configurer DNS vers votre serveur
3. Installer SSL (Let's Encrypt gratuit)
4. Upload fichiers site vitrine
5. Tester formulaire et démo
6. Monitoring (uptime, performances)

---

## 📊 Analytics & Conversion

### À ajouter
- [ ] Google Analytics 4
- [ ] Facebook Pixel
- [ ] Conversion tracking (démo, contact)
- [ ] Heatmaps (Hotjar)

### Métriques clés
- Taux de clic "Essayer démo"
- Taux de remplissage formulaire
- Taux de conversion (contact → client)
- Durée moyenne visite
- Taux de rebond

---

## 📞 Coordonnées

### Tech Solutions DDTP
- **Email** : contact@techsolutionsddtp.cm
- **WhatsApp** : +237 655 454 994
- **Adresse** : Douala, Logbessou, Cameroun
- **Facebook** : facebook.com/techsolutionsddtp
- **LinkedIn** : linkedin.com/company/techsolutionsddtp

---

## 🚀 Roadmap futur

### Phase 1 (Actuellement)
- ✅ Site vitrine statique
- ✅ Formulaire contact basique
- ✅ Tarification 3 plans
- ✅ Démo en ligne

### Phase 2 (Semaine 2)
- [ ] Blog/Blog de nouvelles
- [ ] Case studies détaillés
- [ ] Webinaires/Tutoriels vidéo
- [ ] FAQ interactif
- [ ] Intégration calendrier démo

### Phase 3 (Mois 2)
- [ ] Portal client (login)
- [ ] Dashboard auto-déploiement
- [ ] Ticketing support
- [ ] Knowledge base/Help center
- [ ] API partenaires

---

## 📄 Licence & Conditions

Ce site vitrine est la propriété de **Tech Solutions DDTP**. 
Tous droits réservés © 2026.

**Conditions d'utilisation** : À rédiger
**Politique de confidentialité** : À rédiger

---

## 📞 Support

Pour toute question sur le déploiement ou maintenance du site :
- 📧 Email : contact@techsolutionsddtp.cm
- 📱 WhatsApp : +237 655 454 994

---

**Dernière mise à jour** : 19 mai 2026
**Statut** : ✅ Prêt pour déploiement production
**Estimé déploiement** : 1-2 semaines
