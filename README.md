# rex_social

redaxo4 facebook twitter client

Entwickelt als Methode, um Facebook- und Twitter-Feeds ohne Cookies in die Redaxo-Seite einzubinden.

## Facebook

Facebook kann entweder per API oder Widget eingebunden werden.

### Einbindung per API

Beispiel:

    RexSocialFacebook::getFeed();
    RexSocialFacebook::getPage();
        
### Einbindung per Widget

Beispiel:

    RexSocialFacebook::getWidget();


## Twitter

Twitter kann entweder per API oder Widget eingebunden werden.

### Einbindung per API

Beispiel:

    RexSocialTwitter::getTweets();
        
Es wird eine App benötigt die unter https://apps.twitter.com erstellt werden kann. Folgende Daten der App müssen anschließend im Admin-Bereich hinterlegt werden:

- Consumer Key (API Key) und Consumer Secret (API Secret)
- Access Token und Access Token Secret
- Einbindung per Widget

Beispiel:

    RexSocialTwitter::getWidget();
    
## Beispiele für Aktivierungs-Text
    
    Derzeit ist die Einbindung von Facebook aus datenschutzrechtlichen Gründen deaktiviert, damit nicht automatisiert Daten weitergegeben werden. 
    Erst wenn Sie auf den Button klicken, wird die Anbindung an Facebook aktiv und Sie geben damit Ihre Einwilligung zum Datenaustaustausch mit dem dazugehörigen Dienst. Schon beim Aktivieren werden Daten an Dritte übertragen. 
