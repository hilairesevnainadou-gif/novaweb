# TODO - Revue vue index tâches (modal)

- [ ] Corriger la gestion du modal de suppression (`resources/views/admin/tasks/index.blade.php`)
  - [x] Corriger ouverture/fermeture robuste (overlay + ESC + état body)
  - [x] Réinitialiser proprement l’état du bouton de confirmation
  - [x] Éviter les doubles soumissions pendant la requête
  - [x] Sécuriser le texte du message modal (fallback titre)
  - [x] Harmoniser la fermeture du modal après succès/erreur
  - [x] Corriger le positionnement du modal (centré viewport, plus en bas du tableau)

- [ ] Vérification finale
  - [ ] Vérifier syntaxe Blade/JS de `resources/views/admin/tasks/index.blade.php`
