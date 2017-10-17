# rex_social

redaxo4 facebook twitter client
Konstruiert als Methode, um Facebook- und Twitter-Feeds ohne Cookies in die Redaxo-Seite einzubinden.

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
    
