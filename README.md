# eedomus plugin : Mass Switch - Commutateur en masse

![Nuki Logo](./dist/img/nikya_masswitch.png "Logo Mass Switch by Nikya")

* Plugin version : 1.0
* Origine : [GitHub/Nikya/massSwitch](https://github.com/Nikya/eedomusScript_massSwitch "Origine sur GitHub")

## Description
***Nikya eedomus plugin Mass Switch*** est un plugin pour la box domotique eedomus.  
Il permet de **_commuter en masse_ simultanément plusieurs équipements** vers des valeurs souhaitées.  
La principale force de ce script est que **les actions sont réalisées de façon _intelligente_** : que si nécessaire, afin de ne pas surcharger la box.

### Cas d'utilisation
- Atteindre toutes les lumières restées allumées en quittant la maison
    - Commute vers _Off_ seulement les lumières nécessaires.
- Allumer simultanément plusieurs équipements
    - Commute vers _On_ seulement les lumières nécessaires.
- Entre-ouvrir simultanément plusieurs volets
    - Commute vers une valeur souhaitée seulement les volets qui ne sont pas déjà dans la bonne position.

## Installation via store

Depuis le portail _eedomus_, cliquez sur
- `Configuration`
- `Ajouter ou supprimer un périphérique`
- `Store eedomus`
- puis sélectionner _Commutateur en masse_

Des informations seront demandées pour la création du plugin.  
Voir le paragraphe **_valeurs_** pour plus d'informations.

## Utilisation

Par défaut, le plugin génère un _actionneur http_ avec différentes combinaisons :

- **_Masse Off_** : Positionne à **0** les périphériques listés dans `VAR1`
- **_Masse On_** : Positionne à **100** les périphériques listés dans `VAR2`
- **_Masse valeur_** : Positionne à **la valeur souhaitée** les périphériques listés dans `VAR3`


- **Variante _intelligente_** : N'exécute l'action que si nécessaire (si n'est pas déjà dans l'état demandé)
- **Variante _forcée_** : Execute systématiquement l'action


## Installation manuelle

1. Télécharger le projet sur GitHub : [GitHub/Nikya/massSwitch](https://github.com/Nikya/eedomusScript_massSwitch "Origine sur GitHub")
1. Uploader le fichier `dist/massSwitch.php` sur la box ([Doc eedomus script](http://doc.eedomus.com/view/Scripts#Script_HTTP_sur_la_box_eedomus))
2. Créer manuellement un _actionneur http_ avec des appels vers ce script en renseignant les paramètres souhaités.

Exemple d'URL :

    https://localhost/script/?exec=massSwitch.php&toOn=123,456&toOff=147,258&toVal=159:20,753:70&force=true

Tous les paramètres son optionels.  
Voir le paragraphe **_valeurs_** pour plus d'informations.  
Le paramètre `force=true` oblige l'exécution de l'action (le script n'est plus en mode intelligent)
Le paramètre `force=true` oblige l'exécution de l'action (le script n'est plus en mode intelligent)

## Valeurs

- `toOff` : Liste de _periphId_ à positionner à _Off_ (0), séparés par des virgules
    - Une installation via le store place ces valeurs dans le champ `VAR1`
    - Exemple : `123456,789456,159753`
- `toOn` : Liste de _periphId_ à positionner à _On_ (100), séparés par des virgules
    - Une installation via le store place ces valeurs dans le champ `VAR2`
    - Exemple : `123456,789456,159753`
- `toVal` : Liste de _periphId_ à positionner à _la valeur souhaitée_, chacun suivi d'un `:` puis de la valeur souhaité et le tout séparés par des virgules
    - Une installation via le store place ces valeurs dans le champ `VAR3`
    - Exemple : `123456:20,789456:50,159753:70`

## Résultat

A titre d'information, l'appel de ce script répond par un XML servant de compte rendu d'éxecution (utile pour debugger si besoin).  
Les _XPath_ suivants sont diponibles :

* `/root/params/ToOffCount` : Nombre de périphérique trouvés à mettre _off_
* `/root/params/ToOnCount` : Nombre de périphérique trouvés à mettre _on_
* `/root/params/ToValCount` : Nombre de périphérique trouvés à mettre _à la valeur souhaitée_
* `/root/params/force` : Indicateur de forcer ou non les exécutions
* `/root/results/exe_msg_fr` : Phrase de compte rendu d'éxecution en Français
* `/root/results/exe_msg_en` : Phrase de compte rendu d'éxecution en Anglais
* `/root/results/exeCount` : Nombre d'exécution effectivement réalisées
* `/root/results/executions` : Détails des exécutions réalisées au format `pid`+`val`
