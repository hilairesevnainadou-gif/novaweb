# TODO: Fix Task Create Cancel Redirect

## Plan approuvé par l'utilisateur

**Objectif:** Corriger le bouton "Annuler/Retour" dans la vue création de tâche globale (`admin/tasks/create`) pour rediriger vers la liste des projets (`admin/projects/index`) au lieu de la liste globale des tâches.

## Étapes à compléter:

### ✅ Étape 1: Créer ce fichier TODO.md [ TERMINÉE ]

### ✅ Étape 2: Appliquer les modifications edit_file sur resources/views/admin/tasks/create.blade.php
- Remplacer les 2 occurrences du href du bouton retour pour utiliser `route('admin.projects.index')` quand !$hasProject
- Fichiers impactés: `resources/views/admin/tasks/create.blade.php`

### ✅ Étape 3: Vérifier le résultat
- Accéder à `/admin/tasks/create` (création globale)
- Cliquer sur le bouton "Retour" (flèche gauche ou "Annuler")
- ✅ Doit rediriger vers `/admin/projects`
- ✅ Le sélecteur de projet fonctionne toujours correctement
- ✅ La création depuis un projet spécifique (`/admin/projects/{id}/tasks/create`) retourne toujours vers les tâches du projet

### ✅ Étape 4: Marquer comme terminé - Modal tableau projets fonctionnel dans meetings/global-index
- Utiliser `attempt_completion` une fois testé et validé

**Note:** Modification purement frontale (Blade). Aucune impact sur les contrôleurs ou routes existants.

