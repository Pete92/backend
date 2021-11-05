# PHP BackEnd REST

Tämä on ecommerce harjoituksen backendi, https://github.com/Pete92/ecommerce.

## Tietoa

Tässä on kaksi luokkaa esimmäinen on käyttäjän toiminnoille ja toinen on verkkokaupan tuotteet.

Ominaisuudet:

Pystyy rekisteröitymään uuden käyttäjän, kirjautumaan ja hakea käyttäjän tiedot.

Verkkokaupan tuotteita voi lisätä, hakea, hakea tietty tuote, päivittää tuote ja poistaa tuote. (Verkkokaupan pyynnöt tapahtuu vielä Postmanin kautta)

## Postman Reitit

Palauttaa kaikki tuoteet (GET)
https://backenddphp.herokuapp.com/items/read.php

Palauttaa halutun id:en tiedot(GET)
https://backenddphp.herokuapp.com/items/single_read.php/?id=3

Uusi tallennus (POST)
Lähetetään Postmanilla POST pyyntö täyttämällä vaadittavat inputit\
{\
"title": "...",\
"description": "...",\
"price": "...",\
"image": "...",\
"gtin": "..."

}\
https://backenddphp.herokuapp.com/items/create.php

Tietyn tuotteen päivitus(PUT)\
Lähetetään Postmanilla PUT pyyntö täyttämällä vaadittavat inputit\
{\
"id": 12,\
"title": "..."

}\
https://backenddphp.herokuapp.com/items/update.php


Tietyn tuotteen poisto(DELETE)\
Lähetetään Postmanilla DELETE pyyntö täyttämällä vaadittavat inputit\
{\
"id": 12\
}\
https://backenddphp.herokuapp.com/items/delete.php


### Rekisteröityminen ja Kirjautuminen käyttäen Postmania

Rekisteröityminen Lähetetään Postmanilla POST pyyntö täyttämällä vaadittavat inputit\
{\
"name": "testi",\
"email": "testi@testi.fi",\
"password": "testi123"\
}\
https://backenddphp.herokuapp.com/user/register.php

Kirjautuminen
Lähetetään Postmanilla POST pyyntö täyttämällä vaadittavat inputit\
{\
"email": "testi@testi.fi",\
"password": "testi123"\
}\
https://backenddphp.herokuapp.com/user/login.php
